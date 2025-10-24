<?php

namespace Core;

class Validator
{
    private array $errors = [];

    public function required(array $data, string $field, string $message): void
    {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $this->errors[$field][] = $message;
        }
    }

    public function numeric(array $data, string $field, string $message): void
    {
        if (!isset($data[$field]) || !is_numeric($data[$field])) {
            $this->errors[$field][] = $message;
        }
    }

    public function max(array $data, string $field, float $maxValue, string $message): void
    {
        if (isset($data[$field]) && (float)$data[$field] > $maxValue) {
            $this->errors[$field][] = $message;
        }
    }

    public function boolean(array $data, string $field, string $message): void
    {
        if (!isset($data[$field])) {
            $this->errors[$field][] = $message;
        }
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
