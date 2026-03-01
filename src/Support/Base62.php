<?php

namespace Mohammad\Shortener\Support;

class Base62
{
    protected static string $chars =
        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode(int $num): string
    {
        $base = strlen(self::$chars);
        $str = '';

        while ($num > 0) {
            $str = self::$chars[$num % $base] . $str;
            $num = intdiv($num, $base);
        }

        return $str;
    }
}