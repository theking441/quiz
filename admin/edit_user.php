<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = 'Người dùng không hợp lệ';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_users.php');
    exit;
}

$user_id = intval($_GET['id']);
$error = '';
$success = '';

// Lấy thông tin người dùng
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
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

// Cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = clean_input($_POST['first_name']);
    $grade = intval($_POST['grade']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $stmt = mysqli_prepare($conn, "UPDATE users SET first_name = ?, grade = ?, is_admin = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "siii", $first_name, $grade, $is_admin, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $success = 'Cập nhật người dùng thành công!';
    } else {
        $error = 'Lỗi khi cập nhật: ' . mysqli_error($conn);
    }
}

include 'includes/admin_header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Chỉnh sửa người dùng</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="manage_users.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Tên hiển thị</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lớp</label>
                <select name="grade" class="form-select" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= ($user['grade'] == $i) ? 'selected' : '' ?>>Lớp <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="is_admin" id="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>>
                <label for="is_admin" class="form-check-label">Quản trị viên</label>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
