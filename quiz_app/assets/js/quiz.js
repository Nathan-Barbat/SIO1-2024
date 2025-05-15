// Fichier: assets/js/quiz.js

document.addEventListener('DOMContentLoaded', function() {
    // Récupération des éléments du DOM
    const startContainer = document.getElementById('start-container');
    const quizContainer = document.getElementById('quiz-container');
    const quizDescription = document.getElementById('quiz-description');
    const resultsContainer = document.getElementById('results-container');
    const btnStart = document.getElementById('btn-start');
    const btnSkip = document.getElementById('btn-skip');
    const btnNext = document.getElementById('btn-next');
    const btnReplay = document.getElementById('btn-replay');
    const progressBar = document.getElementById('progress-bar');
    const currentQuestionSpan = document.getElementById('current-question');
    const totalQuestionsSpan = document.getElementById('total-questions');
    const questionTitle = document.getElementById('question-title');
    const questionText = document.getElementById('question-text');
    const difficultyBadge = document.getElementById('difficulty-badge');
    const choicesContainer = document.getElementById('choices-container');
    const feedbackContainer = document.getElementById('feedback-container');
    const timer = document.getElementById('timer');
    const scoreCircle = document.getElementById('score-circle');
    const finalScore = document.getElementById('final-score');
    const maxScore = document.getElementById('max-score');
    const totalTime = document.getElementById('total-time');
    const feedbackMessage = document.getElementById('feedback-message');
    const pointsInput = document.getElementById('points');
    const tempsInput = document.getElementById('temps_total');
    
    // Récupération des données du quiz depuis le template JSON
    const quizDataElement = document.getElementById('quiz-data');
    const quizData = JSON.parse(quizDataElement.textContent);
    
    // Variables pour le quiz
    let currentQuestionIndex = 0;
    let score = 0;
    let timerInterval;
    let timeLeft = 10;
    let totalTimeSpent = 0;
    let questionStartTime;
    let hasAnswered = false;
    
    // Initialisation
    totalQuestionsSpan.textContent = quizData.questions.length;
    maxScore.textContent = quizData.questions.length;
    
    // Démarrer le quiz
    btnStart.addEventListener('click', startQuiz);
    
    // Commencer le quiz
    function startQuiz() {
        startContainer.style.display = 'none';
        quizDescription.style.display = 'none';
        quizContainer.style.display = 'block';
        loadQuestion(0);
    }
    
    // Charger une question
    function loadQuestion(index) {
        // Reset
        feedbackContainer.style.display = 'none';
        btnNext.disabled = true;
        btnSkip.disabled = false;
        hasAnswered = false;
        
        // Mise à jour de l'interface
        currentQuestionIndex = index;
        currentQuestionSpan.textContent = index + 1;
        
        // Calcul du pourcentage de progression
        const progressPercentage = ((index) / quizData.questions.length) * 100;
        progressBar.style.width = progressPercentage + '%';
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        
        // Chargement de la question
        const question = quizData.questions[index];
        questionTitle.textContent = `Question ${index + 1}`;
        questionText.textContent = question.text;
        
        // Affichage du badge de difficulté
        let difficultyText, difficultyClass;
        switch (question.difficulty) {
            case 1:
                difficultyText = 'Facile';
                difficultyClass = 'bg-success';
                break;
            case 2:
                difficultyText = 'Moyen';
                difficultyClass = 'bg-warning text-dark';
                break;
            case 3:
                difficultyText = 'Difficile';
                difficultyClass = 'bg-danger';
                break;
            default:
                difficultyText = 'Niveau inconnu';
                difficultyClass = 'bg-secondary';
        }
        difficultyBadge.innerHTML = `<span class="badge ${difficultyClass}">${difficultyText}</span>`;
        
        // Chargement des choix
        choicesContainer.innerHTML = '';
        question.choices.forEach(choice => {
            const choiceButton = document.createElement('button');
            choiceButton.className = 'list-group-item list-group-item-action';
            choiceButton.textContent = choice.text;
            choiceButton.dataset.choiceId = choice.id;
            choiceButton.addEventListener('click', () => selectChoice(choice.id));
            choicesContainer.appendChild(choiceButton);
        });
        
        // Démarrage du timer
        timeLeft = 10;
        timer.textContent = timeLeft;
        timer.className = 'badge bg-primary fs-6';
        questionStartTime = new Date();
        startTimer();
    }
    
    // Démarrer le timer
    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            timeLeft--;
            timer.textContent = timeLeft;
            
            // Changer la couleur du timer en fonction du temps restant
            if (timeLeft <= 3) {
                timer.className = 'badge bg-danger fs-6';
            } else if (timeLeft <= 6) {
                timer.className = 'badge bg-warning text-dark fs-6';
            }
            
            // Si le temps est écoulé
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timeOut();
            }
        }, 1000);
    }
    
    // Sélectionner une réponse
    function selectChoice(choiceId) {
        if (hasAnswered) return;
        
        hasAnswered = true;
        clearInterval(timerInterval);
        
        // Calculer le temps passé sur cette question
        const timeSpent = Math.round((new Date() - questionStartTime) / 1000);
        totalTimeSpent += timeSpent;
        
        // Vérifier si la réponse est correcte
        const question = quizData.questions[currentQuestionIndex];
        const isCorrect = (choiceId == question.correctChoiceId);
        
        // Mettre à jour le score
        if (isCorrect) {
            score++;
        }
        
        // Mettre en évidence la réponse sélectionnée et la réponse correcte
        const buttons = choicesContainer.querySelectorAll('button');
        buttons.forEach(button => {
            const id = parseInt(button.dataset.choiceId);
            
            if (id == choiceId) {
                button.classList.add(isCorrect ? 'list-group-item-success' : 'list-group-item-danger');
            }
            
            if (id == question.correctChoiceId && id != choiceId) {
                button.classList.add('list-group-item-success');
            }
            
            button.disabled = true;
        });
        
        // Afficher le feedback
        feedbackContainer.textContent = isCorrect ? 
            '✅ Bonne réponse !' : 
            '❌ Mauvaise réponse. La réponse correcte est mise en évidence.';
        feedbackContainer.className = isCorrect ? 
            'alert alert-success mt-3' : 
            'alert alert-danger mt-3';
        feedbackContainer.style.display = 'block';
        
        // Activer le bouton suivant
        btnNext.disabled = false;
        btnSkip.disabled = true;
    }
    
    // Temps écoulé pour une question
    function timeOut() {
        if (hasAnswered) return;
        
        hasAnswered = true;
        totalTimeSpent += 10; // On compte 10 secondes
        
        // Mettre en évidence la réponse correcte
        const question = quizData.questions[currentQuestionIndex];
        const buttons = choicesContainer.querySelectorAll('button');
        buttons.forEach(button => {
            const id = parseInt(button.dataset.choiceId);
            
            if (id == question.correctChoiceId) {
                button.classList.add('list-group-item-success');
            }
            
            button.disabled = true;
        });
        
        // Afficher le feedback
        feedbackContainer.textContent = '⏱️ Temps écoulé ! La réponse correcte est mise en évidence.';
        feedbackContainer.className = 'alert alert-warning mt-3';
        feedbackContainer.style.display = 'block';
        
        // Activer le bouton suivant
        btnNext.disabled = false;
        btnSkip.disabled = true;
    }
    
    // Passer à la question suivante
    btnNext.addEventListener('click', nextQuestion);
    btnSkip.addEventListener('click', skipQuestion);
    
    function nextQuestion() {
        if (currentQuestionIndex < quizData.questions.length - 1) {
            loadQuestion(currentQuestionIndex + 1);
        } else {
            endQuiz();
        }
    }
    
    function skipQuestion() {
        clearInterval(timerInterval);
        
        // On considère que l'utilisateur a passé 5 secondes sur la question
        totalTimeSpent += 5;
        
        if (currentQuestionIndex < quizData.questions.length - 1) {
            loadQuestion(currentQuestionIndex + 1);
        } else {
            endQuiz();
        }
    }
    
    // Terminer le quiz
    function endQuiz() {
        quizContainer.style.display = 'none';
        resultsContainer.style.display = 'block';
        
        // Mise à jour des résultats
        finalScore.textContent = score;
        totalTime.textContent = totalTimeSpent;
        
        // Calcul du pourcentage
        const percentage = Math.round((score / quizData.questions.length) * 100);
        scoreCircle.textContent = percentage + '%';
        
        // Couleur du cercle en fonction du score
        let scoreColor;
        if (percentage >= 80) {
            scoreColor = '#28a745'; // vert
            feedbackMessage.className = 'alert alert-success text-center mb-4';
            feedbackMessage.textContent = 'Excellent ! Vous maîtrisez très bien ce sujet.';
        } else if (percentage >= 60) {
            scoreColor = '#17a2b8'; // bleu
            feedbackMessage.className = 'alert alert-info text-center mb-4';
            feedbackMessage.textContent = 'Bon travail ! Vous avez de bonnes connaissances sur ce sujet.';
        } else if (percentage >= 40) {
            scoreColor = '#ffc107'; // jaune
            feedbackMessage.className = 'alert alert-warning text-center mb-4';
            feedbackMessage.textContent = 'Pas mal, mais il y a encore des points à améliorer.';
        } else {
            scoreColor = '#dc3545'; // rouge
            feedbackMessage.className = 'alert alert-danger text-center mb-4';
            feedbackMessage.textContent = 'Vous avez besoin de revoir ce sujet en profondeur.';
        }
        
        scoreCircle.style.backgroundColor = scoreColor;
        
        // Mise à jour des champs cachés pour le formulaire
        pointsInput.value = score;
        tempsInput.value = totalTimeSpent;
    }
    
    // Rejouer le quiz
    btnReplay.addEventListener('click', function() {
        // Réinitialisation des variables
        currentQuestionIndex = 0;
        score = 0;
        totalTimeSpent = 0;
        
        // Masquer les résultats et afficher la première question
        resultsContainer.style.display = 'none';
        startQuiz();
    });
});