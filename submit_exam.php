
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

$examId = filter_input(INPUT_POST, "exam_id", FILTER_VALIDATE_INT);

if (
    !$examId ||
    !isset($_SESSION["active_exam"]) ||
    $_SESSION["active_exam"]["exam_id"] !== $examId
) {
    header("Location: dashboard.php");
    exit;
}

// Check the examination duration on the server.
$stmt = $conn->prepare("SELECT duration FROM exams WHERE id = ?");
$stmt->execute([$examId]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    header("Location: dashboard.php");
    exit;
}

$duration = (int)$exam["duration"] * 60;
$elapsed = time() - $_SESSION["active_exam"]["started_at"];

if ($elapsed > $duration) {
    // The student can still submit, but the time has expired.
}

// Get the correct answers from the database.
$stmt = $conn->prepare(
    "SELECT id, correct_answer FROM questions WHERE exam_id = ?"
);
$stmt->execute([$examId]);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuestions = count($questions);
$correctAnswers = 0;

$answers = $_POST["answers"] ?? [];

if (!is_array($answers)) {
    $answers = [];
}

// Calculate the student's score.
foreach ($questions as $question) {

    $questionId = $question["id"];

    $selectedAnswer = $answers[$questionId] ?? "";

    if (
        is_string($selectedAnswer) &&
        strtoupper($selectedAnswer) === $question["correct_answer"]
    ) {
        $correctAnswers++;
    }
}

// Save the result.
$stmt = $conn->prepare(
    "INSERT INTO results (user_id, exam_id, score, total_questions)
     VALUES (?, ?, ?, ?)"
);

$stmt->execute([
    $_SESSION["user_id"],
    $examId,
    $correctAnswers,
    $totalQuestions
]);

$resultId = $conn->lastInsertId();

// End the active examination.
unset($_SESSION["active_exam"]);

header("Location: result.php?id=" . $resultId);
exit;
?>
