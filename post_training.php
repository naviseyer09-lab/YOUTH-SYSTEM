<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
// Check if company_id column exists
$check_col = $conn->query("SHOW COLUMNS FROM trainings LIKE 'company_id'");
$column_exists = $check_col && $check_col->num_rows > 0;

if(isset($_POST['post'])){
    $title = $_POST['title'];
    $description = $_POST['description'];

    if(!$user_id){
        $msg = 'You must be logged in to post trainings.';
    } elseif(empty($title) || empty($description)){
        $msg = 'Please fill in all fields.';
    } elseif(!$column_exists){
        $msg = 'Database needs to be updated. Please run the installer: <a href="/youth-system/install.php">install.php</a>';
    } else {
        $stmt = $conn->prepare("INSERT INTO trainings (company_id,title,description) VALUES (?,?,?)");
        if($stmt){
            $stmt->bind_param('iss', $user_id, $title, $description);
            if($stmt->execute()){
                $stmt->close();
                header('Location: /youth-system/trainings.php');
                exit;
            } else {
                $msg = 'Insert failed: ' . $stmt->error;
                $stmt->close();
            }
        } else {
            $msg = 'Database error: ' . $conn->error;
        }
    }
}
?>

<section class="card">
  <h1>Post Training</h1>
  <?php if(!$user_id): ?>
    <div class="alert">Please <a href="/youth-system/login.php">log in</a> to post trainings.</div>
  <?php else: ?>
    <?php if(isset($msg)) echo '<div class="alert">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="POST" class="form">
      <label>Training Title
        <input type="text" name="title" placeholder="e.g., Web Development Bootcamp" required>
      </label>
      <label>Description
        <textarea name="description" placeholder="Describe the training, topics covered, duration, etc." required></textarea>
      </label>
      <div class="form-actions">
        <button class="btn" name="post">Post Training</button>
      </div>
    </form>
  <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
