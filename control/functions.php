<?php

    function url($addr){
        global $_G;
        echo $_G['url'].$addr;
    }

    function timebetween($start, $end){
    	$t = time();
    	$start = strtotime($start);
    	$end = strtotime($end);
    	if ($t >= $start and $t < $end) {
    		return true;
    	}else{
    		return false;
    	}
    }

    function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }