<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCnpj implements Rule
{
    public function passes($attribute, $value)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $value);
        
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        return $cnpj[12] == $digit1 && $cnpj[13] == $digit2;
    }

    public function message()
    {
        return 'O CNPJ informado é inválido.';
    }
}