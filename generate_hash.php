<?php
$password = 'Admin@KosinAja2024';
$hash = password_hash($password, PASSWORD_DEFAULT);
?>
<!DOCTYPE html>
<html>
<head><title>Hash Generator</title></head>
<body style="font-family: monospace; padding: 40px; background: #f5f5f5;">
    <h2>Hash Generator - KosinAja</h2>
    <p>Password: <b><?= $password ?></b></p>
    <p>Copy hash ke <code>sql/schema.sql</code>:</p>
    <textarea rows="3" style="width: 100%; padding: 10px; font-size: 13px;"><?= $hash ?></textarea>
    <p style="color: red; margin-top: 20px; font-weight: bold;">
        Jangan lupa untuk menghapus file <code>generate_hash.php</code> setelah selesai!
    </p>
</body>
</html>