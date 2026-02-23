<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_company = $user_role === 'company';
$result = null;
$error = null;

if($user_id && $is_company){
    // Check if company_id column exists
    $check_col = $conn->query("SHOW COLUMNS FROM trainings LIKE 'company_id'");
    if($check_col && $check_col->num_rows > 0){
        // Column exists, use it
        $stmt = $conn->prepare("SELECT * FROM trainings WHERE company_id = ? ORDER BY created_at DESC");
        if($stmt){
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        // Column doesn't exist, show message to run installer
        $error = "Database needs to be updated. Please run the installer: <a href='/youth-system/install.php'>install.php</a>";
    }
}
?>

<section class="card">
  <h1>My Posted Trainings</h1>
  <?php if(!$user_id): ?>
    <div class="alert">Please <a href="/youth-system/login.php">log in</a> to view your posted trainings.</div>
  <?php elseif(!$is_company): ?>
    <div class="alert">Only employers can access posted trainings management.</div>
  <?php elseif(isset($error)): ?>
    <div class="alert"><?php echo htmlspecialchars($error); ?></div>
  <?php elseif($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p style="margin-top:8px;color:var(--muted)">Posted: <?php echo htmlspecialchars($row['created_at']); ?></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">You have not posted any trainings yet. <a href="/youth-system/post_training.php">Post one now</a></div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
