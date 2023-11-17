
CREATE TABLE `wp_rnb_category` (
  `ncategory_id` int(11) NOT NULL,
  `vcategory_title` varchar(50) NOT NULL,
  `vslug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `wp_rnb_category` (`ncategory_id`, `vcategory_title`, `vslug`) VALUES
(1, 'arts', 'arts'),
(2, 'basic science', 'basic-science'),
(3, 'commerce', 'commerce'),
(4, 'engineering and technology', 'engineering-and-technology'),
(5, 'humanities', 'humanities'),
(6, 'life sciences', 'life-sciences'),
(7, 'management', 'management'),
(8, 'science', 'science'),
(9, 'education', 'education'),
(10, 'mathematics', 'mathematics'),
(11, 'pharmacy', 'pharmacy'),
(12, 'physiotherapy', 'physiotherapy'),
(13, 'medical science', 'medical-science'),
(14, 'agriculture', 'agriculture');
