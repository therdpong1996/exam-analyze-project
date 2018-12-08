<div class="col-xl-10">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                  <div class="row align-items-center">
                    <div class="col">
                      <strong>Overview</strong>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <?php
                        $stmt = $_DB->prepare('SELECT * FROM q_and_a WHERE qa_subject = :subject AND qa_exam = :exam ORDER BY qa_order ASC');
                $stmt->bindParam(':subject', $session['examination_subject']);
                $stmt->bindParam(':exam', $session['examination_id']);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                    $stm->bindParam(':subject', $row['qa_subject']);
                    $stm->bindParam(':session', $session['session_id']);
                    $stm->bindParam(':exam', $row['qa_exam']);
                    $stm->bindParam(':question', $row['qa_id']);
                    $stm->execute();
                    $total = $stm->fetch(PDO::FETCH_ASSOC);

                    $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM answer_data WHERE ans_check = 1 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                    $stm->bindParam(':subject', $row['qa_subject']);
                    $stm->bindParam(':session', $session['session_id']);
                    $stm->bindParam(':exam', $row['qa_exam']);
                    $stm->bindParam(':question', $row['qa_id']);
                    $stm->execute();
                    $true = $stm->fetch(PDO::FETCH_ASSOC);

                    $precen = ($true['c'] / $total['c']) * 100;
                    $precen = floor($precen); ?>
                        <div class="progress-wrapper" style="padding-top: 0.5rem;">
                          <div class="progress-info">
                            <div style="white-space: nowrap;width: 600px;overflow: hidden;text-overflow: ellipsis;"><?php echo $row['qa_order']; ?>. <?php echo strip_tags($row['qa_question']); ?></div>
                            <div class="progress-percentage">
                              <span><i class="fa fa-check text-success"></i> <strong class="text-success"><?php echo $precen; ?>%</strong> | <i class="fa fa-times text-danger"></i> <strong class="text-danger"><?php echo 100 - $precen; ?>%</strong></span>
                            </div>
                          </div>
                          <div class="progress" style="background-color: #ff4c4c;">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?php echo $precen; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $precen; ?>%;"></div>
                          </div>
                        </div>
                    <?php
                } ?>
                </div>
            </div>
        </div>