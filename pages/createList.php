<?php
require_once '../db_connect.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $soort = $_POST['soort'];

    $stmt = $pdo->prepare("INSERT INTO questionnaires (user_id, name, description, type) VALUES (:userid, :name, :description, :soort)");

    $name = htmlspecialchars($name);
    $description = htmlspecialchars($description);

    $stmt->bindParam(':userid', $_SESSION['user']['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':soort', $soort);

    if ($stmt->execute()) {
        // Retrieve the ID of the inserted questionnaire
        $questionnaireId = $pdo->lastInsertId();

        // Check if questions array exists
        if(isset($_POST['questions'])) {
            // Loop through the submitted questions
            foreach ($_POST['questions'] as $question) {
                // Check if both Nederlands and Engels fields are not empty
                if (!empty($question['nederlands']) && !empty($question['engels'])) {
                    // Insert the question into the database
                    $nederlands = $question['nederlands'];
                    $engels = $question['engels'];
                    $stmt = $pdo->prepare("INSERT INTO questions (questionnaire_id, question_text, question_answer) VALUES (:questionnaire_id, :nederlands, :engels)");
                    
                    $nederlands = htmlspecialchars($nederlands);
                    $engels = htmlspecialchars($engels);
                    
                    $stmt->bindParam(':questionnaire_id', $questionnaireId);
                    $stmt->bindParam(':nederlands', $nederlands);
                    $stmt->bindParam(':engels', $engels);
                    $stmt->execute();
                }
            }
        }

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $pdo->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/createList.css">
</head>

<body>
    <nav class="navbar">
        <div class="container1">
            <div class="navbar-left">
                <a href="../index.php" class="logo"></a>
            </div>
            <div class="navbar-right">
                <a href="login.php" class="login-btn">Dashboard</a>
                <a href="register.php" class="register-btn">Uitloggen</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="subContainer">
            <h1>Maak een nieuwe lijst</h1>

            <form action="#" method="POST">

                <div class="data">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Naam" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="description" name="description" placeholder="Beschrijving" required>
                    </div>
                </div>

                <div class="questions">
                    <div class="form-group form-group1">
                        <input class="left" type="text" name="questions[0][nederlands]" placeholder="Nederlands">
                        <input class="right" type="text" name="questions[0][engels]" placeholder="Engels">
                        <button type="button" class="removeQuestion">Delete</button>
                    </div>

                    <div class="form-group form-group1">
                        <input class="left" type="text" name="questions[1][nederlands]" placeholder="Nederlands">
                        <input class="right" type="text" name="questions[1][engels]" placeholder="Engels">
                        <button type="button" class="removeQuestion">Delete</button>
                    </div>

                    <a class="addQuestion">Voeg een vraag toe</a>
                </div>
                <button type="submit">Aanmaken</button>

            </form>
        </div>
    </div>
    <script src="js/question.js"></script>

</body>

</html>
