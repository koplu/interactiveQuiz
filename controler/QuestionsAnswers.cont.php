<?php 
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
class QuestionsAnswers extends Questions {

    # inserts

    function insert($question, $correct, $files){
        if (empty($question) || $correct === '' || $correct === null || empty($files)) {
            $_SESSION['error'] = 'Some form fields are empty.';
            return;
        }

        $idQuestion = $this->createQuestion($question, 4);

        $imagePaths = $this->handleImageUploads($files);

        foreach ($imagePaths as $index => $path) {
            $isCorrect = ((int)$correct === $index) ? 1 : 0;
            $this->createImageOptions($isCorrect, $idQuestion, $path);
        }
    }

    private function handleImageUploads($files) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 6 * 1024 * 1024;
        $fileCount = count($files['name']);
        if ($fileCount > 4) {
            $_SESSION['error'] = 'Exceeded maximum number of pictures.';
            return;
        }

        $savedPaths = [];

        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Error uploading file: " . $name;
                return;
            }

            if (!in_array($files['type'][$key], $allowedTypes)) {
                $_SESSION['error'] = "Invalid file type for " . $name;
                return;
            }

            if ($files['size'][$key] > $maxSize) {
                $_SESSION['error'] = $name . "exceeds the max file size of 6MB.";
                return;
            }

            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $newName = uniqid('img_', true) . '.' . $ext;
            $targetPath = $uploadDir . $newName;

            if (!move_uploaded_file($files['tmp_name'][$key], $targetPath)) {
                $_SESSION['error'] = "Failed to save uploaded image: " . $name;
                return;
            }
            $showDir = '/uploads/';
            $dbPath = $showDir . $newName;
            $savedPaths[] = $dbPath;
        }

        return $savedPaths;
    }

    function insertUserAnswerr($imagePath, $orderAnswer, $correct, $IdUser, $IdQuiz, $IdQuestion){
        $this->insertUserAnswer($imagePath, $orderAnswer, $correct, $IdUser, $IdQuiz, $IdQuestion);
    }

    # updates

    public function updatePictureChoice($questionId, $question, $correct, $files){
        
        $oldImages = $this->getPictureChoiceImages($questionId);

        $hasNewImages = !empty($files['name'][0]);
        if (!$hasNewImages){
            if (empty($question) || $correct === '' || $correct === null || empty($oldImages)) {
                $_SESSION['error'] = 'Some form fields are empty.';
                return;
            }

            $newCorrectImagePath = $oldImages[$correct]['imagePath'];
            $this->updateCorrectImage($questionId, $newCorrectImagePath);
            $this->updateQuestion($questionId, $question);
            return;
        }
        foreach ($oldImages as $img) {
            if (file_exists($img['imagePath'])) {
                unlink($img['imagePath']);
            }
        }
        $this->deletePictureChoice($questionId);
        $this->updateQuestion($questionId, $question);
        $imagePaths = $this->handleImageUploads($files);
        foreach ($imagePaths as $index => $path) {
            $isCorrect = ((int)$correct === $index) ? 1 : 0;
            $this->createImageOptions($isCorrect, $questionId, $path);
        }
    }

    # gets

    public function getAllQuestions(){
        return $this->getQuestions();
    }

    public function getQuizbyId(){
        return $this->getQuiz();
    }

    public function getQuizFromId($id){
        return $this->getQuizByUrlId($id);
    }

    public function getQuestionById($id){
        return $this->getQuestion($id);
    }

    public function getQuestionByPositionn($pos){
        return $this->getQuestionByPosition($pos);
    }

    public function getQuestionPositionById($id){
        return $this->getQuestionPosition($id);
    }

    public function getQuestionMinMaxPositionByQuizId(){
        return $this->getQuestionMinMaxPosition();
    }

    public function getQuestionTypeById($id){
        return $this->getQuestionType($id);
    }

    public function getQuestionIdByPositionn($pos){
        return $this->getQuestionIdByPosition($pos);
    }

    public function getQuestionsForTestById($id){
        return $this->getQuestionsForTest($id);
    }

    public function getQuestionAnswersByPositionn($pos, $idquiz){
        return $this->getQuestionAnswersByPosition($pos, $idquiz);
    }

    public function getUserAnswerr($IdUser, $IdQuiz, $IdQuestion){
        return $this->getUserAnswer($IdUser, $IdQuiz, $IdQuestion);
    }

    public function getAllUserAnswerss($IdUser, $IdQuiz){
        return $this->getAllUserAnswers($IdUser, $IdQuiz);
    }

    public function getAllQuizResultss($idQuiz){
        return $this->getAllQuizResults($idQuiz);
    }

    public function getQuizQuestionCountt($idQuiz){
        return $this->getQuizQuestionCount($idQuiz);
    }

    public function hasUserCompletedQuizz($idUser ,$idQuiz){
        return $this->hasUserCompletedQuiz($idUser, $idQuiz);
    }

    # deletes
    public function deleteQuestionById($id){
        $this->deleteQuestion($id);
    }

    public function deleteQuizProgresss($quizId){
        $this->deleteQuizProgress($quizId);
    }
}