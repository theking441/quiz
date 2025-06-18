<?php
// Trang thêm câu hỏi
require_once '../includes/config.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Kiểm tra đăng nhập và quyền quản trị
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập trang quản trị';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';
$importSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['question_text'])) {
        // Thêm từng câu hỏi thủ công
        $question_text = $_POST['question_text'];
        $option_a = clean_input($_POST['option_a']);
        $option_b = clean_input($_POST['option_b']);
        $option_c = clean_input($_POST['option_c']);
        $option_d = clean_input($_POST['option_d']);
        $correct_answer = $_POST['correct_answer'];
        $explanation = $_POST['explanation'] ?? '';
        $grade_level = intval($_POST['grade_level']);
        $topic = clean_input($_POST['topic']);
        $difficulty = clean_input($_POST['difficulty']);

        if (empty($question_text) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_answer) || $grade_level < 1 || $grade_level > 5) {
            $error = 'Vui lòng nhập đầy đủ thông tin hợp lệ.';
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO math_questions (question_text, option_a, option_b, option_c, option_d, correct_answer, explanation, grade_level, topic, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssssssss", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $explanation, $grade_level, $topic, $difficulty);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Đã thêm câu hỏi thành công!';
            } else {
                $error = 'Lỗi khi thêm câu hỏi: ' . mysqli_error($conn);
            }
        }
    } elseif (isset($_FILES['excel_file'])) {
        $file = $_FILES['excel_file']['tmp_name'];
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $count = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Bỏ qua tiêu đề
            list($question, $a, $b, $c, $d, $correct, $explanation, $grade, $topic, $difficulty) = array_pad($row, 10, null);

            if ($question && $a && $b && $c && $d && $correct) {
                $stmt = mysqli_prepare($conn, "INSERT INTO math_questions (question_text, option_a, option_b, option_c, option_d, correct_answer, explanation, grade_level, topic, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssssssss", $question, $a, $b, $c, $d, $correct, $explanation, $grade, $topic, $difficulty);
                if (mysqli_stmt_execute($stmt)) {
                    $count++;
                }
            }
        }
        $importSuccess = "Đã nhập thành công $count câu hỏi từ file Excel.";
    }
}

include 'includes/admin_header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Thêm câu hỏi mới</h1>
        <p class="lead">Tạo câu hỏi toán mới cho hệ thống hoặc nhập hàng loạt từ Excel.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="manage_questions.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><strong>Nhập câu hỏi từ file Excel</strong></div>
    <div class="card-body">
        <?php if ($importSuccess): ?><div class="alert alert-info"><?= $importSuccess ?></div><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tải file Excel</label>
                <input type="file" class="form-control" name="excel_file" accept=".xlsx,.xls" required>
            </div>
            <button type="submit" class="btn btn-success">Nhập từ Excel</button>
            <a href="mau_excel_cau_hoi.xlsx" class="btn btn-link">Tải file mẫu</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Thông tin câu hỏi</strong></div>
    <div class="card-body">
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Câu hỏi</label>
                <textarea name="question_text" class="form-control tinymce"><?= $_POST['question_text'] ?? '' ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Đáp án A</label>
                    <input type="text" class="form-control" name="option_a" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Đáp án B</label>
                    <input type="text" class="form-control" name="option_b" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Đáp án C</label>
                    <input type="text" class="form-control" name="option_c" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Đáp án D</label>
                    <input type="text" class="form-control" name="option_d" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Đáp án đúng</label>
                <select name="correct_answer" class="form-select" required>
                    <option value="">-- Chọn đáp án đúng --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Lời giải (Giải thích)</label>
                <textarea name="explanation" class="form-control tinymce"><?= $_POST['explanation'] ?? '' ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Lớp</label>
                    <select name="grade_level" class="form-select" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>">Lớp <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Chủ đề</label>
                    <input type="text" class="form-control" name="topic">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Độ khó</label>
                <select name="difficulty" class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="Dễ">Dễ</option>
                    <option value="Trung bình">Trung bình</option>
                    <option value="Khó">Khó</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Thêm câu hỏi</button>
            </div>
        </form>
    </div>
</div>

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

<?php include 'includes/admin_footer.php'; ?>