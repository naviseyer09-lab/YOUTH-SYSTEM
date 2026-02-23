<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';

if(isset($_POST['add'])){
    $skill = $_POST['skill'];
    if(!$user_id){
        $msg = 'You must be logged in to add skills.';
    } elseif(!$is_youth){
        $msg = 'Only employees can add skills.';
    } else {
        $stmt = $conn->prepare("INSERT INTO skills (user_id,skill_name) VALUES (?,?)");
        if($stmt){
            $stmt->bind_param('is', $user_id, $skill);
            $stmt->execute();
            $stmt->close();
            $msg = 'Skill added.';
        } else {
            $msg = 'Database error: could not prepare statement.';
        }
    }
}
?>

<section class="card">
    <h1>Add Skill</h1>
    <?php if(isset($msg)) echo '<div class="alert">'.htmlspecialchars($msg).'</div>'; ?>
    <?php if(!$user_id): ?>
        <div class="alert">Please <a href="/youth-system/login.php">log in</a> as an employee to add skills.</div>
    <?php elseif(!$is_youth): ?>
        <div class="alert">Only employees can add skills.</div>
    <?php else: ?>
        <form method="POST" class="form">
            <label>Skill Name
                <input type="text" name="skill" placeholder="e.g., JavaScript, Graphic Design, Data Analysis" required>
            </label>
            <div class="form-actions">
                <button class="btn" name="add">Add Skill</button>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php include('includes/footer.php'); ?>