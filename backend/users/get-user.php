<?php
header('Content-Type: application/json');
require_once '../middleware/auth.php';
requireAuth();
require_once 'profile.php';
?>