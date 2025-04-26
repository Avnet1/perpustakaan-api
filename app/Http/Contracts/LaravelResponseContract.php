<?php

namespace App\Http\Contracts;

use App\Http\Interfaces\LaravelResponseInterface;

class LaravelResponseContract implements LaravelResponseInterface {
    public bool $success;  // Change private to public
    public int $code;
    public string $message;
    public mixed $data;

    public function __construct(bool $success, int $code, string $message, mixed $data = [])
    {
        $this->success = $success;
        $this->code = $code;

        if (empty($message)) {
            $this->message = 'Tidak ada pesan';
        } else {
            $this->message = $message;
        }

        $this->data = $data;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return (string) $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
