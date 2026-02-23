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

$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<section class="card">
  <h1>My Posted Jobs</h1>
  <?php if($status === 'updated'): ?>
    <div class="alert success">Job offer updated successfully.</div>
  <?php elseif($status === 'deleted'): ?>
    <div class="alert success">Job offer deleted successfully.</div>
  <?php elseif($status === 'notfound'): ?>
    <div class="alert">Job not found or not owned by your account.</div>
  <?php elseif($status === 'forbidden'): ?>
    <div class="alert">You are not allowed to perform that action.</div>
  <?php elseif($status === 'error'): ?>
    <div class="alert">An error occurred while processing your request.</div>
  <?php endif; ?>
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
        <p class="meta">Posted: <?php echo htmlspecialchars($row['created_at']); ?></p>
        <div class="action-row">
          <a class="btn secondary" href="/youth-system/edit_job.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
          <form method="POST" action="/youth-system/delete_job.php" onsubmit="return confirm('Delete this job offer?');">
            <input type="hidden" name="job_id" value="<?php echo (int)$row['id']; ?>">
            <button class="btn" type="submit">Delete</button>
          </form>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">You have not posted any jobs yet. <a href="/youth-system/post_job.php">Post one now</a></div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
