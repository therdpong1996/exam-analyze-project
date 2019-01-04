<?php
    header('Content-type: application/json');
    session_start();
    $id = $_POST['stu_id'];
    $verify_url = 'https://arit.rmutl.ac.th/search_email/search';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "search_text=$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
    $server_output = curl_exec($ch);
    
    preg_match_all('/<td class="text-left">(.+[^\/])<\/td>/', $server_output, $match);
    
    if($match[1][0]){
        echo json_encode(['state'=>true,'full_name'=>$match[1][0],'email'=>$match[1][1]]);
    }else{
        echo json_encode(['state'=>false,'msg'=>'No result']);
    }
    exit;
        
