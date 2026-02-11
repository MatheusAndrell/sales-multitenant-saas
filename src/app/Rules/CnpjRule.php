<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', $value);

        if (strlen($cnpj) !== 14) {
            $fail('O :attribute deve conter 14 dígitos.');
            return;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        // Validação dos dígitos verificadores
        for ($t = 12; $t < 14; $t++) {
            $sum = 0;
            $pos = $t - 7;

            for ($i = 0; $i < $t; $i++) {
                $sum += $cnpj[$i] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
            }

            $digit = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);

            if ($cnpj[$t] != $digit) {
                $fail('O :attribute informado é inválido.');
                return;
            }
        }
    }
}
