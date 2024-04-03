<?php
require_once '../db_connect.php';

    $questionnaire_id = $_GET['id']; 
    var_dump($questionnaire_id);
    $stmt_delete = $pdo->prepare("DELETE FROM questionnaires WHERE questionnaire_id = :questionnaire_id");
    $stmt_delete->bindParam(':questionnaire_id', $questionnaire_id);
    $stmt_delete->execute();


?>
