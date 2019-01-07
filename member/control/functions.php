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

    function addtotimeline($type, $for, $id, $subject){
        global $_DB;
        $stm = $_DB->prepare("INSERT INTO timeline(type,content_id,for_time,subject,taken) VALUES (:type, :content_id, :for, :subject, :taken)");
        $stm->bindParam(':type', $type);
        $stm->bindParam(':content_id', $id);
        $stm->bindParam(':for', $for);
        $stm->bindParam(':subject', $subject);
        $stm->bindParam(':taken', $_SESSION['uid']);
        $stm->execute();

        return true;
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

    function deniedpage(){
        include_once __DIR__.'/views/denied.page.php';
        include_once __DIR__.'/views/parts/footer.content.php';
        include_once __DIR__.'/views/parts/footer.common.php';
        exit();
    }
