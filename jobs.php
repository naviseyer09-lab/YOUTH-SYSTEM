<?php include('includes/header.php');

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
?>

<section class="card">
  <h1>All Job Offers</h1>
  <div style="margin-bottom:14px;display:flex;gap:8px;flex-wrap:wrap">
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
        <p style="margin-top:8px;color:var(--muted)">Posted by: <?php echo htmlspecialchars($row['company_name'] ?? 'Company'); ?> — <?php echo htmlspecialchars($row['created_at']); ?></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">No job offers yet.</div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
