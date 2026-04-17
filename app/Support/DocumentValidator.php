<?php

namespace App\Support;

class DocumentValidator
{
    public static function normalize(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value);
    }

    public static function isCpf(string $digits): bool
    {
        if (strlen($digits) !== 11 || preg_match('/^(\d)\1{10}$/', $digits)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += (int) $digits[$c] * (($t + 1) - $c);
            }
            $d = (10 * $d) % 11;
            if ($d === 10) {
                $d = 0;
            }
            if ($d !== (int) $digits[$c]) {
                return false;
            }
        }

        return true;
    }

    public static function isCnpj(string $digits): bool
    {
        if (strlen($digits) !== 14 || preg_match('/^(\d)\1{13}$/', $digits)) {
            return false;
        }

        $calc = function (string $digits, int $length): int {
            $weights = $length === 12
                ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
                : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $digits[$i] * $weights[$i];
            }
            $mod = $sum % 11;

            return $mod < 2 ? 0 : 11 - $mod;
        };

        return $calc($digits, 12) === (int) $digits[12]
            && $calc($digits, 13) === (int) $digits[13];
    }

    public static function isValid(string $raw): bool
    {
        $digits = self::normalize($raw);

        return self::isCpf($digits) || self::isCnpj($digits);
    }
}
