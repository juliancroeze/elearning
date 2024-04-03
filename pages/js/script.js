document.addEventListener("DOMContentLoaded", function() {
    var currentQuestionIndex = 0;
    var answerChecked = false;
    var nextButton = document.getElementById("nextButton");
    var totalQuestions = 0;
    var correctAnswers = 0;
    var backButton = document.getElementById("backButton");
    var questionsData = null;
    var answerData = null;

    function displayQuestion(question) {
        var questionContainer = document.getElementById("questionContainer");
        if (question) {
            questionContainer.innerHTML = `
                <div class="lijstCard">
                    <h2>Question ${currentQuestionIndex + 1}</h2>
                    <p>${question['question_text']}</p>
                    <input type="text" id="answerInput" placeholder="Enter your answer">
                    <button id="checkAnswerButton">Check Answer</button>
                    <p id="answerResult"></p>
                </div>
            `;
            nextButton.style.display = "block";
            nextButton.textContent = "Next";
            backButton.style.display = "none"; 
        } else {
            var scorePercentage = (correctAnswers / totalQuestions * 100).toFixed(2);
            questionContainer.innerHTML = `<h2>Correct ${correctAnswers} out of ${totalQuestions}. Score: ${scorePercentage}%</h2>`;
            nextButton.style.display = "none";
            backButton.style.display = "block";
        }

        var checkAnswerButton = document.getElementById("checkAnswerButton");
        checkAnswerButton.addEventListener("click", async function () {
            var answerInput = document.getElementById("answerInput");
            try {
                answerData = await loadAnswer(questionsData[currentQuestionIndex]['question_id'], answerInput.value.trim());
                console.log(answerData);
                var answerResult = document.getElementById("answerResult");
                if (!answerChecked) {
                    var userAnswer = answerInput.value.trim();
                    if (answerData == "Correct") {
                        answerResult.textContent = "Correct!";
                        answerResult.style.color = "green";
                        correctAnswers++;
                    } else {
                        answerResult.textContent = "Wrong!";
                        answerResult.style.color = "red";
                    }
                    answerChecked = true;
                    answerInput.disabled = true;
                    checkAnswerButton.disabled = true;
                    nextButton.disabled = false; 
                }
            } catch (error) {
                console.error('Error checking answer:', error);
            }
        });
        
    }

    // Function to load questions
    function loadQuestions() {
        fetch("getQuestions.php?id=" + questionnaireId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                totalQuestions = data.length;
                questionsData = data;
                displayQuestion(data[currentQuestionIndex]);
            })
            .catch(error => {
                console.error('Error fetching questions:', error);
            });
    }

    function loadAnswer(questionId, givenAnswer) {
        return fetch("getAnswers.php?id=" + questionId + "&givenAnswer=" + givenAnswer)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                return data; // Return the fetched data
            })
            .catch(error => {
                console.error('Error fetching answers:', error);
            });
    }
    

    function redirectToDashboard() {
        window.location.href = "dashboard.php";
    }

    loadQuestions();

    nextButton.addEventListener("click", function() {
        if (answerChecked) {
            if (currentQuestionIndex + 1 < totalQuestions) {
                currentQuestionIndex++;
                answerChecked = false; 
                displayQuestion(questionsData[currentQuestionIndex]);
            } else {
                displayQuestion(null);
            }
        }
    });



    backButton.addEventListener("click", redirectToDashboard);


});
