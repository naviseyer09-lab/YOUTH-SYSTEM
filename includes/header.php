<?php
session_start();
include(__DIR__ . '/../config/db.php');

$current_page = basename($_SERVER['PHP_SELF'] ?? '');

function nav_active($pages, $current_page){
  return in_array($current_page, $pages, true) ? 'active' : '';
}
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
      <a class="<?php echo nav_active(['jobs.php'], $current_page); ?>" href="/youth-system/jobs.php">All Jobs</a>
      <a class="<?php echo nav_active(['trainings.php'], $current_page); ?>" href="/youth-system/trainings.php">Trainings</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <?php if(($_SESSION['user_role'] ?? '') === 'youth'): ?>
          <a class="<?php echo nav_active(['match_jobs.php', 'add_skill.php'], $current_page); ?>" href="/youth-system/match_jobs.php">Matched Jobs</a>
          <a class="<?php echo nav_active(['my_enrollments.php', 'register_training.php'], $current_page); ?>" href="/youth-system/my_enrollments.php">My Trainings</a>
          <a class="<?php echo nav_active(['certifications.php'], $current_page); ?>" href="/youth-system/certifications.php">My Certifications</a>
        <?php elseif(($_SESSION['user_role'] ?? '') === 'company'): ?>
          <a class="<?php echo nav_active(['my_jobs.php'], $current_page); ?>" href="/youth-system/my_jobs.php">My Job Posts</a>
          <a class="<?php echo nav_active(['post_job.php'], $current_page); ?>" href="/youth-system/post_job.php">Post Job</a>
          <a class="<?php echo nav_active(['my_trainings.php'], $current_page); ?>" href="/youth-system/my_trainings.php">Posted Trainings</a>
          <a class="<?php echo nav_active(['post_training.php'], $current_page); ?>" href="/youth-system/post_training.php">Post Training</a>
        <?php endif; ?>
        <a href="/youth-system/logout.php">Logout</a>
      <?php else: ?>
        <a class="<?php echo nav_active(['login.php'], $current_page); ?>" href="/youth-system/login.php">Login</a>
        <a class="<?php echo nav_active(['register.php'], $current_page); ?>" href="/youth-system/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
