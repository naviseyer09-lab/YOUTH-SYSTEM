<?php include('includes/header.php');

if(isset($_POST['post'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $offer_type = $_POST['offer_type'];
    $required_skill = $_POST['required_skill'];
    $company_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if($company_id){
        $stmt = $conn->prepare("INSERT INTO job_offers (company_id,title,description,offer_type,required_skill) VALUES (?,?,?,?,?)");
        if($stmt){
            $stmt->bind_param('issss', $company_id, $title, $description, $offer_type, $required_skill);
            if($stmt->execute()){
                $stmt->close();
                // Redirect to jobs listing to show the newly posted job
                header('Location: /youth-system/jobs.php');
                exit;
            } else {
                $msg = 'Insert failed: ' . $stmt->error;
                $stmt->close();
            }
        } else {
            $msg = 'Database error: ' . $conn->error;
        }
    } else {
        $msg = 'You must be logged in to post jobs.';
    }
}
?>

<section class="card">
    <h1>Post Job</h1>
    <?php if(isset($msg)) echo '<div class="alert">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="POST" class="form">
        <label>Title
            <input type="text" name="title" placeholder="Job Title" required>
        </label>
        <label>Description
            <textarea name="description" placeholder="Description" required></textarea>
        </label>
        <label>Offer Type
            <select name="offer_type">
                <option value="Part-time">Part-time</option>
                <option value="Full-time">Full-time</option>
                <option value="Internship Training Only">Internship Training Only (OJT)</option>
            </select>
        </label>
        <label>Required Skill
            <input type="text" name="required_skill" placeholder="Required Skill" required>
        </label>
        <div class="form-actions">
                        <button class="btn" name="post">Post Offer</button>
        </div>
    </form>
    <?php if(!empty($posted)): ?>
        <p style="margin-top:12px"><a class="btn secondary" href="/youth-system/jobs.php">View all jobs</a></p>
    <?php endif; ?>
</section>


<?php include('includes/footer.php'); ?>