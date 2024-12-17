

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `userID` varchar(10) NOT NULL,
  `messageContents` text NOT NULL,
  `senderRole` enum('employer','job_seeker') NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `jobSeekerID` varchar(10) DEFAULT NULL,
  `employerID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

