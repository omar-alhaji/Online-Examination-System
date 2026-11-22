
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

$resultId = (int)$_GET["id"];

$stmt = $conn->prepare(
    "SELECT results.*, exams.title
     FROM results
     INNER JOIN exams ON results.exam_id = exams.id
     WHERE results.id = ? AND results.user_id = ?"
);

$stmt->execute([$resultId, $_SESSION["user_id"]]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    header("Location: dashboard.php");
    exit;
}

$score = (int)$result["score"];
$total = (int)$result["total_questions"];

$percentage = $total > 0
    ? round(($score / $total) * 100, 2)
    : 0;

$status = $percentage >= 50 ? "Passed" : "Failed";
$statusColor = $status === "Passed" ? "green" : "red";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results | ExamPortal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>

        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="nav-button">Logout</a>
        </nav>
    </div>
</header>

<section class="exams-section">
    <div class="container">

        <div class="exam-card result-card">

            <h2>Examination Results</h2>

            <p class="section-description">
                <?= htmlspecialchars($result["title"]) ?>
            </p>

            <div class="result-score">
                <?= $score ?> / <?= $total ?>
            </div>

            <p class="result-percentage">
                <?= $percentage ?>%
            </p>

            <h3 style="color: <?= $statusColor ?>;">
                <?= $status ?>
            </h3>

            <p class="result-message">
                <?php if ($status === "Passed"): ?>
                    Congratulations! You passed the examination.
                <?php else: ?>
                    Keep practicing. You can improve your result!
                <?php endif; ?>
            </p>

            <a href="dashboard.php" class="exam-button">
                Back to Dashboard
            </a>

        </div>

    </div>
</section>

<footer>
    <p>&copy; <?= date("Y") ?> ExamPortal. All rights reserved.</p>
</footer>

</body>
</html>
