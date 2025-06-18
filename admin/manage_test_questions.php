<?php
// Trang quản lý câu hỏi cho bài kiểm tra
require_once '../includes/config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    $_SESSION['message'] = 'Bạn không có quyền truy cập';
    $_SESSION['message_type'] = 'danger';
    header('Location: ../index.php');
    exit;
}

// Lấy ID bài kiểm tra
if (!isset($_GET['test_id']) || !is_numeric($_GET['test_id'])) {
    $_SESSION['message'] = 'ID bài kiểm tra không hợp lệ';
    $_SESSION['message_type'] = 'danger';
    header('Location: manage_tests.php');
    exit;
}
$test_id = intval($_GET['test_id']);

// Lấy thông tin bài kiểm tra
$test_stmt = mysqli_prepare($conn, "SELECT * FROM tests WHERE id = ?");
mysqli_stmt_bind_param($test_stmt, "i", $test_id);
mysqli_stmt_execute($test_stmt);
$test_result = mysqli_stmt_get_result($test_stmt);
$test = mysqli_fetch_assoc($test_result);

// Thêm câu hỏi vào bài kiểm tra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_questions']) && is_array($_POST['question_ids'])) {
        foreach ($_POST['question_ids'] as $question_id) {
            $stmt = mysqli_prepare($conn, "SELECT * FROM test_questions WHERE test_id = ? AND question_id = ?");
            mysqli_stmt_bind_param($stmt, "ii", $test_id, $question_id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($res) === 0) {
                // Thêm câu hỏi mới
                $order_stmt = mysqli_prepare($conn, "SELECT MAX(question_order) AS max_order FROM test_questions WHERE test_id = ?");
                mysqli_stmt_bind_param($order_stmt, "i", $test_id);
                mysqli_stmt_execute($order_stmt);
                $order_res = mysqli_stmt_get_result($order_stmt);
                $order_row = mysqli_fetch_assoc($order_res);
                $new_order = $order_row['max_order'] + 1;

                $insert_stmt = mysqli_prepare($conn, "INSERT INTO test_questions (test_id, question_id, question_order) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($insert_stmt, "iii", $test_id, $question_id, $new_order);
                mysqli_stmt_execute($insert_stmt);
            }
        }
        $_SESSION['message'] = 'Đã thêm câu hỏi thành công';
        $_SESSION['message_type'] = 'success';
        header("Location: manage_test_questions.php?test_id=$test_id");
        exit;
    }
    
    if (isset($_POST['shuffle'])) {
        $shuffle_stmt = mysqli_prepare($conn, "SELECT id FROM test_questions WHERE test_id = ?");
        mysqli_stmt_bind_param($shuffle_stmt, "i", $test_id);
        mysqli_stmt_execute($shuffle_stmt);
        $shuffle_res = mysqli_stmt_get_result($shuffle_stmt);
        $ids = [];
        while ($row = mysqli_fetch_assoc($shuffle_res)) {
            $ids[] = $row['id'];
        }
        shuffle($ids);
        foreach ($ids as $index => $id) {
            $order = $index + 1;
            mysqli_query($conn, "UPDATE test_questions SET question_order = $order WHERE id = $id");
        }
        $_SESSION['message'] = 'Đã trộn đề thành công';
        $_SESSION['message_type'] = 'success';
        header("Location: manage_test_questions.php?test_id=$test_id");
        exit;
    }

    if (isset($_POST['random_10'])) {
        $grade_level = $test['grade_level'];
        $random_sql = "SELECT id FROM math_questions WHERE grade_level = $grade_level AND id NOT IN (SELECT question_id FROM test_questions WHERE test_id = $test_id) ORDER BY RAND() LIMIT 10";
        $random_res = mysqli_query($conn, $random_sql);
        while ($row = mysqli_fetch_assoc($random_res)) {
            $insert = mysqli_prepare($conn, "INSERT INTO test_questions (test_id, question_id, question_order) VALUES (?, ?, ?)");
            $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(question_order) AS max_order FROM test_questions WHERE test_id = $test_id"))['max_order'] + 1;
            mysqli_stmt_bind_param($insert, "iii", $test_id, $row['id'], $order);
            mysqli_stmt_execute($insert);
        }
        $_SESSION['message'] = 'Đã tạo ngẫu nhiên 10 câu hỏi';
        $_SESSION['message_type'] = 'success';
        header("Location: manage_test_questions.php?test_id=$test_id");
        exit;
    }
}

// Lấy danh sách câu hỏi theo lớp
$questions = mysqli_query($conn, "SELECT * FROM math_questions WHERE grade_level = " . intval($test['grade_level']) . " ORDER BY id DESC");

// Lấy các câu hỏi đã thêm
$added_result = mysqli_query($conn, "SELECT question_id FROM test_questions WHERE test_id = $test_id");
$added_ids = [];
while ($row = mysqli_fetch_assoc($added_result)) {
    $added_ids[] = $row['question_id'];
}

include 'includes/admin_header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Quản lý câu hỏi cho bài: <?php echo htmlspecialchars($test['title']); ?></h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="manage_tests.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<form method="POST">
    <div class="mb-3">
        <button type="submit" name="random_10" class="btn btn-outline-primary" onclick="return confirm('Bạn có chắc muốn tạo ngẫu nhiên 10 câu hỏi không?');">
            <i class="fas fa-random"></i> Tạo ngẫu nhiên 10 câu hỏi
        </button>
        <button type="submit" name="shuffle" class="btn btn-outline-warning" onclick="return confirm('Bạn có chắc muốn trộn đề không?');">
            <i class="fas fa-exchange-alt"></i> Trộn đề (đảo thứ tự)
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Chọn câu hỏi thêm vào bài kiểm tra</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Câu hỏi</th>
                            <th>Chủ đề</th>
                            <th>Lớp</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($q = mysqli_fetch_assoc($questions)): ?>
                            <tr>
                                <td>
                                    <?php if (in_array($q['id'], $added_ids)): ?>
                                        <span class="badge bg-success">Đã thêm</span>
                                    <?php else: ?>
                                        <input type="checkbox" name="question_ids[]" value="<?php echo $q['id']; ?>">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($q['question_text']); ?></td>
                                <td><?php echo htmlspecialchars($q['topic']); ?></td>
                                <td>Lớp <?php echo $q['grade_level']; ?></td>
                                <td>
                                    <?php echo in_array($q['id'], $added_ids) ? '<i class="fas fa-check text-success"></i>' : ''; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" name="add_questions" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Thêm vào bài kiểm tra
            </button>
        </div>
    </div>
</form>

<?php include 'includes/admin_footer.php'; ?>
