<?php
session_start();
include(__DIR__ . '/../config/db.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Youth Skills System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/youth-system/assets/css/styles.css">
  <link rel="icon" type="image/svg+xml" href="/youth-system/assets/img/favicon.svg">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="/youth-system/"><img src="/youth-system/assets/img/logo.svg" alt="Youth Skills" class="brand-logo"></a>
    <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
      <span class="hamburger"></span>
    </button>
    <nav class="nav" id="main-nav">
      <a href="/youth-system/jobs.php">All Jobs</a>
      <a href="/youth-system/match_jobs.php">Matched</a>
      <a href="/youth-system/my_jobs.php">My Posts</a>
      <a href="/youth-system/post_job.php">Post Job</a>
      <a href="/youth-system/trainings.php">Trainings</a>
      <a href="/youth-system/my_trainings.php">My Trainings</a>
      <a href="/youth-system/post_training.php">Post Training</a>
      <a href="/youth-system/certifications.php">Certifications</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="/youth-system/logout.php">Logout</a>
      <?php else: ?>
        <a href="/youth-system/login.php">Login</a>
        <a href="/youth-system/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
