SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_role` enum('job_seeker','employer') NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `messages` (`id`, `sender_role`, `message`, `timestamp`) VALUES
(185, 'job_seeker', 'Hi', '2024-11-10 15:40:18'),
(186, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 15:40:19'),
(187, 'job_seeker', 'My name is Tam', '2024-11-10 15:40:27'),
(188, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 15:40:28'),
(189, 'job_seeker', 'What is your job?', '2024-11-10 15:40:44'),
(190, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 15:40:45'),
(191, 'job_seeker', 'can u tell me the details of your job?', '2024-11-10 15:41:02'),
(192, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 15:41:03'),
(193, 'job_seeker', 'what time?', '2024-11-10 15:41:19'),
(194, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 15:41:20'),
(195, 'employer', 'Hi', '2024-11-10 15:41:31'),
(196, 'job_seeker', 'Thank you for your message. I will get back to you soon!', '2024-11-10 15:41:32'),
(197, 'employer', 'I m Jia', '2024-11-10 15:41:39'),
(198, 'job_seeker', 'Thank you for your message. I will get back to you soon!', '2024-11-10 15:41:40'),
(199, 'job_seeker', 'Hi, how are you?', '2024-11-10 16:03:27'),
(200, 'employer', 'Thank you for your message. We will get back to you soon!', '2024-11-10 16:03:28'),
(201, 'employer', 'Hi, what can i help you?', '2024-11-10 16:09:54'),
(202, 'job_seeker', 'Thank you for your message. I will get back to you soon!', '2024-11-10 16:09:55');

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;
COMMIT;
