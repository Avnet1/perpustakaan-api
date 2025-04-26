<?php

namespace App\Http\Interfaces;


interface LaravelResponseInterface
{
    public function getSuccess(): bool;
    public function getCode(): int;
    public function getMessage(): string;
    public function getData(): mixed;
}
