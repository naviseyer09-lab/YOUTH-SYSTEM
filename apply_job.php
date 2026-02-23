<?php
include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_youth = $user_role === 'youth';
$job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;

if(!$user_id){
    header('Location: /youth-system/login.php');
    exit;
}

if(!$is_youth){
    header('Location: /youth-system/jobs.php?apply=forbidden');
    exit;
}

if($job_id <= 0){
    header('Location: /youth-system/jobs.php?apply=invalid');
    exit;
}

$check_job = $conn->prepare("SELECT id FROM job_offers WHERE id = ? LIMIT 1");
if(!$check_job){
    header('Location: /youth-system/jobs.php?apply=error');
    exit;
}

$check_job->bind_param('i', $job_id);
$check_job->execute();
$check_job->store_result();
$exists = $check_job->num_rows > 0;
$check_job->close();

if(!$exists){
    header('Location: /youth-system/jobs.php?apply=invalid');
    exit;
}

$stmt = $conn->prepare("INSERT IGNORE INTO job_applications (job_id, user_id) VALUES (?, ?)");
if(!$stmt){
    header('Location: /youth-system/jobs.php?apply=error');
    exit;
}

$stmt->bind_param('ii', $job_id, $user_id);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();

if($affected > 0){
    header('Location: /youth-system/jobs.php?apply=success');
} else {
    header('Location: /youth-system/jobs.php?apply=duplicate');
}
exit;
?>
