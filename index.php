<?php
require_once __DIR__ . '/includes/functions.php';

if (!is_logged_in()) {
    redirect('auth/login.php');
}

switch (current_role()) {
    case 'admin':
        redirect('dashboard/admin/index.php');
        break;
    case 'pemilik_kos':
        redirect('dashboard/pemilik/index.php');
        break;
    case 'pencari_kos':
    default:
        redirect('dashboard/pencari/index.php');
        break;
}