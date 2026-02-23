<?php include('includes/header.php'); ?>

<section class="card">
  <h1>Welcome to Youth Skills</h1>
  <p>Find trainings, post jobs, and manage your skills and certifications.</p>

  <div style="display:flex;gap:12px;margin-top:12px;flex-wrap:wrap">
    <a class="btn" href="/youth-system/match_jobs.php">Find Jobs</a>
    <a class="btn" href="/youth-system/trainings.php">Trainings</a>
    <a class="btn" href="/youth-system/certifications.php">My Certifications</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <span style="align-self:center;color:#7f1d1d">Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
    <?php else: ?>
      <a class="btn" href="/youth-system/login.php">Login</a>
    <?php endif; ?>
  </div>
</section>

<?php include('includes/footer.php'); ?>
