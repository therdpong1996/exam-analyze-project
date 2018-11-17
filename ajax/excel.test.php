<?php
	// If you need to parse XLS files, include php-excel-reader
	require('../control/init.php');
	require('../library/spreadsheet-reader/php-excel-reader/excel_reader2.php');
	require('../library/spreadsheet-reader/SpreadsheetReader.php');

	$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

	if (isset($_POST["import"])){

		if(in_array($_FILES["file"]["type"],$allowedFileType)){

	        $targetPath = '../uploads/'.$_FILES['file']['name'];
	        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

	        $Reader = new SpreadsheetReader($targetPath);

	        $sheetCount = count($Reader->sheets());

	        for($i = 0; $i < $sheetCount; $i++){

	            $Reader->ChangeSheet($i);

	            foreach ($Reader as $Row){

	                $qa_question = "";
	                $qa_choice_1 = "";
	                $qa_choice_2 = "";
	                $qa_choice_3 = "";
	                $qa_choice_4 = "";
	                $qa_choice_true = "";

	                if(isset($Row[0])) {
	                    $qa_question = $Row[0];
	                }
	                if(isset($Row[1])) {
	                    $qa_choice_1 = $Row[1];
	                }
	                if(isset($Row[2])) {
	                    $qa_choice_2 = $Row[2];
	                }
	                if(isset($Row[3])) {
	                    $qa_choice_3 = $Row[3];
	                }
	                if(isset($Row[4])) {
	                    $qa_choice_4 = $Row[4];
	                }
	                if(isset($Row[5])) {
	                    $qa_choice_true = $Row[5];
	                }

	                if (!empty($qa_question) and !empty($qa_choice_true)) {
	                	$stm = $_DB->prepare("INSERT INTO q_and_a (qa_subject,qa_exam,qa_question,qa_choice_1,qa_choice_2,qa_choice_3,qa_choice_4,qa_choice_true) VALUES (:subject, :exam, :question, :ch1, :ch2, :ch3, :ch4, :true)");
					    $stm->bindParam(':subject', $_POST['subject'], PDO::PARAM_INT);
					    $stm->bindParam(':exam', $_POST['exam'], PDO::PARAM_INT);
					    $stm->bindParam(':question', $qa_question, PDO::PARAM_STR);
					    $stm->bindParam(':ch1', $qa_choice_1, PDO::PARAM_STR);
					    $stm->bindParam(':ch2', $qa_choice_2, PDO::PARAM_STR);
					    $stm->bindParam(':ch3', $qa_choice_3, PDO::PARAM_STR);
					    $stm->bindParam(':ch4', $qa_choice_4, PDO::PARAM_STR);
					    $stm->bindParam(':true', $qa_choice_true, PDO::PARAM_STR);
					    $stm->execute();
					    $lastid = $_DB->lastInsertId();
	                }

	                echo json_encode(['state'=>true, 'msg'=>'Import ไฟล์ '.$_FILES['file']['tmp_name'].' เรียบร้อย']);

	             }
	         }

	    }else{
	    	echo json_encode(['state'=>false, 'msg'=>'lid File Type. Upload Excel File.']);
  		}
    }