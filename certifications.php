<?php
include("includes/header.php");

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';

if(isset($_POST['add'])){
    $name = $_POST['certificate_name'];
    $date = $_POST['issue_date'];

    if(!$user_id){
        $message = "You must be logged in to add certificates.";
    } elseif(!$is_youth){
        $message = "Only employees can add certifications.";
    } else {
        $stmt = $conn->prepare("INSERT INTO certifications (user_id,certificate_name,issue_date) VALUES (?,?,?)");
        if($stmt){
            $stmt->bind_param('iss', $user_id, $name, $date);
            $stmt->execute();
            $stmt->close();
            $message = "Certificate added.";
        } else {
            $message = "Database error: could not prepare statement.";
        }
    }
}
?>

<section class="card">
    <h1>Certifications</h1>
    <?php if(isset($message)): ?><div class="alert"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <?php if(!$user_id): ?>
        <div class="alert">Please <a href="/youth-system/login.php">log in</a> as an employee to manage certifications.</div>
    <?php elseif(!$is_youth): ?>
        <div class="alert">Only employees can manage certifications.</div>
    <?php else: ?>
        <form method="POST" class="form">
            <label>Certificate Name
                <input type="text" name="certificate_name" placeholder="Certificate Name" required>
            </label>
            <label>Issue Date
                <input type="date" name="issue_date" required>
            </label>
            <div class="form-actions">
                <button type="submit" name="add" class="btn">Add Certificate</button>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php include("includes/footer.php"); ?>