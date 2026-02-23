<?php include('includes/header.php');

if(isset($_POST['register'])){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (fullname,email,password,role) VALUES (?,?,?,?)");
    if($stmt){
        $stmt->bind_param('ssss', $fullname, $email, $password, $role);
        if($stmt->execute()){
            $msg = 'Registered Successfully!';
        } else {
            $msg = 'Registration failed: '.$stmt->error;
        }
        $stmt->close();
    } else {
        $msg = 'Database error: could not prepare statement.';
    }
}
?>

<section class="card">
    <h1>Register</h1>
    <p class="subtitle">Create your account and choose the correct role to get the right tools.</p>
    <?php if(isset($msg)) echo '<div class="alert">'.htmlspecialchars($msg).'</div>'; ?>
    <form method="POST" class="form">
        <label>Full Name
            <input type="text" name="fullname" placeholder="Full Name" required>
        </label>
        <label>Email
            <input type="email" name="email" placeholder="Email" required>
        </label>
        <label>Password
            <input type="password" name="password" placeholder="Password" required>
        </label>
        <label>Role
            <select name="role">
                <option value="youth">Employee (Youth)</option>
                <option value="company">Employer (Company)</option>
            </select>
        </label>
        <div class="form-actions">
            <button class="btn" name="register">Register</button>
        </div>
    </form>
    <div class="action-row">
        <a class="btn secondary" href="/youth-system/login.php">Already have an account? Login</a>
    </div>
</section>

<?php include('includes/footer.php'); ?>