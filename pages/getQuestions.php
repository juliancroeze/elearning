<?php
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $questionnaire_id = $_GET['id'];

    $stmt_questions = $pdo->prepare("SELECT question_text, question_id FROM questions WHERE questionnaire_id = :questionnaire_id");
    $stmt_questions->bindParam(':questionnaire_id', $questionnaire_id);
    $stmt_questions->execute();
    $questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($questions);
}
?>
