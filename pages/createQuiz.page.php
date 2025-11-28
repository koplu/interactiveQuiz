<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include '../autoloader.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="../styles.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background"></div>
    <?php 
        include 'header.php';

        if(isset($_SESSION['error'])){
            echo "<div class='error'>Error:" . $_SESSION['error'] . "</div>";
        }

        $mode = 'create';
    ?>

    <div class='page-wrapper'>
        <div class="create-question">
            <?php 
                $questionId = $_GET['id'] ?? null;
                $type = $_GET['type'] ?? 'pick';
                $question = null;

                if ($type != 'pick' && $questionId != null){
                    $questionsAnswersCont = new QuestionsAnswers();
                    $question = $questionsAnswersCont->getQuestionById($questionId);
                }

                if ($type === 'quizName') {
                    include '../templates/createQuizName.temp.php';
                } elseif ($type === 'pick') {
                    include '../templates/pickQuizType.temp.php';
                } elseif ($type === 'pictureChoice' || $type == 4) {
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
            <?php 
                $prevClass = ($type === 'quizName') ? 'disabled-btn' : '';
                echo "<input form='form' type='submit' name='prev' value='Previous' class='prev-btn $prevClass'>";

                $deleteClass = ($type !== 'quizName' && $type !== 'pick') ? '' : 'disabled-btn';
                echo "<a href='../includes/deleteQuestion.inc.php?id=$questionId' class='$deleteClass'>Delete</a>";

                $previewClass = ($type === 'pick') ? 'link' : 'input';
                if ($previewClass == 'link'){
                    echo "<a href='quizOverview.page.php' class='next-btn'>Overview</a>";
                }

                $nextClass = ($type !== 'pick') ? '' : 'disabled-btn';
                echo "<input form='form' type='submit' name='next' value='Next' class='next-btn $nextClass'>";
            ?>
        </div>
    </div>
    <footer>© 2025 Lukas Kopecky. All rights reserved.</footer>
</body>
</html>