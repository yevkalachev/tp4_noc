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


$sql = "SELECT id_user FROM post WHERE id_post = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


if ($post && $post['id_user'] == $_SESSION['user']['id_user']) {
    $sql = "DELETE FROM post WHERE id_post = ? AND id_user = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $id, $_SESSION['user']['id_user']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


header('Location: index.php');
exit;
