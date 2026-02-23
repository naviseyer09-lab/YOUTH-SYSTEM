<?php
// Dev helper: set a session user id for testing (do not use in production)
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test User';
echo "Session set for user_id=1. <a href='/youth-system/'>Go to site</a>";
