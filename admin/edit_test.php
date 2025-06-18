<?php
// edit_test.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Truy cập bị từ chối';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = 'Bài kiểm tra không hợp lệ';
    header('Location: manage_tests.php');
    exit;
}

$test_id = (int) $_GET['id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $grade_level = (int) $_POST['grade_level'];
    $time_limit = (int) $_POST['time_limit'];

    if (!$title || !$grade_level || !$time_limit) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } else {
        $stmt = $conn->prepare("UPDATE tests SET title = ?, description = ?, grade_level = ?, time_limit = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $title, $description, $grade_level, $time_limit, $test_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Đã cập nhật bài kiểm tra thành công';
            $_SESSION['message_type'] = 'success';
            header("Location: view_test.php?id=$test_id");
            exit;
        } else {
            $error = 'Có lỗi xảy ra khi cập nhật.';
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$test = $stmt->get_result()->fetch_assoc();

include 'includes/admin_header.php';
?>
<div class="row mb-4">
  <div class="col-md-8">
    <h1>Chỉnh sửa bài kiểm tra</h1>
  </div>
  <div class="col-md-4 text-end">
    <a href="view_test.php?id=<?php echo $test_id; ?>" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="title" class="form-label">Tiêu đề</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($test['title']); ?>">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea name="description" class="form-control"><?php echo htmlspecialchars($test['description']); ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label for="grade_level" class="form-label">Lớp</label>
          <select name="grade_level" class="form-select">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?php echo $i; ?>" <?php if ($i == $test['grade_level']) echo 'selected'; ?>>Lớp <?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label for="time_limit" class="form-label">Thời gian (phút)</label>
          <input type="number" name="time_limit" class="form-control" value="<?php echo $test['time_limit']; ?>">
        </div>
      </div>
      <div class="mt-3 text-end">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Lưu thay đổi
        </button>
      </div>
    </form>
  </div>
</div>
<?php include 'includes/admin_footer.php'; ?>