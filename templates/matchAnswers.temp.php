<?php
$questionText = $question ? $question[0]['qText'] : '';
$questionId = $question ? $question[0]['Id'] : null;
$json = $question ? json_decode($question[0]['Json'], true) : [];
?>

<?php if ($mode !== 'take'): ?>
    <form id="form" action="../includes/matchAnswers.inc.php" method="POST">
        <div id="create-question">

            <label for="question_text">Question:</label>
            <textarea id="question_text" name="question_text" rows="3"><?= htmlspecialchars($questionText) ?></textarea>

            <div id="matching-creation">
                <div>Fill between 2–6 matching pairs.</div>

                <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="matching-row">
                    <label>Left <?= $i+1 ?>:</label>
                    <input 
                        type="text"
                        name="left[]"
                        value="<?= isset($json[$i]['left']) ? htmlspecialchars($json[$i]['left']) : '' ?>"
                    >

                    <label>Right <?= $i+1 ?>:</label>
                    <input 
                        type="text"
                        name="right[]"
                        value="<?= isset($json[$i]['right']) ? htmlspecialchars($json[$i]['right']) : '' ?>"
                    >
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <input type="hidden" name="questionId" value="<?= $questionId ?>">
        <input type="hidden" name="type" value="<?= $type ?>">

    </form>
    <script src="../scripts/validateMatchAnswer.js?v=<?= time(); ?>" defer></script>
<?php endif; ?>

<?php if ($mode == 'take'): ?>
    <script src="https://unpkg.com/konva@10/konva.min.js" defer></script>

    <div id="rotate-overlay">
        <div class="rotate-message">
            <p>Please rotate your phone to landscape.</p>
        </div>
    </div>

    <div id="flip">
        <form id="form" action="../includes/matchAnswers.inc.php?mode=take&idQuiz=<?=$quizId?>&pos=<?=$pos?>" method="POST">
        <div id="create-question">
            <label for="question_text">Question:</label>
            <textarea id="question_text" disabled><?= htmlspecialchars($questionText) ?></textarea>
            <div id="matchAnswer"></div>
        </div>

        <input type="hidden" name="questionId" value="<?= $questionId ?>">
        <input type="hidden" name="type" value="<?= $type ?>">
        <input type="hidden" name="matches" id="matches">
        </form>
    </div>

    <script>
        let pairs = <?= json_encode($json) ?>;
    </script>

    <script src="../scripts/connectRectangle.js?v=<?= time(); ?>" defer></script>

    <script defer>
        let lastOrientation = (window.innerWidth > window.innerHeight) ? "landscape" : "portrait"; 

        function handleResize() {
            let current = (window.innerWidth > window.innerHeight) ? "landscape" : "portrait";

            document.getElementById('rotate-overlay').style.display = (current === "portrait" ? "flex" : "none");

            if (current !== lastOrientation) {
                lastOrientation = current;
                rebuildStage();
            }
        }

        window.addEventListener('resize', handleResize);
        window.addEventListener('orientationchange', handleResize);

        document.addEventListener("DOMContentLoaded", () => {
            rebuildStage();
        });
    </script>
<?php endif; ?>
