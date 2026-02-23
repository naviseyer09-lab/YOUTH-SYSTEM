<?php include('includes/header.php');

if(isset($_POST['add'])){
    $skill = $_POST['skill'];
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if($user_id){
        $stmt = $conn->prepare("INSERT INTO skills (user_id,skill_name) VALUES (?,?)");
        if($stmt){
            $stmt->bind_param('is', $user_id, $skill);
            $stmt->execute();
            $stmt->close();
            $msg = 'Skill added.';
        } else {
            $msg = 'Database error: could not prepare statement.';
        }
    } else {
        $msg = 'You must be logged in to add skills.';
    }
}
?>

<section class="card">
    <h1>Add Skill</h1>
    <?php if(isset($msg)) echo '<div class="alert">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="POST" class="form">
        <label>Skill Name
            <input type="text" name="skill" placeholder="e.g., JavaScript, Graphic Design, Data Analysis" required>
        </label>
        <div class="form-actions">
            <button class="btn" name="add">Add Skill</button>
        </div>
    </form>
</section>

<?php include('includes/footer.php'); ?>