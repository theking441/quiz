<?php
require_once '../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../login.php');
    exit;
}

$page_title = 'Xem câu hỏi';

$question_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($question_id <= 0) {
    $_SESSION['message'] = 'ID không hợp lệ';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_questions.php');
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM math_questions WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Không tìm thấy câu hỏi';
    $_SESSION['message_type'] = 'warning';
    header('Location: manage_questions.php');
    exit;
}

$question = mysqli_fetch_assoc($result);
include 'includes/admin_header.php'; // Header đồng bộ
?>

<div class="row mb-4">
  <div class="col-md-8">
    <h1>Xem chi tiết câu hỏi</h1>
    <p class="lead">Thông tin chi tiết về câu hỏi đã chọn.</p>
  </div>
  <div class="col-md-4 text-end">
    <a href="manage_questions.php" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header"><strong>Câu hỏi lớp <?php echo $question['grade_level']; ?></strong></div>
  <div class="card-body">
    <p><strong>Câu hỏi:</strong> <?php echo $question['question_text']; ?></p>
    <ul>
      <li>A. <?php echo $question['option_a']; ?></li>
      <li>B. <?php echo $question['option_b']; ?></li>
      <li>C. <?php echo $question['option_c']; ?></li>
      <li>D. <?php echo $question['option_d']; ?></li>
    </ul>
    <p><strong>Đáp án đúng:</strong> <?php echo $question['correct_answer']; ?></p>
    <p><strong>Lời giải:</strong> <?php echo $question['explanation']; ?></p>
  </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
