<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($cpf) !== 11) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Rejeita CPFs com todos os dígitos iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($c = 0; $c < $t; $c++) {
                $sum += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $remainder = ((10 * $sum) % 11) % 10;
            if ((int) $cpf[$t] !== $remainder) {
                $fail('O CPF informado é inválido.');
                return;
            }
        }
    }
}