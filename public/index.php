<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/db.php';

$sql = "SELECT p.*, u.full_name, u.username 
        FROM post p 
        JOIN user u ON p.id_user = u.id_user 
        ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);
$posts = [];
if ($result) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

include 'header.php';
?>

    <h1>sshAll Forum Posts</h1>

<?php if (empty($posts)): ?>
    <p style="text-align:center; color:#7f8c8d;">No posts yet. Be the first to write!</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="post-card">
            <h2 class="post-title"><?= htmlspecialchars($post['title']) ?></h2>
            <div class="post-meta">
                Written by <strong><?= htmlspecialchars($post['username']) ?></strong>
                (<?= htmlspecialchars($post['full_name']) ?>) &nbsp;|&nbsp;
                <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
            </div>
            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <?php
            $loggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']['id_user']);
            if ($loggedIn && $_SESSION['user']['id_user'] == $post['id_user']):
                ?>
                <div class="post-actions" style="margin-top:15px;">
                    <a href="edit_post.php?id=<?= $post['id_post'] ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_post.php?id=<?= $post['id_post'] ?>" class="btn btn-delete" onclick="return confirm('Delete this post?')">Delete</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>
