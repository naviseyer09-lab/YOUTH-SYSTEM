<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_company = $user_role === 'company';
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$job = null;

if(!$user_id || !$is_company){
    header('Location: /youth-system/my_jobs.php');
    exit;
}

if($job_id <= 0){
    header('Location: /youth-system/my_jobs.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM job_offers WHERE id = ? AND company_id = ? LIMIT 1");
if($stmt){
    $stmt->bind_param('ii', $job_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result ? $result->fetch_assoc() : null;
    $stmt->close();
}

if(!$job){
    header('Location: /youth-system/my_jobs.php');
    exit;
}

if(isset($_POST['save'])){
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $offer_type = trim($_POST['offer_type'] ?? '');
    $required_skill = trim($_POST['required_skill'] ?? '');

    if($title === '' || $description === '' || $offer_type === '' || $required_skill === ''){
        $message = 'Please complete all fields.';
    } else {
        $update = $conn->prepare("UPDATE job_offers SET title = ?, description = ?, offer_type = ?, required_skill = ? WHERE id = ? AND company_id = ?");
        if($update){
            $update->bind_param('ssssii', $title, $description, $offer_type, $required_skill, $job_id, $user_id);
            if($update->execute()){
                $update->close();
                header('Location: /youth-system/my_jobs.php?status=updated');
                exit;
            }
            $update->close();
        }
        $message = 'Failed to update job. Please try again.';
    }
}
?>

<section class="card">
  <h1>Edit Job Offer</h1>
  <?php if($message): ?>
    <div class="alert"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <form method="POST" class="form">
    <label>Title
      <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
    </label>
    <label>Description
      <textarea name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>
    </label>
    <label>Offer Type
      <select name="offer_type" required>
        <option value="Part-time" <?php echo $job['offer_type'] === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
        <option value="Full-time" <?php echo $job['offer_type'] === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
        <option value="Internship Training Only" <?php echo $job['offer_type'] === 'Internship Training Only' ? 'selected' : ''; ?>>Internship Training Only (OJT)</option>
      </select>
    </label>
    <label>Required Skill
      <input type="text" name="required_skill" value="<?php echo htmlspecialchars($job['required_skill']); ?>" required>
    </label>
    <div class="form-actions">
      <button class="btn" name="save" type="submit">Save Changes</button>
      <a class="btn secondary" href="/youth-system/my_jobs.php">Cancel</a>
    </div>
  </form>
</section>

<?php include('includes/footer.php'); ?>
