
<?php
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must contain at least 6 characters.";
    }
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "This email is already registered.";
        }
        else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );

            $stmt->execute([$name, $email, $hashedPassword]);

            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ExamPortal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container navbar">
        <h2>Exam<span>Portal</span></h2>
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<section class="exams-section">
    <div class="container">
        <div class="exam-card" style="max-width:450px; margin:auto;">
            <h2>Create Account</h2>
            <p class="section-description">Register to start taking examinations.</p>

            <?php if ($error !== ""): ?>
                <p style="color:red; margin-bottom:15px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="">

                <label>Full Name</label>
                <input type="text" name="name" required
                       value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">

                <label>Email Address</label>
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">

                <label>Password</label>
                <input type="password" name="password" required minlength="6">

                <button type="submit" class="exam-button">
                    Create Account
                </button>

            </form>

            <p style="margin-top:20px;">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>
