<?php
// Hàm làm sạch dữ liệu đầu vào
function clean_input($data) {
    return htmlspecialchars(trim($data));
}

// Hàm tạo mật khẩu băm (nếu cần)
function create_password_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Hàm xác minh mật khẩu
function verify_password($input, $hash) {
    return password_verify($input, $hash);
}
