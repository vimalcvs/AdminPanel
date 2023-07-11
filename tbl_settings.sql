-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 11, 2023 at 10:24 AM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u726159739_computer_hindi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `app_fcm_key` text NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `app_mandatory_login` tinyint(1) NOT NULL DEFAULT 1,
  `app_channel_grid` tinyint(1) NOT NULL DEFAULT 0,
  `app_name` varchar(50) NOT NULL,
  `app_logo` varchar(255) NOT NULL,
  `app_email` varchar(255) NOT NULL,
  `app_version` varchar(50) NOT NULL,
  `app_author` varchar(255) NOT NULL,
  `app_contact` varchar(255) NOT NULL,
  `app_website` varchar(255) NOT NULL,
  `app_developed_by` varchar(255) NOT NULL,
  `app_description` text NOT NULL,
  `app_privacy_policy` text NOT NULL,
  `publisher_id` varchar(255) NOT NULL,
  `interstital_ad` tinyint(1) NOT NULL DEFAULT 0,
  `interstital_ad_id` varchar(255) NOT NULL,
  `interstital_ad_click` varchar(255) NOT NULL,
  `banner_ad` tinyint(1) NOT NULL DEFAULT 0,
  `banner_ad_id` varchar(255) NOT NULL,
  `force_version_code` int(11) NOT NULL DEFAULT 1 COMMENT 'Must match with Android latest source code versionCode',
  `force_update` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Force Update or Optional Update',
  `force_title` varchar(50) NOT NULL DEFAULT 'New Version Available!',
  `force_message` text NOT NULL COMMENT 'Please download the latest version to continue using the app and get new features.',
  `force_yes_button` varchar(30) NOT NULL DEFAULT 'Update',
  `force_no_button` varchar(30) NOT NULL DEFAULT 'Exit' COMMENT 'if force_update YES, ''Exit'' otherwise ''Continue''',
  `force_source` varchar(100) NOT NULL DEFAULT 'Playstore' COMMENT 'Playstore / Server APK URL',
  `force_apk_link` varchar(255) DEFAULT NULL COMMENT 'Full APK path if Server URL selected',
  `youtube_dev_api` varchar(255) NOT NULL COMMENT 'You can follow this link : https://developers.google.com/youtube/android/player/register',
  `app_direction` varchar(3) NOT NULL DEFAULT 'rtl' COMMENT 'rtl or ltr',
  `createdAt` varchar(50) NOT NULL,
  `updatedAt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `app_fcm_key`, `api_key`, `app_mandatory_login`, `app_channel_grid`, `app_name`, `app_logo`, `app_email`, `app_version`, `app_author`, `app_contact`, `app_website`, `app_developed_by`, `app_description`, `app_privacy_policy`, `publisher_id`, `interstital_ad`, `interstital_ad_id`, `interstital_ad_click`, `banner_ad`, `banner_ad_id`, `force_version_code`, `force_update`, `force_title`, `force_message`, `force_yes_button`, `force_no_button`, `force_source`, `force_apk_link`, `youtube_dev_api`, `app_direction`, `createdAt`, `updatedAt`) VALUES
(1, 'AAAA6XwG4vE:APA91bHis2BEdJHLSrX8TKLm2KfI3C0qdTtdeBPZ1fEJenE41zk1OU5YzUiG_8P9mgULjN8Mh_FDxDfPEH7H1eFw_dVSNhZ1coRuxRKAZ1YPeTp6999coWgCotXNq6YWjn7fyJGl-nUk', 'd2b078b4-b1e8-4348-87f2-31bf58c8fc5b', 1, 0, 'Police Exam App', '6143-2023-07-07.png', 'technovimalin@gmail.com', '1.0.0', 'Vimal K. Vishwakarma ', '+91 9971770331', 'https://technovimal.in', 'TechnoVimal', '<p>This Application is the best application for Streaming Video, User can play their favorite videos through applications.</p>\r\n\r\n<ul>\r\n	<li><span dir=\"ltr\">Easy to stream video</span></li>\r\n	<li><span dir=\"ltr\">YouTube Video Supported</span></li>\r\n	<li><span dir=\"ltr\">Embedded video supported (DailyMotion, Open Load, Vimeo, Very Stream)</span></li>\r\n	<li><span dir=\"ltr\">Great Materialize Design</span></li>\r\n	<li><span dir=\"ltr\">Push Notification handling</span></li>\r\n	<li><span dir=\"ltr\">User Friendly</span></li>\r\n</ul>\r\n', '<p>Effective date: May 05, 2023</p>\r\n\r\n<p>BytesBee (&quot;us&quot;, &quot;we&quot;, or &quot;our&quot;) operates the http://www.technovimal.in website and the My Streaming App mobile application (the &quot;Service&quot;).</p>\r\n\r\n<p>This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service and the choices you have associated with that data.</p>\r\n\r\n<p>We use your data to provide and improve the Service. By using the Service, you agree to the collection and use of information under this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions.</p>\r\n\r\n<h2>Information Collection And Use</h2>\r\n\r\n<p>We collect several different types of information for various purposes to provide and improve our Service to you.</p>\r\n\r\n<h3>Types of Data Collected</h3>\r\n\r\n<h4>Personal Data</h4>\r\n\r\n<p>While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you (&quot;Personal Data&quot;). Personally, identifiable information may include, but is not limited to:</p>\r\n\r\n<ul>\r\n	<li>Email address</li>\r\n	<li>Cookies and Usage Data</li>\r\n</ul>\r\n\r\n<h4>Usage Data</h4>\r\n\r\n<p>We may also collect information that your browser sends whenever you visit our Service or when you access the Service by or through a mobile device (&quot;Usage Data&quot;).</p>\r\n\r\n<p>This Usage Data may include information such as your computer&#39;s Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>\r\n\r\n<p>When you access the Service by or through a mobile device, this Usage Data may include information such as the type of mobile device you use, your mobile device unique ID, the IP address of your mobile device, your mobile operating system, the type of mobile Internet browser you use, unique device identifiers and other diagnostic data.</p>\r\n\r\n<h4>Tracking &amp; Cookies Data</h4>\r\n\r\n<p>We use cookies and similar tracking technologies to track the activity on our Service and hold certain information.</p>\r\n\r\n<p>Cookies are files with a small amount of data which may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device. Tracking technologies also used are beacons, tags, and scripts to collect and track information and to improve and analyze our Service.</p>\r\n\r\n<p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>\r\n\r\n<p>Examples of Cookies we use:</p>\r\n\r\n<ul>\r\n	<li><strong>Session Cookies.</strong> We use Session Cookies to operate our Service.</li>\r\n	<li><strong>Preference Cookies.</strong> We use Preference Cookies to remember your preferences and various settings.</li>\r\n	<li><strong>Security Cookies.</strong> We use Security Cookies for security purposes.</li>\r\n</ul>\r\n\r\n<h2>Use of Data</h2>\r\n\r\n<p>BytesBee uses the collected data for various purposes:</p>\r\n\r\n<ul>\r\n	<li>To provide and maintain the Service</li>\r\n	<li>To notify you about changes to our Service</li>\r\n	<li>To allow you to participate in interactive features of our Service when you choose to do so</li>\r\n	<li>To provide customer care and support</li>\r\n	<li>To provide analysis or valuable information so that we can improve the Service</li>\r\n	<li>To monitor the usage of the Service</li>\r\n	<li>To detect, prevent and address technical issues</li>\r\n</ul>\r\n\r\n<h2>Transfer Of Data</h2>\r\n\r\n<p>Your information, including Personal Data, may be transferred to &mdash; and maintained on &mdash; computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from your jurisdiction.</p>\r\n\r\n<p>If you are located outside India and choose to provide information to us, please note that we transfer the data, including Personal Data, to India and process it there.</p>\r\n\r\n<p>Your consent to this Privacy Policy followed by your submission of such information represents your agreement to that transfer.</p>\r\n\r\n<p>BytesBee will take all steps reasonably necessary to ensure that your data is treated securely and under this Privacy Policy and no transfer of your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of your data and other personal information.</p>\r\n\r\n<h2>Disclosure Of Data</h2>\r\n\r\n<h3>Legal Requirements</h3>\r\n\r\n<p>BytesBee may disclose your Data in the good faith belief that such action is necessary to:</p>\r\n\r\n<ul>\r\n	<li>To comply with a legal obligation</li>\r\n	<li>To protect and defend the rights or property of BytesBee</li>\r\n	<li>To prevent or investigate possible wrongdoing in connection with the Service</li>\r\n	<li>To protect the personal safety of users of the Service or the public</li>\r\n	<li>To protect against legal liability</li>\r\n</ul>\r\n\r\n<h2>Security Of Data</h2>\r\n\r\n<p>The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>\r\n\r\n<h2>Service Providers</h2>\r\n\r\n<p>We may employ third party companies and individuals to facilitate our Service (&quot;Service Providers&quot;), to provide the Service on our behalf, to perform Service-related services or to assist us in analyzing how our Service is used.</p>\r\n\r\n<p>These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>\r\n\r\n<h2>Links To Other Sites</h2>\r\n\r\n<p>Our Service may contain links to other sites that are not operated by us. If you click on a third party link, you will be directed to that third party&#39;s site. We strongly advise you to review the Privacy Policy of every site you visit.</p>\r\n\r\n<p>We have no control over and assume no responsibility for the content, privacy policies, or practices of any third party sites or services.</p>\r\n\r\n<h2>Children&#39;s Privacy</h2>\r\n\r\n<p>Our Service does not address anyone under the age of 18 (&quot;Children&quot;).</p>\r\n\r\n<p>We do not knowingly collect personally identifiable information from anyone under the age of 18. If you are a parent or guardian and you are aware that your Children has provided us with Personal Data, please contact us. If we become aware that we have collected Personal Data from children without verification of parental consent, we take steps to remove that information from our servers.</p>\r\n\r\n<h2>Changes To This Privacy Policy</h2>\r\n\r\n<p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>\r\n\r\n<p>We will let you know via email and/or a prominent notice on our Service, before the change becoming effective and update the &quot;effective date&quot; at the top of this Privacy Policy.</p>\r\n\r\n<p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>\r\n\r\n<h2>Contact Us</h2>\r\n\r\n<p>If you have any questions about this Privacy Policy, please contact us:</p>\r\n\r\n<ul>\r\n	<li>By email: technovimalin@gmail.com</li>\r\n	<li>By visiting this page on our website: technovimal.in</li>\r\n</ul>\r\n', 'ca-app-pub-8676187581068146~7555857632', 1, 'ca-app-pub-8676187581068146/3979013620', '1', 1, 'ca-app-pub-8676187581068146/4373083108', 1, 1, 'New Version Available!', 'Please download the latest version to continue using the app and get new features.', 'Update', 'Exit', 'Playstore', '', 'AIzaSyBU19ln9W1W85olmtWIUZpGGLlY9JcOroA', 'LTR', '2018-10-20 15:00:00', '2023-07-11 09:19:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
