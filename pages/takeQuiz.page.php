<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
include_once '../autoloader.php';

$quizId = $_GET['idQuiz'] ?? null;
if(isset($_GET['user']) && $_GET['user'] == 'anonymous'){
    unset($_SESSION['anonymous']);
}

$questionsAnswersCont = new QuestionsAnswers();
$anonymous = new anonymous();
if(isset($_SESSION['anonymous']) && $quizId != null){
    $userId = $anonymous->getUserIdd($_SESSION['anonymous'])['Id'];
    $currentQuestionDone = $questionsAnswersCont->hasUserCompletedQuizz($userId, $quizId);
    $totalQuestionCount = $questionsAnswersCont->getQuizQuestionCountt($quizId);

    if ($currentQuestionDone >= $totalQuestionCount) {
        header("location: userquizoverview.php");
        exit();
    }
}

if (!$quizId) {
    echo "Invalid quiz.";
    exit;
}

$pos = $_GET['pos'] ?? null;
$quiz = $questionsAnswersCont->getQuizFromId($quizId);

if (isset($_GET['user'])){
    $type = 'anonymous';
}
else{
    $question = $questionsAnswersCont->getQuestionAnswersByPositionn($pos, $quizId);
    $type = $question[0]['IdAnswerType'];
}

$mode = 'take';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $quiz['Name'] ?></title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="../stylesTake.css?v=<?= time(); ?>">

</head>
<body>
    <div class="background"></div>
    
    <div class='page-wrapper'>
        <div class="create-question">
            <?php 

                if ($type === 'anonymous') {
                    include '../templates/fillName.temp.php';
                }
                else if ($type === 'pictureChoice' || $type == 4) {
                    include '../templates/pictureChoice.temp.php';
                } elseif ($type === 'orderAnswers' || $type == 5) {
                    include '../templates/orderAnswers.temp.php';
                } elseif ($type === 'sentenceCompletion' || $type == 6) {
                    include '../templates/sentenceCompletion.temp.php';
                } elseif ($type === 'pictureAssembly' || $type == 7) {
                    include '../templates/pictureAssembly.temp.php'; 
                } elseif ($type === 'categoriesFill' || $type == 8) {
                    include '../templates/categoriesFill.temp.php';
                } elseif ($type === 'matchAnswers' || $type == 9) {
                    include '../templates/matchAnswers.temp.php';
                }
            ?>   
        </div>

        <div class="prev-next-buttons">
            <?php echo "<input form='form' type='submit' name='next' value='Next' class='next-btn'>"; ?>
        </div>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>
