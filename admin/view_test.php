<?php
// view_test.php
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
$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$test = $stmt->get_result()->fetch_assoc();

$qstmt = $conn->prepare("SELECT q.question_text FROM test_questions tq JOIN math_questions q ON tq.question_id = q.id WHERE tq.test_id = ? ORDER BY tq.question_order ASC");
$qstmt->bind_param("i", $test_id);
$qstmt->execute();
$questions = $qstmt->get_result();

include 'includes/admin_header.php';
?>
<div class="row mb-4">
  <div class="col-md-8">
    <h1>Xem bài kiểm tra</h1>
    <p class="lead">Chi tiết bài kiểm tra lớp <?php echo $test['grade_level']; ?></p>
  </div>
  <div class="col-md-4 text-end">
    <a href="manage_tests.php" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h4><?php echo htmlspecialchars($test['title']); ?></h4>
    <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($test['description']); ?></p>
    <p><strong>Thời gian:</strong> <?php echo $test['time_limit']; ?> phút</p>
    <p><strong>Số câu hỏi:</strong> <?php echo $questions->num_rows; ?></p>
    <hr>
    <h5>Câu hỏi:</h5>
    <ol>
      <?php while ($q = $questions->fetch_assoc()): ?>
        <li><?php echo htmlspecialchars($q['question_text']); ?></li>
      <?php endwhile; ?>
    </ol>
  </div>
</div>
<?php include 'includes/admin_footer.php'; ?>


<?php