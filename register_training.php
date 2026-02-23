<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';
$training_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$message_type = 'alert';
$training = null;

if($training_id > 0){
    $stmt_training = $conn->prepare("SELECT trainings.*, users.fullname AS company_name FROM trainings LEFT JOIN users ON trainings.company_id = users.id WHERE trainings.id = ? LIMIT 1");
    if($stmt_training){
        $stmt_training->bind_param('i', $training_id);
        $stmt_training->execute();
        $result_training = $stmt_training->get_result();
        $training = $result_training ? $result_training->fetch_assoc() : null;
        $stmt_training->close();
    }
}

if(isset($_POST['register'])){
    if(!$user_id){
        $message = 'Please log in as an employee to register for training.';
    } elseif(!$is_youth){
        $message = 'Only employees can register for trainings.';
    } elseif($training_id <= 0 || !$training){
        $message = 'Invalid training selected.';
    } else {
        $check = $conn->prepare("SELECT id FROM training_enrollments WHERE training_id = ? AND user_id = ? LIMIT 1");
        if($check){
            $check->bind_param('ii', $training_id, $user_id);
            $check->execute();
            $check->store_result();
            $already_enrolled = $check->num_rows > 0;
            $check->close();

            if($already_enrolled){
                $message = 'You are already registered for this training.';
            } else {
                $insert = $conn->prepare("INSERT INTO training_enrollments (training_id, user_id) VALUES (?, ?)");
                if($insert){
                    $insert->bind_param('ii', $training_id, $user_id);
                    if($insert->execute()){
                        $message = 'Training registration successful!';
                        $message_type = 'alert success';
                    } else {
                        $message = 'Registration failed. Please try again.';
                    }
                    $insert->close();
                } else {
                    $message = 'Database error while registering.';
                }
            }
        } else {
            $message = 'Database error while checking enrollment.';
        }
    }
}
?>

<section class="card">
    <h1>Register for Training</h1>
    <?php if($message): ?>
        <div class="<?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if(!$training): ?>
        <div class="alert">Training not found. <a href="/youth-system/trainings.php">Back to trainings</a></div>
    <?php else: ?>
        <article class="card">
            <h3><?php echo htmlspecialchars($training['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($training['description'])); ?></p>
            <p class="meta">Posted by: <?php echo htmlspecialchars($training['company_name'] ?? 'Company'); ?> — <?php echo htmlspecialchars($training['created_at'] ?? 'N/A'); ?></p>
        </article>

        <?php if(!$user_id): ?>
            <div class="alert">Please <a href="/youth-system/login.php">log in</a> as an employee to continue.</div>
        <?php elseif(!$is_youth): ?>
            <div class="alert">Only employees can register for trainings.</div>
        <?php else: ?>
            <form method="POST" class="form">
                <div class="form-actions">
                    <button class="btn" name="register">Confirm Registration</button>
                    <a class="btn secondary" href="/youth-system/my_enrollments.php">My Enrolled Trainings</a>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>
