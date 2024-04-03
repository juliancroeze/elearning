<?php
require_once '../db_connect.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This part is for updating the existing list
    $questionnaireId = $_GET['id']; // Get the ID from the URL parameter
    $name = $_POST['name'];
    $description = $_POST['description'];
    $soort = $_POST['soort'];

    $stmt = $pdo->prepare("UPDATE questionnaires SET name = :name, description = :description, type = :soort WHERE questionnaire_id = :questionnaire_id");

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':soort', $soort);
    $stmt->bindParam(':questionnaire_id', $questionnaireId);

    if ($stmt->execute()) {
        // Delete existing questions to update with new ones
        $stmt = $pdo->prepare("DELETE FROM questions WHERE questionnaire_id = :questionnaire_id");
        $stmt->bindParam(':questionnaire_id', $questionnaireId);
        $stmt->execute();

        // Insert new questions
        if(isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $question) {
                if (!empty($question['nederlands']) && !empty($question['engels'])) {
                    $nederlands = $question['nederlands'];
                    $engels = $question['engels'];
                    $stmt = $pdo->prepare("INSERT INTO questions (questionnaire_id, question_text, question_answer) VALUES (:questionnaire_id, :nederlands, :engels)");
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
} else {
    // Retrieve existing list data to pre-populate the form
    $questionnaireId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM questionnaires WHERE questionnaire_id = :questionnaire_id");
    $stmt->bindParam(':questionnaire_id', $questionnaireId);
    $stmt->execute();
    $questionnaire = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit List</title>
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
            <h1>Edit List</h1>

            <form action="#" method="POST">

                <div class="data">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Naam" value="<?php echo $questionnaire['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="description" name="description" placeholder="Beschrijving" value="<?php echo $questionnaire['description']; ?>" required>
                    </div>
                </div>

                <div class="questions">
                    <?php
                    // Retrieve existing questions for this list and pre-populate the form
                    $stmt = $pdo->prepare("SELECT * FROM questions WHERE questionnaire_id = :questionnaire_id");
                    $stmt->bindParam(':questionnaire_id', $questionnaireId);
                    $stmt->execute();
                    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($questions as $index => $question) {
                        echo '<div class="form-group form-group1">';
                        echo '<input class="left" type="text" name="questions[' . $index . '][nederlands]" placeholder="Nederlands" value="' . $question['question_text'] . '">';
                        echo '<input class="right" type="text" name="questions[' . $index . '][engels]" placeholder="Engels" value="' . $question['question_answer'] . '">';
                        echo '<button type="button" class="removeQuestion">Delete</button>';
                        echo '</div>';
                    }
                    ?>
                    <a class="addQuestion">Voeg een vraag toe</a>
                </div>
                <button type="submit">Bijwerken</button>

            </form>
        </div>
    </div>
    <script src="js/question.js"></script>

</body>

</html>
