<?php
    session_start();
    if(!isset($_SESSION['auth']) and !isset($_SESSION['username']) and $_SESSION['role'] > 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    require_once '../control/init.php';

    if($_SESSION['role'] != 2){
        echo json_encode(['state'=>false,'msg'=>'No permission']); exit;
    }

    $i = 1;
    foreach ($_POST['atid'] as $id) {
        $stm = $_DB->prepare("UPDATE articles SET a_order = :i WHERE atid = :id");
        $stm->bindParam(':i', $i, PDO::PARAM_INT);
        $stm->bindParam(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        $i++;
    }

    $stm = $_DB->prepare('SELECT articles.atid,articles.title,articles.reads,articles.poston,articles.public,articles.a_order,users.full_name FROM articles JOIN users ON articles.uid = users.uid WHERE articles.subject = :sub_id ORDER BY articles.a_order ASC');
    $stm->bindParam(":sub_id", $_POST['subject']);
    $stm->execute();
    while ($rows = $stm->fetch(PDO::FETCH_ASSOC)) {

?>
        <tr atid="<?php echo $rows['atid']; ?>" id="article-<?php echo $rows['atid']; ?>">
            <td>
                <i class="fa fa-arrows-alt"></i>
            </td>
            <td>
                <?php echo $rows['a_order']; ?>
            </td>
            <td>
                <?php echo $rows['title']; ?>
            </td>
            <td>
                <?php echo $rows['full_name']; ?>
            </td>
            <td class="text-right">
                <a href="?edit&article_id=<?php echo $rows['atid']; ?>" class="btn btn-info btn-sm">Edit</a> 
                <button id="delete-btn-<?php echo $rows['atid']; ?>" onclick="article_delete(<?php echo $rows['atid']; ?>)" class="btn btn-danger btn-sm">Delete</button>
            </td>
        </tr>
<?php } ?>