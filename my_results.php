
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$stmt = $conn->prepare(
    "SELECT results.*, exams.title
     FROM results
     INNER JOIN exams ON results.exam_id = exams.id
     WHERE results.user_id = ?
     ORDER BY results.completed_at DESC"
);

$stmt->execute([$_SESSION["user_id"]]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results | ExamPortal</title>
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

        <h2>My Examination Results</h2>

        <p class="section-description">
            View your previous examination results.
        </p>

        <?php if (count($results) > 0): ?>

            <div class="results-list">

                <?php foreach ($results as $result): ?>

                    <?php
                    $percentage = $result["total_questions"] > 0
                        ? round(
                            ($result["score"] / $result["total_questions"]) * 100,
                            2
                        )
                        : 0;

                    $status = $percentage >= 50 ? "Passed" : "Failed";
                    ?>

                    <div class="exam-card result-item">

                        <h3>
                            <?= htmlspecialchars($result["title"]) ?>
                        </h3>

                        <p>
                            Score:
                            <?= (int)$result["score"] ?> /
                            <?= (int)$result["total_questions"] ?>
                        </p>

                        <p>
                            Percentage: <?= $percentage ?>%
                        </p>

                        <p>
                            Status: <strong><?= $status ?></strong>
                        </p>

                        <p>
                            Completed:
                            <?= htmlspecialchars($result["completed_at"]) ?>
                        </p>

                        <a
                            href="result.php?id=<?= (int)$result["id"] ?>"
                            class="exam-button"
                        >
                            View Result
                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="exam-card">
                <p>You have not completed any examinations yet.</p>
            </div>

        <?php endif; ?>

    </div>
</section>

<footer>
    <p>&copy; <?= date("Y") ?> ExamPortal. All rights reserved.</p>
</footer>

</body>
</html>
