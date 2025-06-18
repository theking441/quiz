<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập trang quản trị';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

$selected_grade = isset($_GET['grade']) ? intval($_GET['grade']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];
$types = '';

if ($selected_grade >= 1 && $selected_grade <= 5) {
    $query .= " AND grade = ?";
    $params[] = $selected_grade;
    $types .= 'i';
}

if ($search !== '') {
    $query .= " AND (username LIKE ? OR first_name LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$query .= " ORDER BY grade ASC, username ASC";

$stmt = mysqli_prepare($conn, $query);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include 'includes/admin_header.php';
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Quản lý người dùng</h1>
    </div>
    <div class="col-md-6 text-end">
        <form method="get" class="d-flex">
            <select name="grade" class="form-select me-2">
                <option value="0">Tất cả lớp</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" <?= ($selected_grade == $i) ? 'selected' : '' ?>>Lớp <?= $i ?></option>
                <?php endfor; ?>
            </select>
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên hoặc tài khoản" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh sách người dùng</h5>
    </div>
    <div class="card-body table-responsive">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tài khoản</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Quản trị</th>
                        <th>Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['first_name']) ?></td>
                            <td><span class="badge bg-info">Lớp <?= $user['grade'] ?></span></td>
                            <td><?= $user['is_admin'] ? '<span class="badge bg-success">Có</span>' : '<span class="badge bg-secondary">Không</span>' ?></td>
                            <td>
                                <?php if ($user['profile_image']): ?>
                                    <img src="../images/<?= htmlspecialchars($user['profile_image']) ?>" alt="Avatar" class="rounded-circle" width="40" height="40">
                                <?php else: ?>
                                    <span class="text-muted">Không</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Không tìm thấy người dùng phù hợp.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
