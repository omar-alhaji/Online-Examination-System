
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if (!isset($_GET["id"]) || !filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
    header("Location: dashboard.php");
    exit;
}

$examId = (int)$_GET["id"];

$stmt = $conn->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$examId]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, question_text, option_a, option_b,
            option_c, option_d
     FROM questions
     WHERE exam_id = ?"
);
$stmt->execute([$examId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    header("Location: dashboard.php");
    exit;
}

// Start the timer only once for this examination.
if (
    !isset($_SESSION["active_exam"]) ||
    $_SESSION["active_exam"]["exam_id"] !== $examId
) {
    $_SESSION["active_exam"] = [
        "exam_id" => $examId,
        "started_at" => time()
    ];
}

$duration = (int)$exam["duration"] * 60;
$elapsed = time() - $_SESSION["active_exam"]["started_at"];
$remaining = max(0, $duration - $elapsed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($exam["title"]) ?> | ExamPortal</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/exam.js" defer></script>
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>

        <div class="timer-container">
            Time Remaining:
            <span id="timer" data-remaining="<?= $remaining ?>">
                Loading...
            </span>
        </div>
    </div>
</header>

<section class="exams-section">
    <div class="container">

        <h2><?= htmlspecialchars($exam["title"]) ?></h2>

        <p class="section-description">
            Answer all questions before the time runs out.
        </p>

        <form id="examForm" action="submit_exam.php" method="POST">

            <input type="hidden" name="exam_id"
                   value="<?= $examId ?>">

            <?php foreach ($questions as $index => $question): ?>

                <div class="exam-question">

                    <h3>
                        Question <?= $index + 1 ?>:
                        <?= htmlspecialchars($question["question_text"]) ?>
                    </h3>

                    <?php
                    $options = [
                        "A" => $question["option_a"],
                        "B" => $question["option_b"],
                        "C" => $question["option_c"],
                        "D" => $question["option_d"]
                    ];
                    ?>

                    <?php foreach ($options as $letter => $option): ?>

                        <label class="answer-option">
                            <input
                                type="radio"
                                name="answers[<?= $question["id"] ?>]"
                                value="<?= $letter ?>"
                                <?= $letter === "A" ? "required" : "" ?>
                            >

                            <?= $letter ?>.
                            <?= htmlspecialchars($option) ?>
                        </label>

                    <?php endforeach; ?>

                </div>

            <?php endforeach; ?>

            <button type="submit" class="exam-button submit-exam">
                Submit Examination
            </button>

        </form>

    </div>
</section>

</body>
</html>
