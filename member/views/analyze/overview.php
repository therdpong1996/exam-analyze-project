<div class="col-xl-10">
            <div class="card shadow">
                <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php if($examNum['num'] > 0){ ?>
                    <li class="nav-item">
                      <a class="nav-link <?php echo $examNum['num'] > 0 ? 'active' : ''; ?>" id="Classic-tab" data-toggle="tab" href="#Classic" role="tab" aria-controls="Classic" aria-selected="true">Classic Overview</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                      <a class="nav-link <?php echo $examNum['num'] == 0 ? 'active' : ''; ?>" id="Adaptive-tab" data-toggle="tab" href="#Adaptive" role="tab" aria-controls="Adaptive" aria-selected="false">Adaptive Overview</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade <?php echo $examNum['num'] > 0 ? 'show active' : ''; ?>" id="Classic" role="tabpanel" aria-labelledby="Classic-tab">
                  <?php
                    $stmt = $_DB->prepare("SELECT DISTINCT(question),qa_id,qa_subject,qa_exam,qa_order,qa_question FROM answer_data JOIN q_and_a ON answer_data.question = q_and_a.qa_id WHERE answer_data.session = :session AND answer_data.temp = 0 ORDER BY q_and_a.qa_order ASC");
                    $stmt->bindParam(':session', $session['session_id']);
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
                        $precen = round((($true['c'] / $total['c']) * 100), 2);
                  ?>
                      
                          <div class="progress-wrapper" style="padding-top: 0.5rem;">
                            <div class="progress-info">
                              <div style="white-space: nowrap;width: 600px;overflow: hidden;text-overflow: ellipsis;"><a style="text-decoration: none" href="?session_id=<?php echo $session['session_id']; ?>&n=<?php echo $row['qa_id']; ?>"><?php echo $row['qa_order']; ?>. <?php echo strip_tags($row['qa_question']); ?></a></div>
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
                <div class="tab-pane fade <?php echo $examNum['num'] == 0 ? 'show active' : ''; ?>" id="Adaptive" role="tabpanel" aria-labelledby="Adaptive-tab">
                  <?php
                    $stmt = $_DB->prepare("SELECT DISTINCT(question),qa_id,qa_subject,qa_exam,qa_order,qa_question FROM adaptive_answer_data JOIN q_and_a ON adaptive_answer_data.question = q_and_a.qa_id WHERE adaptive_answer_data.session = :session AND adaptive_answer_data.temp = 0 ORDER BY q_and_a.qa_order ASC");
                    $stmt->bindParam(':session', $session['session_id']);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                        $stm->bindParam(':subject', $row['qa_subject']);
                        $stm->bindParam(':session', $session['session_id']);
                        $stm->bindParam(':exam', $row['qa_exam']);
                        $stm->bindParam(':question', $row['qa_id']);
                        $stm->execute();
                        $total = $stm->fetch(PDO::FETCH_ASSOC);

                        $stm = $_DB->prepare('SELECT COUNT(id) AS c FROM adaptive_answer_data WHERE ans_check = 1 AND subject = :subject AND session = :session AND examination = :exam AND question = :question AND temp = 0');
                        $stm->bindParam(':subject', $row['qa_subject']);
                        $stm->bindParam(':session', $session['session_id']);
                        $stm->bindParam(':exam', $row['qa_exam']);
                        $stm->bindParam(':question', $row['qa_id']);
                        $stm->execute();
                        $true = $stm->fetch(PDO::FETCH_ASSOC);
                        $precen = round((($true['c'] / $total['c']) * 100), 2);
                  ?>
                      
                          <div class="progress-wrapper" style="padding-top: 0.5rem;">
                            <div class="progress-info">
                              <div style="white-space: nowrap;width: 600px;overflow: hidden;text-overflow: ellipsis;"><a style="text-decoration: none" href="?session_id=<?php echo $session['session_id']; ?>&n=<?php echo $row['qa_id']; ?>"><?php echo $row['qa_order']; ?>. <?php echo strip_tags($row['qa_question']); ?></a></div>
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
            </div>
        </div>