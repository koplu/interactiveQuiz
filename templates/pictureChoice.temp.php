<?php
$questionText = null;
$questionId = null;
$images = [];
if ($question != null){
  $questionText = $question[0]['qText'];
  $questionId = $question[0]['Id'];
  foreach ($question as $row){
    if (!empty($row['imagePath'])) {
      $images[] = [
        'imagePath' => $row['imagePath'],
        'Correct' => $row['Correct']
      ];
    }
  }
}
$preselectedCorrectIndex = null;
foreach ($images as $i => $img) {
  if ($img['Correct'] == 1) {
    $preselectedCorrectIndex = $i;
    break;
  }
}
$preselectedCorrectIndex = htmlspecialchars($preselectedCorrectIndex ?? '');
if ($mode == 'take') {
  $preselectedCorrectIndex = '';
}
?>

<form id="form" 
  action="<?= $mode == 'take'
  ? "../includes/pictureChoice.inc.php?mode=take&idQuiz=$quizId&pos=$pos"
  : '../includes/pictureChoice.inc.php' ?>" 
  method="POST" enctype="multipart/form-data">
  <div id="create-question">
    <label for="question_text">Question:</label>
    <textarea id="question_text" name="question_text" rows="3" required <?= $mode === 'take' ? 'disabled' : '' ?>><?= $questionText ?></textarea>

    <?php if ($mode !== 'take'): ?>
      <label for="images">Upload from 2 to 4 images (jpeg, png, gif):</label>
      <input type="file" id="images" name="images[]" accept="image/jpeg, image/png, image/gif" multiple>
    <?php endif; ?>

    <div id="preview" class="preview-container">
      <?php if (!empty($images)): ?>
        <?php foreach ($images as $index => $img): ?>
          <img 
            src="<?= htmlspecialchars($img['imagePath']) ?>" 
            alt="Answer image <?= $index + 1 ?>"
            data-index="<?= $index ?>"
            class="<?= ($mode !== 'take' && $img['Correct']) ? 'selected' : '' ?>"
          >
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <input type="hidden" name="correct_answer" id="correct_answer" value="<?= $preselectedCorrectIndex ?>">
    <input type="hidden" name="questionId" value="<?= $questionId ?>">
    <input type="hidden" name="type" value="<?= $type ?>">
    <input type="hidden" id="existing_count" value="<?= count($images) ?>">
  </div>
</form>
<?php if ($mode !== 'take'): ?>
<script>
  const input = document.getElementById('images');
  const preview = document.getElementById('preview');

  input.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    preview.innerHTML = '';

    files.forEach((file, index) => {
      const img = document.createElement('img');
      img.dataset.index = index;
      img.alt = file.name;

      img.addEventListener('click', () => {
        document.querySelectorAll('.preview-container img').forEach(i => i.classList.remove('selected'));

        img.classList.add('selected');

        correctField.value = index;
      });

      preview.appendChild(img);

      const url = URL.createObjectURL(file);
      img.src = url;

      img.onload = () => URL.revokeObjectURL(url);
    });
  });


  document.getElementById("form").addEventListener("submit", function (e) {
    const pictures = document.getElementById("images");
    const existingCount = parseInt(document.getElementById("existing_count").value, 10);
    const uploaded = pictures.files.length;

    if (uploaded > 0) {

      if (uploaded < 2 || uploaded > 4) {
        e.preventDefault();
        alert("Please upload between 2 and 44 images.");
        return false;
      }

      return true;
    }

    if (existingCount < 2 || existingCount > 4) {
      e.preventDefault();
      alert("Please upload between 2 and 4 images.");
      return false;
    }

    return true;
  });
</script>
<?php endif; ?>

<script>
  document.querySelectorAll('.preview-container img').forEach(img => {
    img.addEventListener('click', () => {
      document.querySelectorAll('.preview-container img').forEach(i => i.classList.remove('selected'));
      img.classList.add('selected');
      correctField.value = img.dataset.index;
    });
  });
</script>

<script>
  const correctField = document.getElementById('correct_answer');

  document.getElementById("form").addEventListener("submit", function (e) {
    const correctField = document.getElementById("correct_answer");
    if (correctField.value === "" || correctField.value === null) {
      e.preventDefault();
      alert("Please select an image before continuing.");
      return false;
    }
  });
</script>