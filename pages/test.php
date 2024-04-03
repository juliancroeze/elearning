<?php 
require_once '../db_connect.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$questionnaire_id = $_GET['id'];
$user = $_SESSION['user'];

$stmt_questionnaire = $pdo->prepare("SELECT * FROM questionnaires WHERE questionnaire_id = :questionnaire_id");
$stmt_questionnaire->bindParam(':questionnaire_id', $questionnaire_id);
$stmt_questionnaire->execute();
$questionnaire = $stmt_questionnaire->fetch(PDO::FETCH_ASSOC);

if (!$questionnaire) {
    header("Location: dashboard.php");
    exit();
}

$stmt_questions = $pdo->prepare("SELECT question_text, question_answer FROM questions WHERE questionnaire_id = :questionnaire_id");
$stmt_questions->bindParam(':questionnaire_id', $questionnaire_id);
$stmt_questions->execute();
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC); 

$questionsJSON = json_encode($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Practice List</title>
</head>
<body>
    <div class="mainContainer">
        <div class="leftContainer">
            <div class="subContainer">
                <h1>Practice List: <?php echo htmlspecialchars($questionnaire["name"]); ?></h1>
                <div id="questionContainer"></div>
                <div id="buttonContainer"> 
                    <button id="nextButton">Next</button>
                    <button id="backButton" style="display: none;">Back</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var questionnaireId = <?php echo json_encode($questionnaire_id); ?>;
    </script>

    <script src="js/script.js"></script>
</body>
</html>
