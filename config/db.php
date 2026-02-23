<?php
$conn = new mysqli("localhost", "root", "", "youth_skills_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>