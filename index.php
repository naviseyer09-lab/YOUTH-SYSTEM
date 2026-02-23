<?php include('includes/header.php'); ?>

<section class="card">
  <h1>Welcome to Youth Skills</h1>
  <p class="subtitle">Choose the right section based on your role.</p>

  <?php if(isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'youth'): ?>
    <h2>Employee (Youth)</h2>
    <p>Browse opportunities, get matched jobs, and track your certifications.</p>
    <div class="action-row">
      <a class="btn" href="/youth-system/jobs.php">Browse Jobs</a>
      <a class="btn" href="/youth-system/match_jobs.php">Matched Jobs</a>
      <a class="btn" href="/youth-system/trainings.php">Trainings</a>
      <a class="btn" href="/youth-system/my_enrollments.php">My Trainings</a>
      <a class="btn" href="/youth-system/certifications.php">My Certifications</a>
    </div>
  <?php elseif(isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'company'): ?>
    <h2>Employer (Company)</h2>
    <p>Post jobs and trainings, then manage your published opportunities.</p>
    <div class="action-row">
      <a class="btn" href="/youth-system/post_job.php">Post Job</a>
      <a class="btn" href="/youth-system/my_jobs.php">My Job Posts</a>
      <a class="btn" href="/youth-system/post_training.php">Post Training</a>
      <a class="btn" href="/youth-system/my_trainings.php">My Trainings</a>
    </div>
  <?php else: ?>
    <h2>Employee (Youth)</h2>
    <p>Find jobs, explore trainings, and manage certifications.</p>
    <div class="action-row">
      <a class="btn" href="/youth-system/jobs.php">Browse Jobs</a>
      <a class="btn" href="/youth-system/match_jobs.php">Matched Jobs</a>
      <a class="btn" href="/youth-system/trainings.php">Trainings</a>
      <a class="btn" href="/youth-system/certifications.php">Certifications</a>
    </div>

    <div class="section-divider"></div>
    <h2>Employer (Company)</h2>
    <p>Post job offers and trainings for employees.</p>
    <div class="action-row">
      <a class="btn" href="/youth-system/post_job.php">Post Job</a>
      <a class="btn" href="/youth-system/post_training.php">Post Training</a>
    </div>

    <div class="action-row">
      <a class="btn" href="/youth-system/login.php">Login</a>
      <a class="btn secondary" href="/youth-system/register.php">Register</a>
    </div>
  <?php endif; ?>

  <?php if(isset($_SESSION['user_id'])): ?>
    <p class="greeting">Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></p>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
