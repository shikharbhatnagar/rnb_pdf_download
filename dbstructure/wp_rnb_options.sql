
CREATE TABLE `wp_rnb_options` (
  `noption_id` int(11) NOT NULL,
  `voption_title` varchar(50) NOT NULL,
  `noption_order` int(11) NOT NULL,
  `voption_type` varchar(50) NOT NULL,
  `vslug` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `wp_rnb_options` (`noption_id`, `voption_title`, `noption_order`, `voption_type`, `vslug`) VALUES
(1, 'Assignment 1 Format', 1, 'university', 'assignment1-format'),
(2, 'Assignment 2 Format ', 2, 'university', 'assignment2-format'),
(3, 'Assignment Format', 3, 'university', 'assignment-format'),
(4, 'Literature Review Format', 4, 'university', 'literature-review-format'),
(5, 'Progress Report Format', 5, 'university', 'progress-report-format'),
(7, 'Review  Format', 7, 'university', 'review-format'),
(8, 'Review Of Literature', 8, 'university', 'review-of-literature'),
(9, 'Review of Literature Format', 9, 'university', 'review-of-literature-format'),
(10, 'Synopsis Format', 10, 'university', 'synopsis-format'),
(11, 'Synopsis Guidelines', 11, 'university', 'synopsis-guidelines'),
(12, 'Synopsis Instructions', 12, 'university', 'synopsis-instructions'),
(13, 'Thesis Format', 13, 'university', 'thesis-format'),
(14, 'Thesis Guidelines', 14, 'university', 'thesis-guidelines'),
(15, 'review article', 1, 'course', 'review-article'),
(16, 'review of literature', 2, 'course', 'review-of-literature'),
(17, 'summary', 3, 'course', 'summary'),
(18, 'synopsis', 4, 'course', 'synopsis'),
(19, 'thesis', 5, 'course', 'thesis'),
(20, 'research article', 6, 'course', 'research-article'),
(22, 'review paper-1', 8, 'course', 'review-paper1'),
(23, 'review paper-2', 9, 'course', 'review-paper2'),
(24, 'research paper', 10, 'course', 'research-paper'),
(25, 'review paper', 11, 'course', 'review-paper');
