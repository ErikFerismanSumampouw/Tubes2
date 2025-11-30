<?php
session_start();
require_once 'config.php';
require_once 'log_action.php';

//Generate 6-digit OTP
$otp = '';
for ($i = 0; $i < 6; $i++) {
    $otp .= random_int(0, 9);
}

//Simpan OTP di session 
$_SESSION['generated_otp'] = $otp;

//Hanya login jika user ID tersedia
if (isset($_SESSION['temp_user_id_for_otp_verification'])) {
    @logAction($conn, $_SESSION['temp_user_id_for_otp_verification'], 'OTP dikirim', $otp);
}
header('Content-Type: text/plain');

//Output OTP 
echo $otp;
exit; 
?>