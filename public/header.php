<?php
require_once __DIR__ . '/../app/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">My Forum</a>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <?php

                $loggedIn = isset($_SESSION['user']) && !empty($_SESSION['user']['id_user']);
                if ($loggedIn):
                    ?>
                    <li><a href="add_post.php">New Post</a></li>
                    <li><span class="user-greeting">Hello <?= htmlspecialchars($_SESSION['user']['username']) ?></span></li>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php" class="btn-login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
<main class="container">
