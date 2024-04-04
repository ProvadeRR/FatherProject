<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class NumberToStringHelper
{
    public static function convert(int $number): string
    {
        static $dic = array(
            array(
                -2 => 'дві',
                -1 => 'одна',
                1 => 'один',
                2 => 'два',
                3 => 'три',
                4 => 'чотири',
                5 => 'п’ять',
                6 => 'шість',
                7 => 'сім',
                8 => 'вісім',
                9 => 'дев’ять',
                10 => 'десять',
                11 => 'одинадцять',
                12 => 'дванадцять',
                13 => 'тринадцять',
                14 => 'чотирнадцять',
                15 => 'п"ятнадцять',
                16 => 'шістнадцять',
                17 => 'сімнадцять',
                18 => 'вісімнадцять',
                19 => 'дев’ятнадцять',
                20 => 'двадцять',
                30 => 'тридцять',
                40 => 'сорок',
                50 => 'п’ятдесят',
                60 => 'шістдесят',
                70 => 'сімдесят',
                80 => 'вісімдесят',
                90 => 'дев’яносто',
                100 => 'сто',
                200 => 'двісті',
                300 => 'триста',
                400 => 'чотириста',
                500 => 'п’ятсот',
                600 => 'шістсот',
                700 => 'сімсот',
                800 => 'вісімсот',
                900 => 'дев’ятсот'
            ),
            array(
                array('гривня', 'гривні', 'гривень'),
                array('тисяча', 'тисячі', 'тисяч'),
                array('мільйон', 'мільйона', 'мільйонів'),
                array('мільярд', 'мільярда', 'мільярдів'),
                array('трильйон', 'трильйона', 'трильйонів'),
                array('квадрильйон', 'квадрильйона', 'квадрильйонів'),
            ),
            array(
                2, 0, 1, 1, 1, 2
            )
        );

        $string = array();

        $number = str_pad($number, ceil(strlen($number) / 3) * 3, 0, STR_PAD_LEFT);

        $parts = array_reverse(str_split($number, 3));

        foreach ($parts as $i => $part) {

            if ($part > 0) {

                $digits = array();

                if ($part > 99) {
                    $digits[] = floor($part / 100) * 100;
                }

                if ($mod1 = $part % 100) {
                    $mod2 = $part % 10;
                    $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag * $mod1;
                    } else {
                        $digits[] = floor($mod1 / 10) * 10;
                        $digits[] = $flag * $mod2;
                    }
                }

                $last = abs(end($digits));

                foreach ($digits as $j => $digit) {
                    $digits[$j] = $dic[0][$digit];
                }

                $digits[] = $dic[1][$i][(($last %= 100) > 4 && $last < 20) ? 2 : $dic[2][min($last % 10, 5)]];

                array_unshift($string, join(' ', $digits));
            }
        }

        $result = join(' ', $string);

        if (!Str::contains($result, ['гривень'])) {
            $result .= ' гривень';
        }

        return $result;
    }
}
