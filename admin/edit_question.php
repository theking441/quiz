<?php

$explanation = isset($_POST['explanation']) ? $_POST['explanation'] : '';

// Trang chỉnh sửa câu hỏi
require_once '../includes/config.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập.';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

// Lấy ID câu hỏi
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = 'ID câu hỏi không hợp lệ.';
    $_SESSION['message_type'] = 'warning';
    header('Location: manage_questions.php');
    exit;
}

$question_id = intval($_GET['id']);
$error = '';
$success = '';

// Lấy dữ liệu câu hỏi
$stmt = mysqli_prepare($conn, "SELECT * FROM math_questions WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$question = mysqli_fetch_assoc($result);

if (!$question) {
    $_SESSION['message'] = 'Không tìm thấy câu hỏi.';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_questions.php');
    exit;
}

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = $_POST['question_text'];
    $option_a = clean_input($_POST['option_a']);
    $option_b = clean_input($_POST['option_b']);
    $option_c = clean_input($_POST['option_c']);
    $option_d = clean_input($_POST['option_d']);
    $correct_answer = $_POST['correct_answer'];
    $grade_level = intval($_POST['grade_level']);
    $topic = clean_input($_POST['topic']);

    if (empty($question_text) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_answer)) {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE math_questions 
    SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?, grade_level = ?, topic = ?, explanation = ?
    WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssssssissi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $grade_level, $topic, $explanation, $question_id);

        
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Đã cập nhật câu hỏi thành công!';
            // Cập nhật dữ liệu cũ để hiển thị lại
            $question['question_text'] = $question_text;
            $question['option_a'] = $option_a;
            $question['option_b'] = $option_b;
            $question['option_c'] = $option_c;
            $question['option_d'] = $option_d;
            $question['correct_answer'] = $correct_answer;
            $question['grade_level'] = $grade_level;
            $question['topic'] = $topic;
        } else {
            $error = 'Lỗi khi cập nhật: ' . mysqli_error($conn);
        }
    }
}

// Header
include 'includes/admin_header.php';
?>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/vls5im4iq6n8zq6gxhsgiwgwgurmb8vu71uf4epliupc0wwt/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
  selector: 'textarea.tinymce',
  plugins: 'image link code table lists mathjax',
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | image link | code | mathjax',
  height: 400,
  mathjax: {
    lib: 'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js'
  },
  content_css: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'
});
</script>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Chỉnh sửa câu hỏi</h1>
        <p class="lead">Cập nhật nội dung câu hỏi toán.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="manage_questions.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Thông tin câu hỏi</strong></div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Câu hỏi</label>
                <textarea name="question_text" class="form-control tinymce"><?php echo htmlspecialchars($question['question_text']); ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Đáp án A</label>
                    <input type="text" class="form-control" name="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Đáp án B</label>
                    <input type="text" class="form-control" name="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Đáp án C</label>
                    <input type="text" class="form-control" name="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Đáp án D</label>
                    <input type="text" class="form-control" name="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Đáp án đúng</label>
                <select name="correct_answer" class="form-select" required>
                    <option value="">-- Chọn đáp án đúng --</option>
                    <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                        <option value="<?php echo $opt; ?>" <?php echo ($question['correct_answer'] == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Lớp</label>
                    <select name="grade_level" class="form-select" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($question['grade_level'] == $i) ? 'selected' : ''; ?>>Lớp <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Chủ đề</label>
					<div class="mb-3">
    <label class="form-label">Lời giải / Giải thích</label>
    <textarea name="explanation" class="form-control tinymce" rows="5"><?php echo htmlspecialchars($question['explanation']); ?></textarea>
   </div>
					
                    <input type="text" class="form-control" name="topic" value="<?php echo htmlspecialchars($question['topic']); ?>">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
