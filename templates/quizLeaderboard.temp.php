<div class="quizOverviewWrapper">
    <div class="quizOverview">
        <div>Results for: <?= htmlspecialchars($quizName) ?></div>
        <?php if(isset($correct) && isset($total)): ?>
            <div class="scoreBox">
                Score: <?= $correct ?>/<?= $total ?>
            </div>
            <div>Your rank: <?= $userRank ?> / <?= count($quizResults) ?></div>
        <?php endif; ?>

        <?php foreach ($quizResults as $r): ?>
            <div class="questionBox <?= ($r['isCorrect'] ?? false) ? 'correct' : 'wrong' ?>">
                <div class="questionText"><?= htmlspecialchars($r['questionText'] ?? $r['Username']) ?></div>
                <?php if(isset($r['score'])): ?>
                    <div class="result">
                        Score: <?= $r['score'] ?>
                    </div>
                <?php elseif(isset($r['isCorrect'])): ?>
                    <div class="result">
                        <?= $r['isCorrect'] ? "Correct" : "Wrong" ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>