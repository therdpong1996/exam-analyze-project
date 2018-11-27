<?php
    header('Content-type: application/json');
    session_start();
    if (!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }
    require '../control/init.php';
    require '../library/spreadsheet-reader/php-excel-reader/excel_reader2.php';
    require '../library/spreadsheet-reader/SpreadsheetReader.php';

    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    if ($_SESSION['role'] != 2) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

    if ($_SESSION['uid'] != $_POST['qa_owner']) {
        echo json_encode(['state' => false, 'msg' => 'No permission']);
        exit;
    }

        if (in_array($_FILES['file']['type'], $allowedFileType)) {
            $targetPath = '../uploads/'.$_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

            $Reader = new SpreadsheetReader($targetPath);

            $sheetCount = count($Reader->sheets());

            $stm = $_DB->prepare('SELECT COUNT(qa_id) AS c FROM q_and_a WHERE qa_subject = :subj AND qa_exam = :exam');
            $stm->bindParam(':subj', $_POST['subject'], PDO::PARAM_INT);
            $stm->bindParam(':exam', $_POST['exam'], PDO::PARAM_INT);
            $stm->execute();
            $old_order = $stm->fetch(PDO::FETCH_ASSOC);
            $old_order = $old_order['c'];

            for ($i = 0; $i < $sheetCount; ++$i) {
                $Reader->ChangeSheet($i);

                foreach ($Reader as $Row) {
                    $qa_question = '';
                    $qa_choice_1 = '';
                    $qa_choice_2 = '';
                    $qa_choice_3 = '';
                    $qa_choice_4 = '';
                    $qa_choice_true = '';

                    if (isset($Row[0])) {
                        $var = explode('!img=', $Row[0]);
                        if (isset($var[1])) {
                            $qa_question = '<p><img src="'.$var[1].'" width="50%" class="img-thumbnail" /></p><p>'.$var[0].'</p>';
                        } else {
                            $qa_question = $var[0];
                        }
                    }
                    if (isset($Row[1])) {
                        $qa_choice_1 = $Row[1];
                    }
                    if (isset($Row[2])) {
                        $qa_choice_2 = $Row[2];
                    }
                    if (isset($Row[3])) {
                        $qa_choice_3 = $Row[3];
                    }
                    if (isset($Row[4])) {
                        $qa_choice_4 = $Row[4];
                    }
                    if (isset($Row[5])) {
                        $qa_choice_true = $Row[5];
                    }

                    $order = $old_order + $i + 1;

                    if (!empty($qa_question) and !empty($qa_choice_true)) {
                        $stm = $_DB->prepare('INSERT INTO q_and_a (qa_subject,qa_exam,qa_question,qa_choice_1,qa_choice_2,qa_choice_3,qa_choice_4,qa_choice_true,qa_order) VALUES (:subject, :exam, :question, :ch1, :ch2, :ch3, :ch4, :true, :order)');
                        $stm->bindParam(':subject', $_POST['subject'], PDO::PARAM_INT);
                        $stm->bindParam(':exam', $_POST['exam'], PDO::PARAM_INT);
                        $stm->bindParam(':question', $qa_question, PDO::PARAM_STR);
                        $stm->bindParam(':ch1', $qa_choice_1, PDO::PARAM_STR);
                        $stm->bindParam(':ch2', $qa_choice_2, PDO::PARAM_STR);
                        $stm->bindParam(':ch3', $qa_choice_3, PDO::PARAM_STR);
                        $stm->bindParam(':ch4', $qa_choice_4, PDO::PARAM_STR);
                        $stm->bindParam(':true', $qa_choice_true, PDO::PARAM_STR);
                        $stm->bindParam(':order', $order, PDO::PARAM_INT);
                        $stm->execute();
                        $lastid = $_DB->lastInsertId();
                    }
                }
            }
            unlink($targetPath);
            echo json_encode(['state' => true, 'msg' => 'Import ไฟล์ '.$_FILES['file']['name'].' เรียบร้อย']);
        } else {
            echo json_encode(['state' => false, 'msg' => 'lid File Type. Upload Excel File.']);
        }
