<?php if ($mode !== 'take'): ?>
<script src="../scripts/validateOrderAnswers.js?v=<?= time(); ?>" defer></script>
<?php endif; ?>

<?php
$questionText = null;
$json = null;
$questionId = null;
if ($question !== null){
    $question = $question[0];
    $json = json_decode($question['Json'], true);
    $questionText = $question['qText'];
    $questionId = $question['Id'];
}
?>

<?php if ($mode !== 'take'): ?>

    <form id="form" action="../includes/orderAnswer.inc.php" method="POST">
    <div id="create-question">
        <label for="question_text">Question:</label>
        <textarea id="question_text" name="question_text" rows="3"><?= $questionText ?></textarea>
        <div id="order-answer-creation">
            <div>Fill up to 4 answers with their corresponding order.</div>
            <div>
                <label for="answer1">Answer 1:</label>
                <input id="answer1" name="answer[]" type="text" value="<?= isset($json[0]['text']) ? htmlspecialchars($json[0]['text']) : null ?>">
                <label for="order1">Order:</label>
                <input id="order1" name="order[]" type="number" min="1" max="4" value="<?= (!empty($json[0]['text'])) ? htmlspecialchars($json[0]['order']) : '' ?>">
            </div>
            <div>
                <label for="answer2">Answer 2:</label>
                <input id="answer2" name="answer[]" type="text" value="<?= isset($json[1]['text']) ? htmlspecialchars($json[1]['text']) : null ?>">
                <label for="order2">Order:</label>
                <input id="order2" name="order[]" type="number" min="1" max="4" value="<?= (!empty($json[1]['text'])) ? htmlspecialchars($json[1]['order']) : '' ?>">
            </div>
            <div>
                <label for="answer3">Answer 3:</label>
                <input id="answer3" name="answer[]" type="text" value="<?= isset($json[2]['text']) ? htmlspecialchars($json[2]['text']) : null ?>">
                <label for="order3">Order:</label>
                <input id="order3" name="order[]" type="number" min="1" max="4" value="<?= (!empty($json[2]['text'])) ? htmlspecialchars($json[2]['order']) : '' ?>">
            </div>
            <div>
                <label for="answer4">Answer 4:</label>
                <input id="answer4" name="answer[]" type="text" value="<?= isset($json[3]['text']) ? htmlspecialchars($json[3]['text']) : null ?>">
                <label for="order4">Order:</label>
                <input id="order4" name="order[]" type="number" min="1" max="4" value="<?= (!empty($json[3]['text'])) ? htmlspecialchars($json[3]['order']) : '' ?>">
            </div>
        </div>
    </div>
    <input type="hidden" name="questionId" value="<?= $questionId ?>">
    <input type="hidden" name="type" value="<?= $type ?>">
</form>
<?php endif; ?>

<?php if ($mode == 'take'): ?>
<script src="https://unpkg.com/konva@10/konva.min.js" defer></script>

    <div id="rotate-overlay">
        <div class="rotate-message">
            <p>Please rotate your phone to portrait.</p>
        </div>
    </div>

    <form id="form" action="../includes/orderAnswer.inc.php?mode=take&idQuiz=<?=$quizId?>&pos=<?=$pos?>" method="POST">
        <div id="create-question">
            <label for="question_text">Question:</label>
            <textarea id="question_text" name="question_text" rows="3" disabled><?= $questionText ?></textarea>
            <div id='container'></div>
        </div>
        <input type="hidden" name="questionId" value="<?= $questionId ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="answerOrder" id="answerOrder">
    </form>
<script>
    let answers = <?= json_encode($json) ?>;
</script>
<script src="../scripts/rectangleOrder.js?v=<?= time(); ?>" defer></script>
<?php endif; ?>