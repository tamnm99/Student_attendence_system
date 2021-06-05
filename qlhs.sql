-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 05, 2021 lúc 05:09 AM
-- Phiên bản máy phục vụ: 10.4.18-MariaDB
-- Phiên bản PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlhs`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_user_name` text COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_user_name`, `admin_password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `attendance_status` enum('Đi Học','Vắng Mặt') COLLATE utf8_unicode_ci NOT NULL,
  `attendance_date` date NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `attendance_status`, `attendance_date`, `teacher_id`) VALUES
(1, 1, 'Đi Học', '2021-03-01', 2),
(2, 2, 'Đi Học', '2021-03-01', 2),
(3, 3, 'Đi Học', '2021-03-01', 2),
(4, 4, 'Đi Học', '2021-03-01', 2),
(5, 6, 'Vắng Mặt', '2021-03-01', 2),
(20, 1, 'Vắng Mặt', '2021-03-02', 2),
(21, 2, 'Đi Học', '2021-03-02', 2),
(22, 3, 'Đi Học', '2021-03-02', 2),
(23, 4, 'Đi Học', '2021-03-02', 2),
(24, 6, 'Vắng Mặt', '2021-03-02', 2),
(25, 1, 'Đi Học', '2021-03-03', 2),
(26, 2, 'Đi Học', '2021-03-03', 2),
(27, 3, 'Đi Học', '2021-03-03', 2),
(28, 4, 'Đi Học', '2021-03-03', 2),
(29, 6, 'Đi Học', '2021-03-03', 2),
(30, 1, 'Đi Học', '2021-03-04', 2),
(31, 2, 'Đi Học', '2021-03-04', 2),
(32, 3, 'Vắng Mặt', '2021-03-04', 2),
(33, 4, 'Đi Học', '2021-03-04', 2),
(34, 6, 'Đi Học', '2021-03-04', 2),
(35, 1, 'Đi Học', '2021-03-05', 2),
(36, 2, 'Đi Học', '2021-03-05', 2),
(37, 3, 'Đi Học', '2021-03-05', 2),
(38, 4, 'Vắng Mặt', '2021-03-05', 2),
(39, 6, 'Vắng Mặt', '2021-03-05', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `grade`
--

INSERT INTO `grade` (`grade_id`, `grade_name`) VALUES
(1, '10A5'),
(2, '10A2'),
(3, '10A3'),
(4, '11A1'),
(6, '12A2'),
(7, '12A3'),
(8, '12A9'),
(9, '12A10'),
(15, '11A2'),
(16, '11A5'),
(17, '11A3');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `student_roll_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `student_dob` date NOT NULL,
  `grade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `student_roll_number`, `student_dob`, `grade_id`) VALUES
(1, 'Trần Trọng Hùng', '12A10_1', '2020-12-16', 9),
(2, 'Vũ Nhật Lâm', '12A10_2', '2021-02-05', 9),
(3, 'Nguyễn Minh Tuệ', '12A10_3', '2021-02-01', 9),
(4, 'Hoàng Đăng Khôi', '12A10_4', '2021-01-06', 9),
(6, 'Nguyễn Trọng Quang', '12A10_5', '2020-09-02', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_qualification` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `teacher_date_of_join` date NOT NULL,
  `teacher_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `grade_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_name`, `teacher_address`, `teacher_email`, `teacher_password`, `teacher_qualification`, `teacher_date_of_join`, `teacher_image`, `grade_id`) VALUES
(1, 'Nghiêm Thị Hương Ly', 'Tân Minh, Thường Tín, Hà Nội', 'huonglynt@gmail.com', '123', 'Thạc sỹ', '2021-02-01', 'huong_ly.jpg', 3),
(2, 'Nguyễn Mạnh Tâm', 'Đông Triều, Quảng Ninh', 'tamnm1999@gmail.com', '$2y$10$dUwl0wq.i56N.XP25OGqAO/VTgIGB64QqIq.sWdOeIUVUw52bmcM2', 'Đại Học', '2020-01-14', 'nguyen_manh_tam.jpg', 9),
(3, 'Nguyễn Thị Tú Chinh', 'Thọ Giáo, Tam Kỳ, Bắc Ninh', 'tuchinhnt@gmail.com', '123', 'Cao Đẳng', '2019-08-20', 'tu_chinh.jpg', 6),
(4, 'Đào Hồng Quân', 'Cẩm Giàng, Hải Dương', 'hongquandao@gmail.com', '123', 'Đại Học', '2021-02-01', 'hong_quan.jpg', 2),
(13, 'Nguyễn Thị Hồng Vẻ', 'Bắc Kinh, Quảng Nam, Thái Bình', 'hongvent@gmail.com', '$2y$10$KNVjo81Nq7NOp0e9d.mFyO.dOAw/XXzPOx.y6YBQASGltub2qYDDe', 'Tiến Sỹ', '2020-11-03', '60379f5aa3001.jpg', 15),
(16, 'Hoàng Thị Thúy Quỳnh', 'Đông Giao, Hồng Kông, Phú thọ', 'thuyquynhht@gmail.com', '$2y$10$dUwl0wq.i56N.XP25OGqAO/VTgIGB64QqIq.sWdOeIUVUw52bmcM2', 'Thạc sỹ', '2021-02-07', '607e2a2a437b6.jpg', 7);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Chỉ mục cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Chỉ mục cho bảng `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`);

--
-- Chỉ mục cho bảng `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Chỉ mục cho bảng `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`);

--
-- Các ràng buộc cho bảng `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);

--
-- Các ràng buộc cho bảng `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
