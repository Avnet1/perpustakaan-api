<?php

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;


if (!function_exists('sanitizeIdentifier')) {
    function sanitizeIdentifier(string $input): string
    {
        // Only allow alphanumeric + underscore
        return preg_replace('/[^a-zA-Z0-9_]/', '', $input);
    }
}

if (!function_exists('getFileUrl')) {
    function getFileUrl($item)
    {
        if ($item != null) {
            return asset('storage/' . $item);
        }

        return $item;
    }
}


if (!function_exists('generateCodAccess')) {
    function generateCodAccess(): string
    {
        $segments = [];

        for ($i = 0; $i < 4; $i++) {
            $segment = '';
            for ($j = 0; $j < 4; $j++) {
                $random = rand(0, 2); // 0: digit, 1: uppercase, 2: uppercase
                if ($random === 0) {
                    $segment .= rand(0, 9);
                } else {
                    $segment .= chr(rand(65, 90)); // ASCII A-Z
                }
            }
            $segments[] = $segment;
        }

        return implode('-', $segments); // e.g., AZ12-KK24-1231-BBBC
    }
}


if (!function_exists('queryCheckExisted')) {
    function queryCheckExisted($query, $condition, $pkKey = null, $pkValue = null)
    {
        if (count($condition) > 0) {
            $query->where(function ($q) use ($condition) {
                $i = 0;
                foreach ($condition as $key => $value) {
                    $isFirst = $i === 0;

                    if (is_array($value)) {
                        $isFirst ? $q->whereIn($key, $value) : $q->orWhereIn($key, $value);
                    } elseif (is_null($value)) {
                        $isFirst ? $q->whereNull($key) : $q->orWhereNull($key);
                    } elseif (strpos($value, '%') !== false) {
                        $isFirst ? $q->where($key, 'ilike', $value) : $q->orWhere($key, 'ilike', $value);
                    } else {
                        $isFirst ? $q->where($key, '=', $value) : $q->orWhere($key, '=', $value);
                    }

                    $i++;
                }
            });
        }

        if ($pkKey == null) {
            return $query->first();
        }

        return $query->where($pkKey, '!=', $pkValue)->first();
    }
}



if (!function_exists('executeEncrypt')) {
    function executeEncrypt($payload): string
    {
        return Crypt::encrypt(json_encode($payload));
    }
}

if (!function_exists('executeDecrypt')) {
    function executeDecrypt(string $plainText): array
    {
        return json_decode(Crypt::decrypt($plainText), true);
    }
}


if (!function_exists('getOtpRandomize')) {
    function makeMailSender(string $subject, string $title, string $content, mixed $payload = null, mixed $attachments = []): array
    {
        return [
            "mail_title" => $title,
            "mail_subject" => $subject,
            "mail_content" => $content,
            "mail_payload" => $payload,
            "mail_attachments" => $attachments,
            'supported' => [
                "company_url" => env('COMPANY_URL', 'https://avnet.id'),
                "company_name" => env('COMPANY_NAME', 'Indosistem'),
            ]
        ];
    }
}

if (!function_exists('getOtpRandomize')) {
    function getOtpRandomize(): object
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $otp = '';

        // Menghasilkan 6 karakter acak
        for ($i = 0; $i < 6; $i++) {
            $otp .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return (object) [
            "otp_code" => $otp,
            "otp_time" => 300
        ];
    }
}


if (!function_exists('deleteFileInStorage')) {
    function deleteFileInStorage($path)
    {
        if ($path != null) {
            Storage::disk('public')->delete($path);
        }
    }
}

if (!function_exists('setPagination')) {
    function setPagination($data, $totalRow, $page = 1, $limit = 20): object
    {
        // Ensure page and limit are at least 1
        $page = max($page, 1);
        $limit = max($limit, 1);

        // Calculate pagination details
        $totalPage = ceil($totalRow / $limit);
        $nextPage = $page < $totalPage ? $page + 1 : null;
        $prevPage = $page > 1 ? $page - 1 : null;

        // Prepare the pagination response
        return (object)[
            'rows' => $data,
            'total_page' => $totalPage,
            'next_page' => $nextPage,
            'prev_page' => $prevPage,
            'limit' => $limit,
            'page' => $page,
            'total_row' => $totalRow,
        ];
    }
}

if (!function_exists('defineRequestOrder')) {
    function defineRequestOrder($request, $defaultOrder = ['created_at', 'ASC'], $sortColumn = []): object
    {
        $orderOption = [];
        $direction = $request->query('direction_name', $defaultOrder[0]);
        $orders = strtoupper($request->query('order_name', $defaultOrder[1]));

        if (is_string($orders) && is_string($direction)) {
            // Single direction and order
            if (isset($sortColumn[$direction])) {
                $orderOption[$sortColumn[$direction]] = $orders;
            }
        } else {
            // Multiple directions and orders
            $content = [];
            $directions = is_array($direction) ? $direction : [$direction];
            $ordersArray = is_array($orders) ? $orders : [$orders];
            $count = min(count($directions), count($ordersArray));

            for ($index = 0; $index < $count; $index++) {
                if (isset($sortColumn[$directions[$index]])) {
                    $content[$sortColumn[$directions[$index]]] = $ordersArray[$index];
                }
            }

            $orderOption = $content;
        }

        return (object) $orderOption;
    }
}


if (!function_exists('defineRequestPaginateArgs')) {
    function defineRequestPaginateArgs($request): object
    {
        // Fetch query parameters
        $page = $request->query('page', 1); // Default to page 1
        $limit = $request->query('limit', 10); // Default to limit 10
        $search = $request->query('search', ''); // Default to empty search string
        $directionName = $request->query('direction_name', ''); // Additional direction name parameter
        $orderName = $request->query('order_name', 'asc'); // Default sort order 'asc'

        $skip = ($page - 1) * $limit;

        return (object) [
            'page' => $page,
            'skip' => max(0, $skip), // Ensure skip is non-negative
            'search' => $search,
            'limit' => $limit,
            'direction_name' => $directionName,
            'order_name' => $orderName,
        ];
    }
}


if (!function_exists('setUser')) {
    function setUser(Request $request, mixed $user)
    {
        $request->attributes->set('user', $user);
    }
}

if (!function_exists('getUser')) {
    function getUser(Request $request)
    {
        return $request->attributes->get('user');
    }
}


if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone)
    {
        // Example: format to (123) 456-7890
        return preg_replace("/(\d{3})(\d{3})(\d{4})/", '($1) $2-$3', $phone);
    }
}

if (!function_exists('generateUniqueCode')) {
    function generateUniqueCode($length = 8)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersArray = str_split($characters);

        if ($length > count($charactersArray)) {
            throw new InvalidArgumentException('Length exceeds unique characters available.');
        }

        shuffle($charactersArray);

        return substr(implode('', $charactersArray), 0, $length);
    }
}

if (!function_exists('generateRandomPassword')) {
    function generateRandomPassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';

        for ($i = 0; $i < $length; ++$i) {
            $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }
}

/* Convert string to slug */
if (!function_exists('formatStringToSlug')) {
    function formatStringToSlug(string $string, string $typeFormat = '-'): string
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', $typeFormat, $string);
        return trim($string, $typeFormat);
    }
}

/* Convert string to slug */
if (!function_exists('formatStringToSlug')) {
    function formatStringToTag($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '_', $string);
        $string = trim($string, '_');

        return $string;
    }
}

/* Convert string to slug */
if (!function_exists('formatStringToSlug')) {
    function sendErrorResponse(Exception $e): LaravelResponseInterface
    {
        return new LaravelResponseContract(false, 400, $e->getMessage(), $e);
    }
}
