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
  <?php if($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <?php if(isset($row['company_name'])): ?>
        <p style="margin-top:8px;color:var(--muted)">Posted by: <?php echo htmlspecialchars($row['company_name'] ?? 'Company'); ?> — <?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></p>
        <?php else: ?>
        <p style="margin-top:8px;color:var(--muted)">Posted: <?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></p>
        <?php endif; ?>
        <p style="margin-top:12px"><a href="register_training.php?id=<?php echo (int)$row['id']; ?>" class="btn">Register Now</a></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">No trainings available.</div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>