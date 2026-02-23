<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';
$applied_job_ids = [];

$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
if($type_filter){
    $stmt = $conn->prepare("SELECT job_offers.*, users.fullname AS company_name FROM job_offers LEFT JOIN users ON job_offers.company_id = users.id WHERE job_offers.offer_type = ? ORDER BY job_offers.created_at DESC");
    if($stmt){
        $stmt->bind_param('s', $type_filter);
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else {
    $result = $conn->query("SELECT job_offers.*, users.fullname AS company_name FROM job_offers LEFT JOIN users ON job_offers.company_id = users.id ORDER BY job_offers.created_at DESC");
}
$debug = isset($_GET['debug']) && $_GET['debug'] == '1';

if($user_id && $is_youth){
  $applied = $conn->prepare("SELECT job_id FROM job_applications WHERE user_id = ?");
  if($applied){
    $applied->bind_param('i', $user_id);
    $applied->execute();
    $applied_result = $applied->get_result();
    while($row = $applied_result->fetch_assoc()){
      $applied_job_ids[(int)$row['job_id']] = true;
    }
    $applied->close();
  }
}

$apply_status = isset($_GET['apply']) ? $_GET['apply'] : '';
?>

<section class="card">
  <h1>All Job Offers</h1>
  <p class="subtitle">Browse available opportunities and filter by job type.</p>
  <?php if($apply_status === 'success'): ?>
    <div class="alert success">Application submitted successfully.</div>
  <?php elseif($apply_status === 'duplicate'): ?>
    <div class="alert">You already applied to this job.</div>
  <?php elseif($apply_status === 'forbidden'): ?>
    <div class="alert">Only employees can apply for jobs.</div>
  <?php elseif($apply_status === 'invalid'): ?>
    <div class="alert">Invalid job selected.</div>
  <?php elseif($apply_status === 'error'): ?>
    <div class="alert">Something went wrong while applying. Please try again.</div>
  <?php endif; ?>
  <div class="filter-row">
    <a class="btn secondary <?php echo !$type_filter ? 'active' : ''; ?>" href="/youth-system/jobs.php">All Types</a>
    <a class="btn secondary <?php echo $type_filter === 'Part-time' ? 'active' : ''; ?>" href="/youth-system/jobs.php?type=Part-time">Part-time</a>
    <a class="btn secondary <?php echo $type_filter === 'Full-time' ? 'active' : ''; ?>" href="/youth-system/jobs.php?type=Full-time">Full-time</a>
    <a class="btn secondary <?php echo $type_filter === 'Internship Training Only' ? 'active' : ''; ?>" href="/youth-system/jobs.php?type=Internship Training Only">Internship</a>
  </div>
  <?php if($debug): ?>
    <div class="alert" style="background:#e6f2ff;border-color:#bfdbfe;color:#1e40af">
      <strong>Debug Info:</strong> DB: <?php echo isset($conn->query("SELECT DATABASE()")->fetch_row()[0]) ? htmlspecialchars($conn->query("SELECT DATABASE()")->fetch_row()[0]) : 'N/A'; ?> | 
      Jobs found: <?php echo $result ? $result->num_rows : 'Query failed'; ?>
    </div>
  <?php endif; ?>
  <?php if($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($row['offer_type']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p><strong>Required skill:</strong> <?php echo htmlspecialchars($row['required_skill']); ?></p>
        <p class="meta">Posted by: <?php echo htmlspecialchars($row['company_name'] ?? 'Company'); ?> — <?php echo htmlspecialchars($row['created_at']); ?></p>
        <?php if($is_youth): ?>
          <div class="action-row">
            <?php if(isset($applied_job_ids[(int)$row['id']])): ?>
              <button class="btn secondary" type="button" disabled>Applied</button>
            <?php else: ?>
              <form method="POST" action="/youth-system/apply_job.php">
                <input type="hidden" name="job_id" value="<?php echo (int)$row['id']; ?>">
                <button class="btn" type="submit">Apply Now</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">No job offers yet.</div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
