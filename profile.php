<?php include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if(!$user_id){
    header('Location: /youth-system/login.php');
    exit;
}

$message = '';
$message_type = 'alert';

function load_user($conn, $user_id){
    $stmt = $conn->prepare("SELECT id, fullname, email, role FROM users WHERE id = ? LIMIT 1");
    if(!$stmt){
        return null;
    }
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $user;
}

$user = load_user($conn, $user_id);
if(!$user){
    header('Location: /youth-system/logout.php');
    exit;
}

$is_youth = ($user['role'] ?? '') === 'youth';

if(isset($_POST['update_profile'])){
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if($fullname === '' || $email === ''){
        $message = 'Full name and email are required.';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = 'Please enter a valid email address.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
        if($check){
            $check->bind_param('si', $email, $user_id);
            $check->execute();
            $check->store_result();
            $email_exists = $check->num_rows > 0;
            $check->close();

            if($email_exists){
                $message = 'Email is already in use by another account.';
            } else {
                $update = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
                if($update){
                    $update->bind_param('ssi', $fullname, $email, $user_id);
                    if($update->execute()){
                        $_SESSION['user_name'] = $fullname;
                        $message = 'Profile updated successfully.';
                        $message_type = 'alert success';
                    } else {
                        $message = 'Failed to update profile. Please try again.';
                    }
                    $update->close();
                } else {
                    $message = 'Database error while updating profile.';
                }
            }
        } else {
            $message = 'Database error while validating email.';
        }
    }

    $user = load_user($conn, $user_id);
}

if(isset($_POST['change_password'])){
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if($current_password === '' || $new_password === '' || $confirm_password === ''){
        $message = 'Please complete all password fields.';
    } elseif(strlen($new_password) < 6){
        $message = 'New password must be at least 6 characters.';
    } elseif($new_password !== $confirm_password){
        $message = 'New password and confirmation do not match.';
    } else {
        $check_pass = $conn->prepare("SELECT password FROM users WHERE id = ? LIMIT 1");
        if($check_pass){
            $check_pass->bind_param('i', $user_id);
            $check_pass->execute();
            $check_pass->bind_result($hash);
            $has_row = $check_pass->fetch();
            $check_pass->close();

            if(!$has_row || !password_verify($current_password, $hash)){
                $message = 'Current password is incorrect.';
            } else {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                if($update_pass){
                    $update_pass->bind_param('si', $new_hash, $user_id);
                    if($update_pass->execute()){
                        $message = 'Password changed successfully.';
                        $message_type = 'alert success';
                    } else {
                        $message = 'Failed to update password. Please try again.';
                    }
                    $update_pass->close();
                } else {
                    $message = 'Database error while changing password.';
                }
            }
        } else {
            $message = 'Database error while validating password.';
        }
    }
}

if($is_youth && isset($_POST['add_certification'])){
  $certificate_name = trim($_POST['certificate_name'] ?? '');
  $issue_date = trim($_POST['issue_date'] ?? '');

  if($certificate_name === '' || $issue_date === ''){
    $message = 'Please complete certificate name and issue date.';
  } else {
    $add_cert = $conn->prepare("INSERT INTO certifications (user_id, certificate_name, issue_date) VALUES (?, ?, ?)");
    if($add_cert){
      $add_cert->bind_param('iss', $user_id, $certificate_name, $issue_date);
      if($add_cert->execute()){
        $message = 'Certification added successfully.';
        $message_type = 'alert success';
      } else {
        $message = 'Failed to add certification. Please try again.';
      }
      $add_cert->close();
    } else {
      $message = 'Database error while adding certification.';
    }
  }
}

if($is_youth && isset($_POST['delete_certification'])){
  $cert_id = isset($_POST['cert_id']) ? (int)$_POST['cert_id'] : 0;
  if($cert_id <= 0){
    $message = 'Invalid certification selected.';
  } else {
    $delete_cert = $conn->prepare("DELETE FROM certifications WHERE id = ? AND user_id = ?");
    if($delete_cert){
      $delete_cert->bind_param('ii', $cert_id, $user_id);
      $delete_cert->execute();
      if($delete_cert->affected_rows > 0){
        $message = 'Certification deleted successfully.';
        $message_type = 'alert success';
      } else {
        $message = 'Certification not found or not allowed.';
      }
      $delete_cert->close();
    } else {
      $message = 'Database error while deleting certification.';
    }
  }
}

$certifications = null;
if($is_youth){
  $cert_stmt = $conn->prepare("SELECT id, certificate_name, issue_date, created_at FROM certifications WHERE user_id = ? ORDER BY issue_date DESC, created_at DESC");
  if($cert_stmt){
    $cert_stmt->bind_param('i', $user_id);
    $cert_stmt->execute();
    $certifications = $cert_stmt->get_result();
  }
}
?>

<section class="card">
  <h1>My Profile</h1>
  <p class="subtitle">Manage your account information and security settings.</p>

  <?php if($message): ?>
    <div class="<?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <article class="card">
    <h3>Account Details</h3>
    <form method="POST" class="form">
      <label>Full Name
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
      </label>
      <label>Email
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
      </label>
      <label>Role
        <input type="text" value="<?php echo htmlspecialchars(($user['role'] ?? '') === 'company' ? 'Employer (Company)' : 'Employee (Youth)'); ?>" readonly>
      </label>
      <div class="form-actions">
        <button class="btn" type="submit" name="update_profile">Save Profile</button>
      </div>
    </form>
  </article>

  <article class="card">
    <h3>Change Password</h3>
    <form method="POST" class="form">
      <label>Current Password
        <input type="password" name="current_password" required>
      </label>
      <label>New Password
        <input type="password" name="new_password" required>
      </label>
      <label>Confirm New Password
        <input type="password" name="confirm_password" required>
      </label>
      <div class="form-actions">
        <button class="btn" type="submit" name="change_password">Update Password</button>
      </div>
    </form>
  </article>

  <?php if($is_youth): ?>
  <article class="card">
    <h3>My Certifications</h3>
    <p class="subtitle">Add and view your certifications in one place.</p>
    <form method="POST" class="form">
      <label>Certificate Name
        <input type="text" name="certificate_name" placeholder="Certificate Name" required>
      </label>
      <label>Issue Date
        <input type="date" name="issue_date" required>
      </label>
      <div class="form-actions">
        <button class="btn" type="submit" name="add_certification">Add Certification</button>
      </div>
    </form>

    <div class="section-divider"></div>
    <h3>Certification List</h3>
    <?php if($certifications && $certifications->num_rows): ?>
      <?php while($cert = $certifications->fetch_assoc()): ?>
        <article class="card">
          <h3><?php echo htmlspecialchars($cert['certificate_name']); ?></h3>
          <p class="meta">Issued on: <?php echo htmlspecialchars($cert['issue_date']); ?></p>
          <p class="meta">Added: <?php echo htmlspecialchars($cert['created_at']); ?></p>
          <div class="action-row">
            <form method="POST" onsubmit="return confirm('Delete this certification?');">
              <input type="hidden" name="cert_id" value="<?php echo (int)$cert['id']; ?>">
              <button class="btn secondary" type="submit" name="delete_certification">Delete</button>
            </form>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert">No certifications added yet.</div>
    <?php endif; ?>
  </article>
  <?php endif; ?>

  <article class="card">
    <h3>Session</h3>
    <p class="subtitle">Need to sign out from this device?</p>
    <div class="action-row">
      <a class="btn secondary" href="/youth-system/logout.php">Logout</a>
    </div>
  </article>
</section>

<?php include('includes/footer.php'); ?>
