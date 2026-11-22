
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$sql = "SELECT exams.*,
        (SELECT COUNT(*) FROM questions
         WHERE questions.exam_id = exams.id) AS question_count
        FROM exams";

$stmt = $conn->query($sql);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ExamPortal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>

        <nav>
            <span>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
            <a href="my_results.php">My Results</a>
            <a href="logout.php" class="nav-button">Logout</a>
        </nav>
    </div>
</header>

<section class="exams-section">
    <div class="container">

        <h2>Your Dashboard</h2>

        <p class="section-description">
            Choose an examination and test your knowledge.
        </p>

        <div class="exam-grid">

            <?php foreach ($exams as $exam): ?>

                <div class="exam-card">

                    <h3><?= htmlspecialchars($exam["title"]) ?></h3>

                    <p>
                        <?= htmlspecialchars($exam["description"] ?? "") ?>
                    </p>

                    <div class="exam-info">
                        <span>
                            <?= (int)$exam["duration"] ?> minutes
                        </span>

                        <span>
                            <?= (int)$exam["question_count"] ?> questions
                        </span>
                    </div>

                    <?php if ((int)$exam["question_count"] > 0): ?>
                        <a href="exam.php?id=<?= (int)$exam["id"] ?>"
                           class="exam-button">
                            Start Examination
                        </a>
                    <?php else: ?>
                        <p>No questions available.</p>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<footer>
    <p>&copy; <?= date("Y") ?> ExamPortal. All rights reserved.</p>
</footer>

</body>
</html>
