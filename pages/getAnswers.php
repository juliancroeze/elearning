<?php
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $question_id = $_GET['id'];
    $givenAnswer = $_GET['givenAnswer'];

    $stmt_questions = $pdo->prepare("SELECT question_answer FROM questions WHERE question_id = :question_id");
    $stmt_questions->bindParam(':question_id', $question_id);
    $stmt_questions->execute();
    $questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

    if($questions[0]["question_answer"] == $givenAnswer){
        echo json_encode("Correct");
    } else {
        echo json_encode("Incorrect");
    }

}
?>
