
<?php
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
    <title>ExamPortal | Online Examination</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>

        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="nav-button">Register</a>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>Test Your Knowledge</h1>
        <p>Choose an examination, challenge yourself, and discover your results.</p>
        <a href="#exams" class="main-button">Explore Exams</a>
    </div>
</section>

<section class="exams-section" id="exams">
    <div class="container">
        <h2>Available Examinations</h2>
        <p class="section-description">
            Choose an exam and prepare to test your skills.
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

                    <a href="register.php" class="exam-button">
                        Start Examination
                    </a>
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
