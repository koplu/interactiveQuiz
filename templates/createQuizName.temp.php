<?php include '../includes/createQuizName.inc.php' ?>
<form id="form" action="../includes/createQuizName.inc.php" method="post">
    <div id="create-question">
        <label for="question">Enter quiz name:</label>
        <input id="question" type="text" name="question" placeholder="Type here..." value='<?= $quizName ?>' required>
        <input type="hidden" name="quizId" value="<?= $quizId ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
    </div>
</form>
