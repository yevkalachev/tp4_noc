<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/db.php';


if (!isset($_SESSION['user']) || empty($_SESSION['user']['id_user'])) {
    header('Location: login.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $userId = $_SESSION['user']['id_user'];

    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        $sql = "INSERT INTO post (title, content, id_user) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $userId);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Post published successfully! <a href="index.php">View all posts</a>.';
            $_POST = [];
        } else {
            $error = 'Something went wrong. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

include 'header.php';
?>

    <h1>Write a New Post</h1>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-submit">Publish</button>
    </form>

<?php include 'footer.php'; ?>
