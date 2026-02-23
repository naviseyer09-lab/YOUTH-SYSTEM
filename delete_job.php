<?php
include('includes/header.php');

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$user_role = $_SESSION['user_role'] ?? '';
$is_company = $user_role === 'company';
$job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;

if(!$user_id || !$is_company || $job_id <= 0){
    header('Location: /youth-system/my_jobs.php?status=forbidden');
    exit;
}

$stmt = $conn->prepare("DELETE FROM job_offers WHERE id = ? AND company_id = ?");
if($stmt){
    $stmt->bind_param('ii', $job_id, $user_id);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();

    if($deleted > 0){
        header('Location: /youth-system/my_jobs.php?status=deleted');
    } else {
        header('Location: /youth-system/my_jobs.php?status=notfound');
    }
    exit;
}

header('Location: /youth-system/my_jobs.php?status=error');
exit;
?>
