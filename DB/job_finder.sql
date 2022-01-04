-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 04, 2022 alle 02:25
-- Versione del server: 10.4.22-MariaDB
-- Versione PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_finder`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Log_in` (IN `mail` VARCHAR(48), IN `pass` VARCHAR(48), OUT `responseMessage` VARCHAR(64), OUT `ID` INT)  NO SQL
BEGIN
   DECLARE `_salt` BINARY(20);
   SET `ID`=NULL;

   SELECT LOWER(HEX(`Salt`)) INTO `_salt` FROM `credentials` WHERE BINARY `Email`=`mail`;
   IF (`_salt` IS NOT NULL)
   THEN
       SELECT `Code` INTO `ID` FROM `credentials` WHERE BINARY `Email`=`mail` AND BINARY `Passwd`=UNHEX(SHA1(CONCAT(`pass`,`_salt`)));
       IF(`ID` IS NULL) THEN
           SET `responseMessage`='wrong password';
       ELSE
           SET `responseMessage`='Success';
       END IF;

   ELSE
       SET `responseMessage`='Error';
   END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Register_new_user` (IN `mail` VARCHAR(48), IN `pass` VARCHAR(48))  NO SQL
BEGIN
	DECLARE `_salt` BINARY(20);
	SET `_salt`=SUBSTRING(MD5(RAND()), -24);
    INSERT INTO `credentials`(`Email`,`Passwd`, `Salt`) VALUES(`mail`, UNHEX(SHA1(CONCAT(`pass`, `_salt`))), UNHEX(`_salt`));
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_action`
--

CREATE TABLE `admin_action` (
  `Code` int(10) UNSIGNED NOT NULL,
  `Reference` int(10) UNSIGNED NOT NULL,
  `Ban Reason` varchar(256) DEFAULT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Admin` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `bids`
--

CREATE TABLE `bids` (
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Code_job` int(10) UNSIGNED NOT NULL,
  `User_price` int(10) UNSIGNED NOT NULL,
  `Bid_selfdescription` varchar(1200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `credentials`
--

CREATE TABLE `credentials` (
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Email` varchar(48) NOT NULL,
  `Passwd` binary(20) NOT NULL,
  `Salt` binary(12) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `current_jobs`
--

CREATE TABLE `current_jobs` (
  `Code_job` int(10) UNSIGNED NOT NULL,
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Date` datetime NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Description` varchar(4096) NOT NULL,
  `Status` enum('Frozen','Active','Expired','') NOT NULL,
  `Tipology` enum('Fulltime','Onetime','Urgent','Recruiter') DEFAULT NULL,
  `Payment` tinyint(1) NOT NULL,
  `P_min` int(6) NOT NULL,
  `P_max` int(6) NOT NULL,
  `Expiring` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `past_jobs`
--

CREATE TABLE `past_jobs` (
  `Code_job` int(10) UNSIGNED NOT NULL,
  `Code_user` int(10) UNSIGNED DEFAULT NULL,
  `Code_winner` int(10) UNSIGNED DEFAULT NULL,
  `Status` enum('Deleted','Froozen','Success','Unsuccess') NOT NULL,
  `Date` datetime NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Description` varchar(4096) NOT NULL,
  `Tipology` enum('Fulltime','Onetime','Urgent','Recruiter') DEFAULT NULL,
  `Payment` tinyint(1) NOT NULL,
  `P_min` int(6) NOT NULL,
  `P_max` int(6) NOT NULL,
  `Expiring_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `reviews`
--

CREATE TABLE `reviews` (
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Code_job` int(10) UNSIGNED NOT NULL,
  `Stars` int(1) UNSIGNED NOT NULL,
  `Comments` varchar(250) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `tags`
--

CREATE TABLE `tags` (
  `Code_tag` int(10) UNSIGNED NOT NULL,
  `Name` varchar(65) NOT NULL,
  `Category` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tags`
--

INSERT INTO `tags` (`Code_tag`, `Name`, `Category`) VALUES
(1, 'PHP', NULL),
(2, 'HTML', NULL),
(3, 'JavaScript', NULL),
(4, 'WordPress', NULL),
(5, 'Software Architecture', NULL),
(6, 'MySQL', NULL),
(7, 'Python', NULL),
(8, 'Java', NULL),
(9, 'SEO', NULL),
(10, 'C# Programming', NULL),
(11, 'Blockchain', NULL),
(12, 'Node.js', NULL),
(13, 'C Programming', NULL),
(14, 'HTML5', NULL),
(15, 'Linux', NULL),
(16, 'C++ Programming', NULL),
(17, 'React.js', NULL),
(18, 'Lavarel', NULL),
(19, 'Link Building', NULL),
(20, 'eCommerce', NULL),
(21, 'Web Scraping', NULL),
(22, 'Game Development', NULL),
(23, 'Unity 3D', NULL),
(24, 'Twitter', NULL),
(25, 'Flutter', NULL),
(26, 'System Admin', NULL),
(27, 'Shopify', NULL),
(28, 'Amazon Web Services', NULL),
(29, 'Non-Fungible Token (NFT)', NULL),
(30, 'Web Hosting', NULL),
(31, '.NET', NULL),
(32, 'AngularJS', NULL),
(33, 'Objective C', NULL),
(34, 'React Native', NULL),
(35, 'Game Design', NULL),
(36, 'Social Networking', NULL),
(37, 'Smart Contracts', NULL),
(38, 'Solidity', NULL),
(39, 'User Interface/IA', NULL),
(40, 'Ethernet', NULL),
(41, 'SQL', NULL),
(42, 'Software Testing', NULL),
(43, 'YouTube', NULL),
(44, 'Web Development', NULL),
(45, 'iOS Development', NULL),
(46, 'jQuery/Prototype', NULL),
(47, 'Network Administrator', NULL),
(48, 'Software Development', NULL),
(49, 'Mycrosoft SQL Server', NULL),
(50, 'Visual Basic', NULL),
(51, 'Django', NULL),
(52, 'ASP.NET', NULL),
(53, 'Digital Marketing', NULL),
(54, 'Google Plus', NULL),
(55, 'Website Management', NULL),
(56, 'Instagram', NULL),
(57, 'Website Testing', NULL),
(58, 'Web Security', NULL),
(59, 'Testing/QA', NULL),
(60, 'Swift', NULL),
(61, 'Google Firebase', NULL),
(62, 'Pinterest', NULL),
(63, 'Full Stack Development', NULL),
(64, 'Android App Development', NULL),
(65, 'Express JS', NULL),
(66, 'Windows Desktop', NULL),
(67, 'Codeigniter', NULL),
(68, 'Bitcoin', NULL),
(69, 'PostgreSQL', NULL),
(70, 'Ubuntu', NULL),
(71, 'API', NULL),
(72, 'Database Administration', NULL),
(73, 'Database Programming', NULL),
(74, 'Artificial Intelligence', NULL),
(75, 'Adobe Illustrator', NULL),
(76, 'Social Media Management', NULL),
(77, 'J2EE', NULL),
(78, 'Programming', NULL),
(79, 'Google Sheets', NULL),
(80, 'Windows Server', NULL),
(81, 'Vue.js', NULL),
(82, 'RESTful API', NULL),
(83, 'Computer Security', NULL),
(84, 'Cloud Computing', NULL),
(85, 'Typescript', NULL),
(86, 'Blender', NULL),
(87, 'Microsoft', NULL),
(88, 'Coding', NULL),
(89, 'Google Analytics', NULL),
(90, 'PhoneGap', NULL),
(91, 'Odoo', NULL),
(92, 'DNS', NULL),
(93, 'Database Development', NULL),
(94, 'MongoDB', NULL),
(95, 'Shopify Development', NULL),
(96, 'Unity', NULL),
(97, 'AJAX', NULL),
(98, 'Website Build', NULL),
(99, 'Web Application', NULL),
(100, 'Magento', NULL),
(101, 'Microsoft Access', NULL),
(102, 'Test Automation', NULL),
(103, 'Apache', NULL),
(104, 'Git', NULL),
(105, 'Spring Boot', NULL),
(106, 'Unreal Engine', NULL),
(107, 'Shopping Carts', NULL),
(108, 'Cisco', NULL),
(109, 'Xamarin', NULL),
(110, 'Google Cloud Platform', NULL),
(111, 'XML', NULL),
(112, 'VoIP', NULL),
(113, 'Docker', NULL),
(114, 'Payment Gateway Integration', NULL),
(115, 'Kubernetes', NULL),
(116, 'Angular', NULL),
(117, 'Next.js', NULL),
(118, 'Telegram API', NULL),
(119, 'JSP', NULL),
(120, 'Oracle', NULL),
(121, 'MVC', NULL),
(122, 'Google Maps API', NULL),
(123, '.NET Core', NULL),
(124, 'Visual Basic for Apps', NULL),
(125, 'Drawing', NULL),
(126, 'jQuery', NULL),
(127, 'Drupal', NULL),
(128, 'Adobe Premiere Pro', NULL),
(129, 'Backend Development', NULL),
(130, 'Rust', NULL),
(131, 'API Integration', NULL),
(132, 'ASP', NULL),
(133, 'Golang', NULL),
(134, 'Usability Testing', NULL),
(135, 'Dart', NULL),
(136, 'Linkedin', NULL),
(137, 'Ionic Framework', NULL),
(138, 'JSON', NULL),
(139, 'Raspberry Pi', NULL),
(140, 'AWS Lambda', NULL),
(141, 'Android Studio', NULL),
(142, 'Sharepoint', NULL),
(143, 'Nginx', NULL),
(144, 'Wix', NULL),
(145, 'Jenkins', NULL),
(146, 'Microsoft Azure', NULL),
(147, 'Binance Smart Chain', NULL),
(148, 'Solana', NULL),
(149, 'Ruby on Rails', NULL),
(150, 'Prestashop', NULL),
(151, 'OpenCV', NULL),
(152, 'CSS3', NULL),
(153, 'Frontend Development', NULL),
(154, 'Java Spring', NULL),
(155, 'Automation', NULL),
(156, 'Joomla', NULL),
(157, 'Microsoft Exchange', NULL),
(158, 'Google Chrome', NULL),
(159, 'Big Data Sales', NULL),
(160, 'Debian', NULL),
(161, 'Internet Security', NULL),
(162, 'Plugin', NULL),
(163, 'Computer Science', NULL),
(164, 'Web API', NULL),
(165, 'Elementor', NULL),
(166, 'Data Collection', NULL),
(167, 'Software Engineering', NULL),
(168, 'Google App Engine', NULL),
(169, 'Mac OS', NULL),
(170, 'Embedded Software', NULL),
(171, 'Shell Script', NULL),
(172, 'Mobile App Testing', NULL),
(173, 'Stripe', NULL),
(174, 'Lua', NULL),
(175, 'App Developer', NULL),
(176, 'Flask', NULL),
(177, 'Visual Studio', NULL),
(178, 'WebRTC', NULL),
(179, 'Desktop Application', NULL),
(180, 'Metamask', NULL),
(181, 'SAP', NULL),
(182, 'Selenium Webdriver', NULL),
(183, 'Data Visualization', NULL),
(184, 'Website Optimization', NULL),
(185, 'API Development', NULL),
(186, 'SSL', NULL),
(187, 'Terraform', NULL),
(188, 'Virtual Reality', NULL),
(189, 'UNIX', NULL),
(190, 'Assembly', NULL),
(191, 'Analytics', NULL),
(192, 'Ruby', NULL),
(193, 'RESTful', NULL),
(194, 'Augmented Reality', NULL),
(195, 'Copyright', NULL),
(196, 'Scripting', NULL),
(197, 'Apple Xcode', NULL),
(198, 'Server', NULL),
(199, 'Computer Vision', NULL),
(200, 'Magento 2', NULL),
(201, '.NET Core Web API', NULL),
(202, 'Binance', NULL),
(203, 'Angular 6', NULL),
(204, 'Freelancer API', NULL),
(205, 'Symfony PHP', NULL),
(206, 'WPF', NULL),
(207, 'Game Consoles', NULL),
(208, 'Email Developer', NULL),
(209, 'Mobile Development', NULL),
(210, 'ASP.NET MVC', NULL),
(211, 'Roblox', NULL),
(212, 'GraphQL', NULL),
(213, 'Adobe Acrobat', NULL),
(214, 'Android SDK ', NULL),
(215, 'VPN ', NULL),
(216, 'PayPal API', NULL),
(217, 'CakePHP', NULL),
(218, 'Hadoop', NULL),
(219, 'Moodle', NULL),
(220, 'Web Services', NULL),
(221, 'VB.NET', NULL),
(222, 'Tableau', NULL),
(223, 'Powershell', NULL),
(224, 'Facebook API', NULL),
(225, 'Geographical Information System (GIS)', NULL),
(226, 'Tensorflow', NULL),
(227, 'Power BI', NULL),
(228, 'Selenium', NULL),
(229, 'Map Reduce', NULL),
(230, 'MariaDB', NULL),
(231, 'VMware', NULL),
(232, 'Scrum', NULL),
(233, 'Oculus Rift', NULL),
(234, 'phpMyAdmin', NULL),
(235, 'Object Oriented Programming (OOP)', NULL),
(236, 'GitLab', NULL),
(237, 'Pytorch', NULL),
(238, 'HTTP', NULL),
(239, 'Xcodebuild', NULL),
(240, 'CentOs', NULL),
(241, 'ETL', NULL),
(242, 'D3.js', NULL),
(243, 'Google Docs', NULL),
(244, 'Slack', NULL),
(245, 'Network Engineering', NULL),
(246, 'A+ Certified IT Technician', NULL),
(247, 'Fusion 360', NULL),
(248, 'Certified Ethical Hacking', NULL),
(249, 'Reverse Engineering', NULL),
(250, 'Chatbot', NULL),
(251, 'Big Data', NULL),
(252, 'User Experience Research', NULL),
(253, 'Hybrid App', NULL),
(254, 'Photon Multiplayer ', NULL),
(255, 'JavaFX', NULL),
(256, 'Forum Software', NULL),
(257, 'IIS', NULL),
(258, 'CUDA', NULL),
(259, 'VPS', NULL),
(260, 'Socket IO', NULL),
(261, 'Red Hat', NULL),
(262, 'XMPP', NULL),
(263, 'Elasticsearch', NULL),
(264, 'Hive', NULL),
(265, 'MQTT', NULL),
(266, 'Google Cloud Storage', NULL),
(267, 'Storage Area Networks', NULL),
(268, 'Business Intelligence', NULL),
(269, 'Virtual Machines', NULL),
(270, 'Cinematography', NULL),
(271, 'XHTML', NULL),
(272, 'Office 365', NULL),
(273, 'Cloud', NULL),
(274, 'Cloud Networking', NULL),
(275, 'App Development', NULL),
(276, '.NET 5.0/6', NULL),
(277, 'Content Management System (CMS)', NULL),
(278, 'Google Data Studio', NULL),
(279, 'NLP', NULL),
(280, 'cPanel', NULL),
(281, 'Script Install', NULL),
(282, 'Delphi', NULL),
(283, 'Cocoa', NULL),
(284, 'OpenGL', NULL),
(285, 'x86/x64 Assembler', NULL),
(286, 'Redis', NULL),
(287, 'Bluetooth Low Energy (BLE)', NULL),
(288, 'Regular Expressions', NULL),
(289, 'Swing (Java)', NULL),
(290, 'OpenVPN', NULL),
(291, 'Web Crawling', NULL),
(292, 'Twitter API', NULL),
(293, 'Apache Maven', NULL),
(294, 'Website Analytics', NULL),
(295, 'BeautifulSoup', NULL),
(296, 'Charts', NULL),
(297, 'Sass', NULL),
(298, 'Twilio', NULL),
(299, 'Cloud Data', NULL),
(300, 'IT strategy', NULL),
(301, 'Amazon App Development', NULL),
(302, 'Google APIs', NULL),
(303, 'Agile Project Management', NULL),
(304, 'Three.js', NULL),
(305, 'Minecraft', NULL),
(306, 'P2P Network', NULL),
(307, 'Ansible', NULL),
(308, 'Blazor', NULL),
(309, 'PLC', NULL),
(310, 'Adobe Animate', NULL),
(311, 'Monday.com', NULL),
(312, 'Phaser', NULL),
(313, 'Perl', NULL),
(314, 'DotNetNuke', NULL),
(315, 'Electronic Forms', NULL),
(316, 'Active Directory', NULL),
(317, 'Chrome OS', NULL),
(318, 'XSLT', NULL),
(319, 'Dynamics', NULL),
(320, 'OCR', NULL),
(321, 'Computer Graphics', NULL),
(322, 'Windows API', NULL),
(323, 'Puppet', NULL),
(324, 'SQLite', NULL),
(325, 'Scheme', NULL),
(326, 'Growth Hacking', NULL),
(327, 'Snapchat', NULL),
(328, 'Heroku', NULL),
(329, 'Android Wear SDK', NULL),
(330, 'Visualization', NULL),
(331, 'Sketching', NULL),
(332, 'Netbeans', NULL),
(333, 'Qt', NULL),
(334, 'Scrapy', NULL),
(335, 'XAML', NULL),
(336, 'Push Notification', NULL),
(337, 'SSIS (SQL Server Integration Services)', NULL),
(338, 'Documentation', NULL),
(339, 'Datatables', NULL),
(340, 'SVG', NULL),
(341, 'Keras', NULL),
(342, 'Firmware', NULL),
(343, 'Angular Material', NULL),
(344, 'NumPy', NULL),
(345, 'MMORPG', NULL),
(346, 'Troubleshooting', NULL),
(347, 'Erlang', NULL),
(348, 'Expression Engine', NULL),
(349, 'Silverlight', NULL),
(350, 'Zend', NULL),
(351, 'Microsoft Expression', NULL),
(352, 'GPGPU', NULL),
(353, 'COBOL', NULL),
(354, 'Prolog', NULL),
(355, 'Fortran', NULL),
(356, 'Apache Solr', NULL),
(357, 'Pentaho', NULL),
(358, 'Solaris', NULL),
(359, 'Google Earth', NULL),
(360, 'AutoHotkey', NULL),
(361, 'Plesk', NULL),
(362, 'Firefox', NULL),
(363, 'Kinect', NULL),
(364, 'Smarty PHP', NULL),
(365, 'Yii', NULL),
(366, 'Apple Safari', NULL),
(367, 'Pattern Matching', NULL),
(368, 'Haskell', NULL),
(369, 'DOS', NULL),
(370, 'Google Checkout', NULL),
(371, 'Tumblr', NULL),
(372, 'Debugging', NULL),
(373, 'Google Web Toolkit', NULL),
(374, 'webMethods', NULL),
(375, 'OpenCL', NULL),
(376, '4D', NULL),
(377, 'Umbraco', NULL),
(378, 'Biztalk', NULL),
(379, 'Scala', NULL),
(380, 'backbone.js', NULL),
(381, 'Dthreejs', NULL),
(382, 'Knockout.js', NULL),
(383, 'CasperJS', NULL),
(384, 'BSD', NULL),
(385, 'edX', NULL),
(386, 'Lisp', NULL),
(387, 'XQuery', NULL),
(388, 'Data Warehousing', NULL),
(389, 'Call Control XML', NULL),
(390, 'TYPO3', NULL),
(391, 'IBM Websphere Transformation Tool', NULL),
(392, 'Sphinx', NULL),
(393, 'Adobe Air', NULL),
(394, 'Ember.js', NULL),
(395, 'WatchKit', NULL),
(396, 'Binary Analysis', NULL),
(397, 'GoPro', NULL),
(398, 'Grunt', NULL),
(399, 'HBase', NULL),
(400, 'Yarn', NULL),
(401, 'Cassandra', NULL),
(402, 'Spark', NULL),
(403, 'Bower', NULL),
(404, 'Samsung Accessory SDK', NULL),
(405, 'iBeacon', NULL),
(406, 'AMQP', NULL),
(407, 'Steam API', NULL),
(408, 'Redshift', NULL),
(409, 'CLIPS', NULL),
(410, 'Applescript', NULL),
(411, 'Apache Ant', NULL),
(412, 'LINQ', NULL),
(413, 'OAuth', NULL),
(414, 'OpenSSL', NULL),
(415, 'OpenStack', NULL),
(416, 'Subversion', NULL),
(417, 'Splunk', NULL),
(418, 'GIMP', NULL),
(419, 'Titanium', NULL),
(420, 'RapidWeaver', NULL),
(421, 'XPages', NULL),
(422, 'Grails', NULL),
(423, 'Ray-tracing', NULL),
(424, 'Parallel Processing', NULL),
(425, 'HTC Vive', NULL),
(426, 'Eclipse', NULL),
(427, 'cURL', NULL),
(428, 'Pascal', NULL),
(429, 'JUnit', NULL),
(430, 'Instagram API', NULL),
(431, 'Moz', NULL),
(432, 'ADO.NET', NULL),
(433, 'Graphics Programming', NULL),
(434, 'Bash Scripting', NULL),
(435, 'ARKit', NULL),
(436, 'jqGrid', NULL),
(437, 'Sails.js', NULL),
(438, 'Vim', NULL),
(439, 'F#', NULL),
(440, 'Jinja2', NULL),
(441, 'xpath', NULL),
(442, 'Racket', NULL),
(443, 'Dojo', NULL),
(444, 'Lucene', NULL),
(445, 'cxf', NULL),
(446, 'CoffeeScript', NULL),
(447, 'T-SQL (Transact Structures Query Language)', NULL),
(448, 'XSS (Cross-site scripting)', NULL),
(449, 'MapKit', NULL),
(450, 'Scikit Learn', NULL),
(451, 'Clojure', NULL),
(452, 'Elixir', NULL),
(453, 'VBScript', NULL),
(454, 'Ext JS', NULL),
(455, 'ECMAScript', NULL),
(456, 'Handlebars.js', NULL),
(457, 'Underscore.js', NULL),
(458, 'RSS', NULL),
(459, 'GTK+', NULL),
(460, 'Java ME', NULL),
(461, 'Enterprise Architecture', NULL),
(462, 'Apple UIKit', NULL),
(463, 'Solutions Architecture', NULL),
(464, 'Development Operations', NULL),
(465, 'DOM', NULL),
(466, 'RxJS', NULL),
(467, 'NgRx', NULL),
(468, 'Jasmine Javascript', NULL),
(469, 'Protractor Javascript', NULL),
(470, 'CocoaPods', NULL),
(471, 'NoSQL', NULL),
(472, 'Storm', NULL),
(473, 'Boost', NULL),
(474, 'Travis CI', NULL),
(475, 'TeamCity', NULL),
(476, 'CircleCI', NULL),
(477, 'ASM', NULL),
(478, 'Version Control Git', NULL),
(479, 'SciPy', NULL),
(480, 'Open Source', NULL),
(481, 'MuleSoft', NULL),
(482, 'Java Technical Architecture', NULL),
(483, 'NetSuite', NULL),
(484, 'Apache Hadoop', NULL),
(485, 'IT Operating Model', NULL),
(486, 'SAP Business Planning and Consolidation', NULL),
(487, 'Cloud Procurement', NULL),
(488, 'Oracle Database', NULL),
(489, 'Yii2', NULL),
(490, 'PHP Slim', NULL),
(491, 'Local Area Networking', NULL),
(492, 'Adobe Dynamic Tag Management', NULL),
(493, 'Google Tag Management', NULL),
(494, 'Segment', NULL),
(495, 'Facebook SDK', NULL),
(496, 'Server to Server Facebook API Integration', NULL),
(497, 'Offline Conversion Facebook API Integration', NULL),
(498, 'TvOS', NULL),
(499, 'Cocos2d', NULL),
(500, 'V-Play', NULL),
(501, 'Genetic Algebra Modelling System', NULL),
(502, 'Arena Simulation Programming', NULL),
(503, 'Test', NULL),
(504, 'Cloud Development', NULL),
(505, 'Cloud Security', NULL),
(506, 'Dropbox API', NULL),
(507, 'Certified Information Systems Security Professional (CISSP)', NULL),
(508, 'Digital Signal Processing', NULL),
(509, 'Highcharts', NULL),
(510, 'Dynamic 365', NULL),
(511, 'Business Central', NULL),
(512, 'Dynatrace Software Monitoring', NULL),
(513, 'Application Performance Monitoring', NULL),
(514, 'Microsoft Project', NULL),
(515, 'Alexa Modification', NULL),
(516, 'Prototyping', NULL),
(517, 'GitHub', NULL),
(518, 'Network Security', NULL),
(519, 'PostgreSQL Programming', NULL),
(520, 'Amazon S3', NULL),
(521, 'MEAN Stack', NULL),
(522, 'A-GPS', NULL),
(523, 'A/B Testing', NULL),
(524, 'A+ Certified Professional', NULL),
(525, 'Advanced Business Application Programming (ABAP)', NULL),
(526, 'ABIS', NULL),
(527, 'AJAX Frameworks', NULL),
(528, 'AJAX Toolkit', NULL),
(529, 'Akka', NULL),
(530, 'Algorithm Analysis', NULL),
(531, 'Alias', NULL),
(532, 'Alibre Design', NULL),
(533, 'All-Source Analysis', NULL),
(534, 'K2', NULL),
(535, 'Polarion', NULL),
(536, 'Redmine', NULL),
(537, 'Hardware Security Module', NULL),
(538, 'Keycloak', NULL),
(539, 'Atmel', NULL),
(540, 'Facebook Development', NULL),
(541, 'System Analysis', NULL),
(542, 'IMAP', NULL),
(543, 'POP / POP3', NULL),
(544, 'Postfix', NULL),
(545, 'SMTP', NULL),
(546, 'Application Packaging', NULL),
(547, 'Packaging Technology', NULL),
(548, 'PySpark', NULL),
(549, 'Oracle APEX', NULL),
(550, 'Houdini', NULL),
(551, 'Development', NULL),
(552, 'Architectural Engineering', NULL),
(553, 'COMPASS', NULL),
(554, 'Financial Software Development', NULL),
(555, 'IT Project Management', NULL),
(556, 'RPG Development', NULL),
(557, 'Electronic Data Interchange (EDI)', NULL),
(558, 'Google Canvas', NULL),
(559, 'Elm', NULL),
(560, 'Svelte', NULL),
(561, 'Reason', NULL),
(562, 'Altium Designer', NULL),
(563, 'Altium NEXUS', NULL),
(564, 'CircuitStudio', NULL),
(565, 'CircuitMaker', NULL),
(566, 'App Publication', NULL),
(567, 'RichFaces', NULL),
(568, 'Low Code', NULL),
(569, 'Twitch', NULL),
(570, 'Arc', NULL),
(571, 'Regression Testing', NULL),
(572, 'Adobe Audition', NULL),
(573, 'Adobe Creative Cloud', NULL),
(574, 'ArchiCAD', NULL),
(575, 'Forth', NULL),
(576, 'AutoCAD Advance Steel', NULL),
(577, 'Windows Service', NULL),
(578, 'Robot Operating System (ROS)', NULL),
(579, 'Cloudflare', NULL),
(580, 'Fedora', NULL),
(581, 'Point of Sale', NULL),
(582, 'Data Modeling', NULL),
(583, 'Predictive Analytics', NULL),
(584, 'PCI Compliance', NULL),
(585, 'BigQuery', NULL),
(586, 'SIP', NULL),
(587, 'Kibana', NULL),
(588, 'Bioinformatics', NULL),
(589, 'ArcGIS', NULL),
(590, 'Data Management', NULL),
(591, 'Bash', NULL),
(592, 'Apache Kafka', NULL),
(593, 'Firewall', NULL),
(594, 'Performance Tuning', NULL),
(595, 'Mule', NULL),
(596, 'Playstation VR', NULL),
(597, 'Apollo', NULL),
(598, 'Network Monitoring', NULL),
(599, 'Chromium', NULL),
(600, 'Teradata', NULL),
(601, 'Braintree', NULL),
(602, 'Data Backup', NULL),
(603, 'Hybris', NULL),
(604, 'Apache Spark', NULL),
(605, 'Backtesting', NULL),
(606, 'Binary Search', NULL),
(607, 'Cross Browser', NULL),
(608, 'LAMP', NULL),
(609, 'PayPal', NULL),
(610, 'ADF / Oracle ADF', NULL),
(611, 'Quality Engineering', NULL),
(612, 'Juniper', NULL),
(613, 'Celery', NULL),
(614, 'Gulp.js', NULL),
(615, 'Litecoin', NULL),
(616, 'Hyperledger', NULL),
(617, 'Hyperledger Fabric', NULL),
(618, 'Hugo', NULL),
(619, 'Amazon ECS', NULL),
(620, 'Citrix', NULL),
(621, 'Docker Compose', NULL),
(622, 'PHPUnit', NULL),
(623, 'DigitalOcean', NULL),
(624, 'Amazon CloudFront', NULL),
(625, 'Amazon CloudFormation', NULL),
(626, 'Cordana', NULL),
(627, 'Polkadot', NULL),
(628, 'Algogrand', NULL),
(629, 'Tether', NULL),
(630, 'Terra', NULL),
(631, 'Dogecoin', NULL),
(632, 'Etherscan', NULL),
(633, 'GeoJSON', NULL),
(634, 'Jitsi', NULL),
(635, 'Lottie', NULL),
(636, 'Micropython', NULL),
(637, 'Netlify', NULL),
(638, 'OKTA', NULL),
(639, 'OTT', NULL),
(640, 'Power Automate', NULL),
(641, 'Roslyn', NULL),
(642, 'Strapi', NULL),
(643, 'TRON', NULL),
(644, 'Weblogic', NULL),
(645, 'Wireguard', NULL),
(646, 'WMS', NULL),
(647, 'SurveyMonkey?', NULL),
(648, 'Powtoon', NULL),
(649, 'Chart.js', NULL),
(650, 'FoxyCart', NULL),
(651, 'Plivo', NULL),
(652, 'Moho', NULL),
(653, 'PyCaret', NULL),
(654, 'Pandas', NULL),
(655, 'AWS SageMaker', NULL),
(656, 'AWS Translate', NULL),
(657, 'AWS Textract', NULL),
(658, 'AWS Polly', NULL),
(659, 'GCP AI', NULL),
(660, 'Cloud Monitoring', NULL),
(661, 'Website Localization', NULL),
(662, 'App Localization', NULL),
(663, 'WordPress Multilingual', NULL),
(664, 'Draw.io', NULL),
(665, 'GrooveFunnels', NULL),
(666, 'Vulkan', NULL),
(667, 'DirectX', NULL),
(668, 'Metal', NULL),
(669, 'Amazon Listings Optimization', NULL),
(670, 'Vymo', NULL),
(671, 'Software Performance Testing', NULL),
(672, 'API Testing', NULL),
(673, 'JMeter', NULL),
(674, 'Adobe Analytics', NULL),
(675, 'CI/CD', NULL),
(676, 'Backbase', NULL),
(677, 'WINDEV', NULL),
(678, 'WEBDEV', NULL),
(679, 'WINDEV Mobile', NULL),
(680, 'CAN Bus', NULL),
(681, 'AutoML', NULL),
(682, 'Construct 3', NULL),
(683, 'GameMaker', NULL),
(684, 'Godot', NULL),
(685, 'PlayFab', NULL),
(686, 'Modding', NULL),
(687, 'Editorial Design', NULL),
(688, 'Google Apps Scripts', NULL),
(689, 'Spring Data', NULL),
(690, 'Spring Security', NULL),
(691, 'Spring JPA', NULL),
(692, 'Keyshot', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tags_current_jobs`
--

CREATE TABLE `tags_current_jobs` (
  `Code_job` int(10) UNSIGNED NOT NULL,
  `Code_tag` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `tags_past_jobs`
--

CREATE TABLE `tags_past_jobs` (
  `Code_job` int(10) UNSIGNED NOT NULL,
  `Code_tag` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `tags_users`
--

CREATE TABLE `tags_users` (
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Code_tag` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `Code_user` int(10) UNSIGNED NOT NULL,
  `Status` enum('Active','Inactive','Banned') NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Surname` varchar(64) NOT NULL,
  `Nickname` varchar(48) NOT NULL,
  `Birth` date NOT NULL,
  `Email` varchar(48) NOT NULL,
  `Nationality` varchar(56) NOT NULL,
  `City` varchar(45) NOT NULL,
  `Address` varchar(75) DEFAULT NULL,
  `Phone` int(11) DEFAULT NULL,
  `Picture` varchar(64) NOT NULL,
  `Curriculum` varchar(128) DEFAULT NULL,
  `Description` varchar(4096) NOT NULL,
  `Creation` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin_action`
--
ALTER TABLE `admin_action`
  ADD PRIMARY KEY (`Code`);

--
-- Indici per le tabelle `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`Code_user`,`Code_job`),
  ADD KEY `FK_Code_user` (`Code_user`) USING BTREE,
  ADD KEY `FK_Code_job` (`Code_job`) USING BTREE;

--
-- Indici per le tabelle `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`Code_user`),
  ADD UNIQUE KEY `Fk_login_email` (`Email`) USING BTREE;

--
-- Indici per le tabelle `current_jobs`
--
ALTER TABLE `current_jobs`
  ADD PRIMARY KEY (`Code_job`),
  ADD KEY `FK_current_job_creator` (`Code_user`) USING BTREE;

--
-- Indici per le tabelle `past_jobs`
--
ALTER TABLE `past_jobs`
  ADD PRIMARY KEY (`Code_job`),
  ADD KEY `FK_past_job_creator` (`Code_user`),
  ADD KEY `FK_past_job_winner` (`Code_winner`);

--
-- Indici per le tabelle `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`Code_user`,`Code_job`),
  ADD KEY `FK_reviews_job` (`Code_job`),
  ADD KEY `FK_reviews_user` (`Code_user`) USING BTREE;

--
-- Indici per le tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`Code_tag`);

--
-- Indici per le tabelle `tags_current_jobs`
--
ALTER TABLE `tags_current_jobs`
  ADD PRIMARY KEY (`Code_job`,`Code_tag`),
  ADD KEY `FK_tag_tags_current_job` (`Code_tag`);

--
-- Indici per le tabelle `tags_past_jobs`
--
ALTER TABLE `tags_past_jobs`
  ADD PRIMARY KEY (`Code_job`,`Code_tag`),
  ADD KEY `FK_tag_tags_past_job` (`Code_tag`);

--
-- Indici per le tabelle `tags_users`
--
ALTER TABLE `tags_users`
  ADD PRIMARY KEY (`Code_user`,`Code_tag`),
  ADD KEY `FK_tag_tags_users` (`Code_tag`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Code_user`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admin_action`
--
ALTER TABLE `admin_action`
  MODIFY `Code` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `credentials`
--
ALTER TABLE `credentials`
  MODIFY `Code_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tags`
--
ALTER TABLE `tags`
  MODIFY `Code_tag` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=693;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `Code_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `FK_bids_job` FOREIGN KEY (`Code_job`) REFERENCES `current_jobs` (`Code_job`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_bids_user` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`) ON DELETE CASCADE;

--
-- Limiti per la tabella `credentials`
--
ALTER TABLE `credentials`
  ADD CONSTRAINT `FK_login` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`),
  ADD CONSTRAINT `Fk_login_email` FOREIGN KEY (`Email`) REFERENCES `users` (`Email`);

--
-- Limiti per la tabella `current_jobs`
--
ALTER TABLE `current_jobs`
  ADD CONSTRAINT `FK_current_job_creator` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`);

--
-- Limiti per la tabella `past_jobs`
--
ALTER TABLE `past_jobs`
  ADD CONSTRAINT `FK_past_job_creator` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`),
  ADD CONSTRAINT `FK_past_job_winner` FOREIGN KEY (`Code_winner`) REFERENCES `users` (`Code_user`);

--
-- Limiti per la tabella `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_reviews_job` FOREIGN KEY (`Code_job`) REFERENCES `past_jobs` (`Code_job`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_reviews_user` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tags_current_jobs`
--
ALTER TABLE `tags_current_jobs`
  ADD CONSTRAINT `FK_job_tags_current_job` FOREIGN KEY (`Code_job`) REFERENCES `current_jobs` (`Code_job`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tag_tags_current_job` FOREIGN KEY (`Code_tag`) REFERENCES `tags` (`Code_tag`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tags_past_jobs`
--
ALTER TABLE `tags_past_jobs`
  ADD CONSTRAINT `FK_job_tags_past_job` FOREIGN KEY (`Code_job`) REFERENCES `past_jobs` (`Code_job`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tag_tags_past_job` FOREIGN KEY (`Code_tag`) REFERENCES `tags` (`Code_tag`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tags_users`
--
ALTER TABLE `tags_users`
  ADD CONSTRAINT `FK_job_tags_users` FOREIGN KEY (`Code_user`) REFERENCES `users` (`Code_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_tag_tags_users` FOREIGN KEY (`Code_tag`) REFERENCES `tags` (`Code_tag`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
