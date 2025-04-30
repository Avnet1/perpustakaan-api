<?php

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;


if (!function_exists('executeEncrypt')) {
    function executeEncrypt(array $payload): string
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
    function formatStringToSlug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        $string = trim($string, '-');

        return $string;
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
        return new LaravelResponseContract(false, 500, $e->getMessage(), $e);
    }
}
