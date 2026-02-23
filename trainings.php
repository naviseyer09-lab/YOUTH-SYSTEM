<?php include('includes/header.php');
// Check if company_id column exists, then decide which query to use
$check_col = $conn->query("SHOW COLUMNS FROM trainings LIKE 'company_id'");
if($check_col && $check_col->num_rows > 0){
    // Column exists, use JOIN
    $result = $conn->query("SELECT trainings.*, users.fullname AS company_name FROM trainings LEFT JOIN users ON trainings.company_id = users.id ORDER BY trainings.created_at DESC");
} else {
    // Column doesn't exist yet, just select trainings
    $result = $conn->query("SELECT * FROM trainings ORDER BY created_at DESC");
}
if(!$result){
    echo "Query error: " . $conn->error;
}
?>

<section class="card">
  <h1>Available Trainings</h1>
  <p class="subtitle">Explore training programs offered by employers.</p>
  <?php $is_logged_in = isset($_SESSION['user_id']); ?>
  <?php $is_youth = (($_SESSION['user_role'] ?? '') === 'youth'); ?>

  <?php if($is_logged_in && $is_youth): ?>
    <div class="action-row" style="margin-bottom:12px">
      <a class="btn secondary" href="/youth-system/my_enrollments.php">My Enrolled Trainings</a>
    </div>
  <?php endif; ?>

  <?php if($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <?php if(isset($row['company_name'])): ?>
        <p class="meta">Posted by: <?php echo htmlspecialchars($row['company_name'] ?? 'Company'); ?> — <?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></p>
        <?php else: ?>
        <p class="meta">Posted: <?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></p>
        <?php endif; ?>
        <?php if($is_youth): ?>
          <div class="action-row">
            <a href="register_training.php?id=<?php echo (int)$row['id']; ?>" class="btn">Register Now</a>
          </div>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">No trainings available.</div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>