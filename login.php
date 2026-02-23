<?php
include('includes/header.php');

$message = '';
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id,fullname,password FROM users WHERE email = ? LIMIT 1');
    if($stmt){
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->bind_result($id,$fullname,$hash);
        if($stmt->fetch()){
            if(password_verify($password, $hash)){
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $fullname;
                $stmt->close();
                header('Location: /youth-system/');
                exit;
            } else {
                $message = 'Invalid credentials.';
            }
        } else {
            $message = 'Invalid credentials.';
        }
        $stmt->close();
    } else {
        $message = 'Database error.';
    }
}
?>

<section class="card">
  <h1>Login</h1>
  <?php if($message): ?><div class="alert"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
  <form method="POST" class="form">
    <label>Email
      <input type="email" name="email" required>
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>
    <div class="form-actions">
      <button class="btn" name="login">Sign in</button>
    </div>
  </form>
</section>

<?php include('includes/footer.php'); ?>
