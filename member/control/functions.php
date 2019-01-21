<?php

    function url($addr)
    {
        global $_G;
        echo $_G['url'].$addr;
    }

    function furl($addr)
    {
        global $_G;
        echo $_G['furl'].$addr;
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

    function insertFirestore($atid, $title, $poston, $subject, $auther, $content)
    {
        $data = "atid=$atid&title=$title&poston=$poston&subject_title=$subject&full_name=$auther&content=$content";
        $url = 'https://us-central1-cat-project-rmutl.cloudfunctions.net/Insert';
        curl($url, $data);
    }

    function updateFirestore($atid, $title, $poston, $subject, $auther, $content)
    {
        $data = "atid=$atid&title=$title&poston=$poston&subject_title=$subject&full_name=$auther&content=$content";
        $url = 'https://us-central1-cat-project-rmutl.cloudfunctions.net/Update';
        curl($url, $data);
    }

    function deleteFirestore($atid)
    {
        $data = 'atid='.$atid;
        $url = 'https://us-central1-cat-project-rmutl.cloudfunctions.net/Delete';
        curl($url, $data);
    }

    function curl($url, $data){

        $ch = curl_init();
        $headers = [
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: en-US,en;q=0.5',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        $out = curl_exec($ch);
        curl_close($ch);

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

    function removefromtimeline($type, $id){
        global $_DB;
        $stm = $_DB->prepare("DELETE FROM timeline WHERE type = :type AND content_id = :content_id");
        $stm->bindParam(':type', $type);
        $stm->bindParam(':content_id', $id);
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
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
        include_once '../views/denied.page.php';
        include_once '../views/parts/footer.content.php';
        include_once '../views/parts/footer.common.php';
        exit();
    }
