<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền thực hiện thao tác này';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = 'ID người dùng không hợp lệ';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_users.php');
    exit;
}

$user_id = intval($_GET['id']);

// Không cho phép xóa chính mình
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['message'] = 'Bạn không thể xóa chính mình';
    $_SESSION['message_type'] = 'warning';
    header('Location: manage_users.php');
    exit;
}

// Kiểm tra nếu tài khoản cần xóa là admin
$stmt = mysqli_prepare($conn, "SELECT is_admin FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    $_SESSION['message'] = 'Người dùng không tồn tại';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_users.php');
    exit;
}

if ($user['is_admin']) {
    $_SESSION['message'] = 'Không thể xóa tài khoản quản trị viên';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_users.php');
    exit;
}

// Tiến hành xóa
$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['message'] = 'Đã xóa người dùng thành công';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Không thể xóa người dùng: ' . mysqli_error($conn);
    $_SESSION['message_type'] = 'danger';
}

header('Location: manage_users.php');
exit;
