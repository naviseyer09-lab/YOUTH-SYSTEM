<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$result = null;

if($user_id){
    $stmt = $conn->prepare("SELECT job_offers.* FROM job_offers JOIN skills ON job_offers.required_skill = skills.skill_name WHERE skills.user_id = ? ORDER BY job_offers.created_at DESC");
    if($stmt){
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

?>

<section class="card">
  <h1>Jobs Matched to Your Skills</h1>
  <?php if(empty($user_id)): ?>
    <div class="alert">Please <a href="/youth-system/login.php">log in</a> to view matched jobs.</div>
  <?php elseif($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['offer_type']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p style="margin-top:8px;color:var(--muted)"><strong>Required skill:</strong> <?php echo htmlspecialchars($row['required_skill']); ?></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert"><strong>No matching jobs yet.</strong> Add skills to get matched with job offers. <a href="/youth-system/add_skill.php">Add a skill</a></div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>