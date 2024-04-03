<?php
require_once '../db_connect.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

$stmt = $pdo->prepare("SELECT * FROM questionnaires WHERE user_id = :id");
$stmt->bindParam(':id', $user['id']);
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt1 = $pdo->prepare("SELECT * FROM questionnaires");
$stmt1->execute();
$allQuestionnaires = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Dashboard</title>
    <script>
        function deleteQuestionnaire(questionnaireId) {
            return fetch("deleteList.php?id=" + questionnaireId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    window.location.reload();
                    return response.json();
                })
                .then(data => {
                    return data;
                })
                .catch(error => {
                    console.error('Error fetching answers:', error);
                });
        }       
    </script>
</head>

<body>
    <nav class="navbar">
        <div class="container1">
            <div class="navbar-left">
                <a href="../index.php" class="logo"></a>
            </div>
            <div class="navbar-right">
                <a href="../index.php" class="login-btn">Home</a>
                <a href="logout.php" class="register-btn">Uitloggen</a>
            </div>
        </div>
    </nav>

    <div class="mainContainer">
        <div class="leftContainer">
            <div class="subContainer">
                <h1>Mijn Lijsten</h1>
                <?php foreach ($questionnaires as $questionnaire): ?>
                    <div class="lijstCard">
                        <h2>
                            <?php echo $questionnaire["name"] ?>
                        </h2>
                        <p>
                            <?php echo $questionnaire["description"] ?>
                        </p>
                        <p>
                            <?php echo $questionnaire["type"] ?>
                        </p>
                        <div class="buttons">
                            <a href="#" onclick="deleteQuestionnaire(<?php echo $questionnaire['questionnaire_id'] ?>)"
                                class="editList">Delete</a>
                            <a href="editList.php?id=<?php echo $questionnaire['questionnaire_id'] ?>"
                                class="editList">Bewerken</a>
                            <a href="test.php?id=<?php echo $questionnaire['questionnaire_id'] ?>"
                                class="makeList">Oefenen</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <a href="createList.php" class="createList">Lijst Maken</a>
            </div>
            <div class="subContainer">
                <h1>Alle Lijsten</h1>
                <?php foreach ($allQuestionnaires as $questionnaire): ?>
                    <div class="lijstCard">
                        <h2>
                            <?php echo $questionnaire["name"] ?>
                        </h2>
                        <p>
                            <?php echo $questionnaire["description"] ?>
                        </p>
                        <p>
                            <?php echo $questionnaire["type"] ?>
                        </p>
                        <div class="buttons">
                            <a href="test.php?id=<?php echo $questionnaire['questionnaire_id'] ?>"
                                class="makeList">Oefenen</a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</body>

</html>