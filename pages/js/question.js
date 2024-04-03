var addQuestionButton = document.querySelector('.addQuestion');
var questionCounter = 2; // Counter for dynamically generated question fields

addQuestionButton.addEventListener('click', function() {
    console.log('The class .addQuestion is clicked!');
    
    // Create a new div for the form group
    var newFormGroup = document.createElement('div');
    newFormGroup.classList.add('form-group', 'form-group1');

    // Create input for Nederlands
    var inputNederlands = document.createElement('input');
    inputNederlands.classList.add('left');
    inputNederlands.type = 'text';
    inputNederlands.name = 'questions[' + questionCounter + '][nederlands]';
    inputNederlands.placeholder = 'Nederlands';

    // Create input for Engels
    var inputEngels = document.createElement('input');
    inputEngels.classList.add('right');
    inputEngels.type = 'text';
    inputEngels.name = 'questions[' + questionCounter + '][engels]';
    inputEngels.placeholder = 'Engels';

    // Create button to remove question
    var removeQuestionButton = document.createElement('button');
    removeQuestionButton.type = 'button';
    removeQuestionButton.classList.add('removeQuestion');
    removeQuestionButton.textContent = 'Delete';
    removeQuestionButton.addEventListener('click', function() {
        newFormGroup.remove();
    });

    // Append inputs to the new form group
    newFormGroup.appendChild(inputNederlands);
    newFormGroup.appendChild(inputEngels);
    newFormGroup.appendChild(removeQuestionButton);

    // Append the new form group to the questions container
    var questionsContainer = document.querySelector('.questions');
    questionsContainer.insertBefore(newFormGroup, addQuestionButton);
    
    questionCounter++; // Increment question counter for the next question
});

// Add event listeners for removing questions
var removeQuestionButtons = document.querySelectorAll('.removeQuestion');
removeQuestionButtons.forEach(function(button) {
    button.addEventListener('click', function () {
        console.log("test");
        var questionGroup = button.parentNode;
        questionGroup.remove();
    });
});
