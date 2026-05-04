<?php
// FP5/includes/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_admin_logged_in(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        header("Location: /FP5/admin/login.php");
        exit;
    }
}