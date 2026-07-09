<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/db.php';


if (!isset($_SESSION['user']) || empty($_SESSION['user']['id_user'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? 0;


$sql = "SELECT * FROM post WHERE id_post = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


if (!$post || $post['id_user'] != $_SESSION['user']['id_user']) {
    header('Location: index.php');
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
        $sql = "UPDATE post SET title = ?, content = ? WHERE id_post = ? AND id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssii', $title, $content, $id, $userId);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Post updated successfully!';

            $post['title'] = $title;
            $post['content'] = $content;
        } else {
            $error = 'Error updating (or nothing changed).';
        }
        mysqli_stmt_close($stmt);
    }
}

include 'header.php';
?>

    <h1>Edit Post</h1>

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?> <a href="index.php">Go Home</a></div><?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-submit">Save Changes</button>
        <a href="index.php" class="btn" style="background:gray;color:white;">Cancel</a>
    </form>

<?php include 'footer.php'; ?>
