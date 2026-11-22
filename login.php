
<?php
session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ExamPortal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>

        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </div>
</header>

<section class="exams-section">
    <div class="container">

        <div class="exam-card" style="max-width: 500px; margin: auto;">

            <h2 style="text-align: center;">Student Login</h2>

            <?php if (isset($_GET["registered"])): ?>
                <p style="color: green; margin-top: 15px;">
                    Registration successful! You can now log in.
                </p>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <p style="color: red; margin-top: 15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="">

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

                <button type="submit" class="nav-button">
                    Login
                </button>

            </form>

            <p style="text-align: center; margin-top: 20px;">
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>

        </div>

    </div>
</section>

<footer>
    <p>&copy; <?= date("Y") ?> ExamPortal. All rights reserved.</p>
</footer>

</body>
</html>
