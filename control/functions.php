<?php

    function url($addr)
    {
        global $_G;
        echo $_G['url'].$addr;
    }

    function timebetween($start, $end)
    {
        $t = time();
        $start = strtotime($start);
        $end = strtotime($end);
        if ($t >= $start and $t < $end) {
            return true;
        } else {
            return false;
        }
    }

    function UniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);

        return array_slice($numbers, 0, $quantity);
    }

    function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function __($str){
        echo $str;
    }
