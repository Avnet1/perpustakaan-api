<?php

namespace App\Http\Modules\Superadmin\Auth;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Mail\PortalEmailSender;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService
{
    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function login($payload): LaravelResponseInterface
    {
        try {
            $user = $this->repository->findIdentity([
                'email' => $payload->email,
            ]);

            if (!$user) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $user);
            }

            if (!Hash::check($payload->password, $user->password)) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.passwordInvalid'), $payload);
            }

            $credentials = [
                'email' => $user->email, // This should be the custom field you want to use
                'password' => $payload->password,        // The password provided by the user
            ];

            // Attempt to generate the JWT token using the custom credentials
            if (!$token = JWTAuth::attempt($credentials)) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.tokenGenerate'), $token);
            }

            $jwtPayload = [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
                'role_id' => $user->role->role_id,
                'role_name' => $user->role->role_name,
                'role_slug' => $user->role->role_slug,
            ];

            $token = JWTAuth::fromUser($user, $jwtPayload);

            if (!$token) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.tokenGenerate'), $token);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.login'), (object) [
                'access_token' => $token,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function forgotPassword($payload): LaravelResponseInterface
    {
        try {
            $user = $this->repository->findIdentity([
                'email' => $payload['email'],
            ]);

            if (!$user) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $user);
            }

            $result = $this->repository->insertOtp($payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.storeReset', ['attribute' => 'Email dan OTP']), null);
            }

            $data = makeMailSender(
                $payload['email'],
                'Reset Password Request',
                'emails.portals.forgot_password',

                array_merge($payload, [
                    'name' => $user->name,
                    'username' => $user->username,
                    'role_name' => $user->role->role_name,
                ])
            );

            Mail::to($payload['email'])->send(new PortalEmailSender($data));

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.resetPassword'), ['email' => $payload['email']]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function verificationOtp($where): LaravelResponseInterface
    {
        try {
            $row = $this->repository->getOtp($where);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => "Kode Otp {$where['otp_code']}"]), $where);
            }

            if ($row->has_verified) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.hadVerified', ['attribute' => "Kode Otp {$where['otp_code']}"]), $where);
            }

            $diffSeconds = Carbon::now()->diffInSeconds(Carbon::parse($row->created_at));

            if ($diffSeconds > $row->otp_time) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.expiredOtp', ['attribute' => "Kode Otp {$where['otp_code']}", 'num' => '5']), $where);
            }

            $result = $this->repository->verifiedOtp($where, ['has_verified' => true]);

            if (!$result) {
                return new LaravelResponseContract(true, 200, __('validation.custom.error.auth.verifiedOtp', ['attribute' => "Kode Otp {$where['otp_code']}"]), $where);
            }

            $token = executeEncrypt($where);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.verifiedOtp', ['attribute' => "Kode Otp {$where['otp_code']}"]), [
                'permission_code' => $token,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function resetPassword($payload): LaravelResponseInterface
    {
        $encryption = executeDecrypt($payload['permission_code']);
        $condition = [
            'email' => $encryption['email'],
            'otp_code' => $encryption['otp_code'],
        ];

        try {
            $row = $this->repository->getOtp($condition);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.permissionCode'), $payload);
            }

            $this->repository->deleteOtp($condition);

            $user = $this->repository->findIdentity([
                'email' => $encryption['email'],
            ]);

            if (!$user) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $payload);
            }

            $user->password = Hash::make($payload['new_password']);
            $user->updated_at = now();
            $user->updated_by = $user->user_id;
            $user->save();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.changePassword'), $user);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function manualChangePassword(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $user = $this->repository->findIdentity([
                'user_id' => $id,
            ]);

            if (!$user) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $payload);
            }

            $user->password = Hash::make($payload['new_password']);
            $user->updated_at = now();
            $user->updated_by = $id;
            $user->save();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.changePassword'), $user);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function refreshToken(): LaravelResponseInterface
    {
        // Get the token from the request (usually in the Authorization header as a Bearer token)
        $token = JWTAuth::getToken();

        // If no token is provided, return an error
        if (!$token) {
            return new LaravelResponseContract(false, 404, __('validation.custom.error.auth.noProvideToken'), $token);
        }

        try {
            // Refresh the token
            $newToken = JWTAuth::refresh($token);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.refreshToken'), [
                'access_token' => $newToken,
            ]);
        } catch (JWTException $e) {
            return sendErrorResponse($e);
        }
    }

    /** Fetch Profile */
    public function fetchProfile(string $id): LaravelResponseInterface
    {
        try {
            $result = $this->repository->findById($id);

            if ($result->photo != null) {
                $result->photo = getFileUrl($result->photo);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.fetch', ['attribute' => 'profil']), $result);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    /** Update Profile */
    public function updateProfile(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'User']), $row);
            }

            $result = $this->repository->updateUser($id, (array) $payload);

            if (!$result) {
                return new LaravelResponseContract(false, 400, __('validation.custom.error.auth.update', ['attribute' => 'user']), $result);
            }

            if (isset($payload->email)) {
                if ($payload->email != $row->email) {
                    JWTAuth::invalidate(JWTAuth::getToken());
                }
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.auth.update', ['attribute' => 'user']), (object) [
                'user_id' => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }


    public function uploadPhoto(string $id, mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {

            $result = $this->repository->update($id, (array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->photo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.uploadImage', ["attribute" => "file photo"]), $result);
            }

            DB::commit();

            return new LaravelResponseContract(true, 200, __('validation.custom.success.default.uploadImage', ["attribute" => "file photo"]), $result);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->icon);
            return sendErrorResponse($e);
        }
    }
}
