<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';
$result = null;

if($user_id && $is_youth){
    $stmt = $conn->prepare("SELECT te.created_at AS enrolled_at, t.title, t.description, t.created_at, u.fullname AS company_name
        FROM training_enrollments te
        JOIN trainings t ON te.training_id = t.id
        LEFT JOIN users u ON t.company_id = u.id
        WHERE te.user_id = ?
        ORDER BY te.created_at DESC");
    if($stmt){
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<section class="card">
  <h1>My Enrolled Trainings</h1>
  <p class="subtitle">Track the trainings you already registered for.</p>

  <?php if(!$user_id): ?>
    <div class="alert">Please <a href="/youth-system/login.php">log in</a> as an employee to view your enrolled trainings.</div>
  <?php elseif(!$is_youth): ?>
    <div class="alert">Only employees can access enrolled trainings.</div>
  <?php elseif($result && $result->num_rows): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <article class="card">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p class="meta">Provider: <?php echo htmlspecialchars($row['company_name'] ?? 'Company'); ?></p>
        <p class="meta">Enrolled on: <?php echo htmlspecialchars($row['enrolled_at']); ?></p>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert">No enrolled trainings yet. <a href="/youth-system/trainings.php">Browse trainings</a></div>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
