<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_company = $user_role === 'company';

if($user_id && $is_company){
    $stmt = $conn->prepare("SELECT * FROM job_offers WHERE company_id = ? ORDER BY created_at DESC");
    if($stmt){
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else {
    $result = null;
}
?>

<section class="card">
  <h1>My Posted Jobs</h1>
  <?php if(!$user_id): ?>
    <div class="alert">Please <a href="/youth-system/login.php">login</a> to view your posted jobs.</div>
  <?php elseif(!$is_company): ?>
    <div class="alert">Only employers can access posted jobs management.</div>
  <?php elseif($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['offer_type']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p><strong>Required skill:</strong> <?php echo htmlspecialchars($row['required_skill']); ?></p>
        <p style="margin-top:8px;color:var(--muted)">Posted: <?php echo htmlspecialchars($row['created_at']); ?></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">You have not posted any jobs yet. <a href="/youth-system/post_job.php">Post one now</a></div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
