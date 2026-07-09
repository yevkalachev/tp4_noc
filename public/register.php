<?php

require_once __DIR__ . '/../app/db.php';


$error = null;
$success = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $birthDate = $_POST['birth_date'] ?? '';


    if (empty($fullName) || empty($username) || empty($password) || empty($birthDate)) {
        $error = 'All fields are required.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Hash the password (salt + hash)
        $hashed = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO user (full_name, username, password_hash, birth_date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $fullName, $username, $hashed, $birthDate);

        if (mysqli_stmt_execute($stmt)) {
            $success = 'Account created! You can now <a href="login.php">login</a>.';
        } else {
            $error = 'This username is already taken. Please choose another.';
        }
        mysqli_stmt_close($stmt);
    }
}

include 'header.php';
?>

    <h1>Create an Account</h1>

<?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="username">Username (login)</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password (min 6 characters)</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <div class="form-group">
            <label for="birth_date">Date of Birth</label>
            <input type="date" id="birth_date" name="birth_date" required>
        </div>
        <button type="submit" class="btn btn-submit">Register</button>
    </form>

<?php include 'footer.php'; ?>
