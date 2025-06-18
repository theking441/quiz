-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th6 18, 2025 lúc 12:13 PM
-- Phiên bản máy phục vụ: 10.6.20-MariaDB-cll-lve-log
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `vdokyho_mathdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `badges`
--

INSERT INTO `badges` (`id`, `name`, `description`, `image_path`) VALUES
(1, 'Nhà toán học tập sự', 'Hoàn thành bài kiểm tra đầu tiên', 'badges/beginner.png'),
(2, 'Siêu sao toán học', 'Đạt điểm 10/10 trong bài kiểm tra', 'badges/star.png'),
(3, 'Tia chớp', 'Hoàn thành bài kiểm tra trong thời gian ngắn', 'badges/lightning.png'),
(4, 'Người kiên trì', 'Hoàn thành 5 bài kiểm tra', 'badges/persistent.png'),
(5, 'Bậc thầy toán học', 'Đạt điểm tối đa trong 3 bài kiểm tra liên tiếp', 'badges/master.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `math_questions`
--

CREATE TABLE `math_questions` (
  `id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct_answer` char(1) NOT NULL COMMENT 'A, B, C hoặc D',
  `explanation` text DEFAULT NULL,
  `grade_level` int(11) NOT NULL COMMENT 'Lớp 1-5',
  `difficulty` enum('easy','medium','hard') NOT NULL,
  `topic` varchar(50) NOT NULL COMMENT 'phép cộng, phép trừ, ...',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `math_questions`
--

INSERT INTO `math_questions` (`id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `explanation`, `grade_level`, `difficulty`, `topic`, `created_at`, `updated_at`) VALUES
(1, '5 + 3 = ?', '7', '8', '9', '10', 'B', '5 cộng với 3 bằng 8', 1, 'easy', 'phép cộng', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(2, '10 - 4 = ?', '4', '5', '6', '7', 'C', '10 trừ đi 4 bằng 6', 1, 'easy', 'phép trừ', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(3, '2 + 2 + 2 = ?', '4', '6', '8', '10', 'B', '2 cộng 2 cộng 2 bằng 6', 1, 'easy', 'phép cộng', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(4, '5 + 5 = ?', '5', '10', '15', '20', 'B', '5 cộng với 5 bằng 10', 1, 'easy', 'phép cộng', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(5, '8 - 3 = ?', '3', '4', '5', '6', 'C', '8 trừ đi 3 bằng 5', 1, 'easy', 'phép trừ', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(6, '12 + 8 = ?', '18', '19', '20', '21', 'C', '12 cộng với 8 bằng 20', 2, 'easy', 'phép cộng', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(7, '15 - 7 = ?', '5', '6', '7', '8', 'D', '15 trừ đi 7 bằng 8', 2, 'easy', 'phép trừ', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(8, '3 × 4 = ?', '7', '10', '12', '15', 'C', '3 nhân với 4 bằng 12', 2, 'medium', 'phép nhân', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(9, '10 ÷ 2 = ?', '4', '5', '6', '8', 'B', '10 chia cho 2 bằng 5', 2, 'medium', 'phép chia', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(10, '9 + 9 = ?', '16', '17', '18', '19', 'C', '9 cộng với 9 bằng 18', 2, 'easy', 'phép cộng', '2025-05-25 17:42:36', '2025-05-25 17:42:36'),
(12, 'Một chong chóng có 4 cánh. Ba chong chóng như thế có số cánh là:', '8 cánh', '12 cánh', '16 cánh', '20 cánh', 'B', '3 chong chóng như thế có số cánh là:\r\n              3 x 4 = 12 (cánh)\r\n                     Đáp số: 12 cánh', 3, 'easy', 'nhân 4', '2025-06-14 19:12:56', '2025-06-14 22:05:15'),
(13, 'Hoàn thành dãy số sau:', '4', '8', '26', '28', 'D', 'Ta có: \r\n4 x 5 = 20     \r\n4 x 6 = 24       \r\n4 x 8 = 32\r\n4 x 9 = 36\r\nVậy số cần điền vào ô trống là: 4 x 7 = 28', 3, 'easy', 'nhân 4', '2025-06-14 19:19:30', '2025-06-14 22:05:42'),
(14, 'Một ô tô con có 4 bánh xe. Hỏi 10 ô tô con như thế có bao nhiêu bánh xe?', '20 bánh xe', '32 bánh xe', '40 bánh xe', '28 bánh xe', 'C', 'Đáp án đúng là: C\r\n10 ô tô con như thế có số bánh xe là:\r\n4 x 10 = 40 (bánh)\r\nĐáp số: 40 bánh xe', 3, '', 'nhân 4', '2025-06-14 20:31:19', '2025-06-14 21:24:56'),
(15, 'Có 28 chiếc bánh chia đều vào 4 hộp, hỏi mỗi hộp có bao nhiêu chiếc bánh?', '4', '5', '6', '7', 'D', 'Đáp án đúng là: D\r\nMỗi hộp có số chiếc bánh là: \r\n28 : 4 = 7 (chiếc)\r\nĐáp số: 7 chiếc bánh', 3, 'easy', 'nhân 4', '2025-06-14 20:42:20', '2025-06-14 22:06:01'),
(17, 'Quả địa cầu có dạng hình gì?', 'Hình tròn', 'Hình cầu', 'Hình hộp chữ nhật', 'Hình lập phương', 'B', 'Đáp án đúng là: B\r\nQuả địa cầu có dạng hình cầu', 3, 'easy', 'hình học và đo lường', '2025-06-14 21:39:41', '2025-06-14 22:06:08'),
(18, 'Nếu ngày 19 – 3 – 2022 vào thứ Bảy, thì ngày 25 – 3 – 2022 vào thứ mấy?', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'D', 'Đáp án đúng là: D\r\nNgày 19 – 3 – 2022 vào thứ Bảy. \r\nThì 7 ngày sau đó, tức ngày 26 – 3 – 2022 cũng sẽ vào thứ Bảy.\r\nVậy ngày 25 – 3 – 2022 vào thứ Sáu.', 3, 'easy', 'hình học và đo lường', '2025-06-14 21:40:47', '2025-06-14 22:06:29'),
(19, 'Một tuần gia đình bạn Hoa ăn hết 5 kg gạo. Mẹ Hoa đi chợ mua 20 kg gạo thì sẽ ăn được trong bao lâu?', '4 tuần', '3 tuần', '2 tuần', '1 tuần', 'A', 'Đáp án đúng là: A\r\nMẹ Hoa đi chợ mua 20 ki-lô-gam gạo thì sẽ ăn được trong khoảng thời gian là:\r\n20 : 5 = 4 (tuần)\r\nĐáp số: 4 tuần', 3, 'easy', 'hình học và đo lường', '2025-06-14 21:41:39', '2025-06-14 22:06:50'),
(20, 'Nhận xét nào sau đây không đúng?', 'Trong một hình tròn, các bán kính có độ dài bằng nhau', 'Trong một hình tròn, độ dài bán kính bằng độ dài đường kính', 'Trong một hình tròn, các đường kính có độ dài bằng nhau', 'Trong một hình tròn, độ dài đường kính lớn hơn độ dài bán kính', 'B', 'Đáp án đúng là: B\r\nTrong một hình tròn, độ dài của đường kính gấp 2 lần độ dài của bán kính', 3, 'easy', 'Tâm, bán kính, đường kính của hình tròn', '2025-06-14 21:43:06', '2025-06-14 22:04:59'),
(21, 'Điền số thích hợp vào ô trống', '1', '10', '100', '1000', 'A', 'Đáp án đúng là: A\r\n10 mm = 1 cm', 3, 'easy', 'mm', '2025-06-14 21:46:58', '2025-06-14 22:04:49'),
(22, 'Bạn ve sầu có chiều dài 3 cm; bạn kiến có chiều dài là 3 mm. Hỏi bạn nào ngắn hơn?', 'Bạn ve sầu', 'Bạn kiến', 'Cả hai bạn có chiều dài bằng nhau', 'Không so sánh được', 'B', 'Đáp án đúng là: B\r\nBạn ve sầu có chiều dài 3 cm.\r\nBạn kiến có chiều dài là 3 mm.\r\nTa có: 3 cm = 30 mm\r\nDo 3 mm < 30 mm nên bạn kiến ngắn hơn bạn ve sầu.', 3, '', 'mm', '2025-06-14 21:48:39', '2025-06-14 21:49:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `grade_level` int(11) NOT NULL COMMENT 'Lớp 1-5',
  `time_limit` int(11) NOT NULL COMMENT 'Thời gian làm bài (phút)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `question_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `score` float NOT NULL COMMENT 'Điểm số trên 10',
  `completion_time` int(11) NOT NULL COMMENT 'Thời gian hoàn thành (giây)',
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `grade` int(11) NOT NULL COMMENT 'Lớp 1-5',
  `school_name` varchar(100) DEFAULT NULL,
  `parent_phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default_avatar.png',
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `grade`, `school_name`, `parent_phone`, `profile_image`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$hlZiyVRZpTgIDj5w7Ljt4uIJYBs9DEjQVv5ThupDz8yoUImoEcoky', 'Bảo', 'Vệ', 5, '', '0911813814', 'default_avatar.png', 1, '2025-05-25 17:42:36', '2025-06-15 13:29:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `test_result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_answer` char(1) DEFAULT NULL COMMENT 'A, B, C hoặc D',
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `math_questions`
--
ALTER TABLE `math_questions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_result_id` (`test_result_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Chỉ mục cho bảng `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `math_questions`
--
ALTER TABLE `math_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `math_questions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `test_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`test_result_id`) REFERENCES `test_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `math_questions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
