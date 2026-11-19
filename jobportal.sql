-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 11:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `cv_id` int(10) UNSIGNED DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('Uus','Ülevaatamisel','Sobiv','Tagasi lükatud') NOT NULL DEFAULT 'Uus',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `cv_id`, `message`, `status`, `created_at`) VALUES
(1, 2, 1, NULL, 'Tere! Olen huvitatud sellest ametikohast.', 'Ülevaatamisel', '2026-11-19 01:44:51'),
(2, 2, 2, NULL, 'Tere! Soovin kandideerida.', 'Uus', '2026-11-19 01:44:51'),
(3, 6, 4, NULL, 'taehate', 'Uus', '2026-11-19 02:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Ehitus'),
(9, 'Finants ja raamatupidamine'),
(3, 'Haldus ja juhtimine'),
(7, 'Haridus'),
(1, 'IT ja arendus'),
(2, 'Müük ja klienditeenindus'),
(6, 'Tervishoid'),
(5, 'Transport ja logistika'),
(8, 'Turundus ja disain');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(190) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `name`, `description`, `logo`, `website`, `email`, `phone`, `city`, `created_at`) VALUES
(1, 3, 'TechSolutions OÜ', 'Kasvav tarkvaraarenduse ettevõte, mis loob veebi- ja mobiilirakendusi Eesti ja Põhjamaade klientidele.', NULL, 'https://techsolutions.example', 'tech@techsolutions.ee', NULL, 'Tallinn', '2026-11-19 01:44:51'),
(2, 4, 'DreamWorks OÜ', 'Digiagentuur, mis ehitab veebilehti ja e-poode ning pakub ka turundusteenuseid.', NULL, 'https://dreamworks.example', 'info@dreamworks.ee', NULL, 'Tartu', '2026-11-19 01:44:51'),
(3, 5, 'Nordic Tech AS', 'IT-infrastruktuuri ja tugiteenuste pakkuja ettevõtetele.', NULL, 'https://nordictech.example', 'hr@nordictech.ee', NULL, 'Tallinn', '2026-11-19 01:44:51');

-- --------------------------------------------------------

--
-- Table structure for table `cv_files`
--

CREATE TABLE `cv_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`user_id`, `job_id`, `created_at`) VALUES
(2, 1, '2026-11-19 01:44:51'),
(2, 3, '2026-11-19 01:44:51'),
(2, 4, '2026-11-19 01:44:51'),
(6, 4, '2026-11-19 01:53:09'),
(6, 5, '2026-11-19 01:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(190) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `salary_min` int(10) UNSIGNED DEFAULT NULL,
  `salary_max` int(10) UNSIGNED DEFAULT NULL,
  `employment_type` enum('Täistööaeg','Osaline tööaeg','Kaugtöö','Praktika') NOT NULL DEFAULT 'Täistööaeg',
  `start_date` varchar(80) NOT NULL DEFAULT 'Esimesel võimalusel',
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `company_id`, `category_id`, `location_id`, `title`, `description`, `requirements`, `salary_min`, `salary_max`, `employment_type`, `start_date`, `status`, `created_at`) VALUES
(1, 1, 1, 1, 'Java Arendaja', 'Otsime oma meeskonda kogenud Java arendajat, kes soovib töötada huvitavate projektidega ja areneda koos meiega.\n\nPeamised ülesanded:\n- Veebirakenduste arendus ja hooldus\n- Uute funktsionaalsuste loomine\n- Koodi ülevaatus ja dokumenteerimine', '- Vähemalt 3 aastat Java kogemust\n- Spring Boot ja SQL tundmine\n- Hea suhtlemisoskus eesti ja inglise keeles', 2500, 3500, 'Täistööaeg', 'Esimesel võimalusel', 'active', '2026-11-19 01:44:51'),
(2, 2, 1, 2, 'Veebiarendaja (PHP)', 'Ehitame kliendiprojekte PHP ja MySQL baasil. Ootame meeskonda arendajat, kes oskab töötada iseseisvalt.', '- PHP 8 ja MySQL kogemus\n- HTML, CSS, JavaScript\n- Git', 2000, 2800, 'Täistööaeg', 'Kokkuleppel', 'active', '2026-11-19 01:44:51'),
(3, 3, 1, 1, 'Süsteemiadministraator', 'Hoiame klientide IT-süsteemid töökorras. Pakume kasvuvõimalusi ja koolitusi.', '- Windows Server ja Linux kogemus\n- Võrgunduse alused', 1800, 2500, 'Täistööaeg', 'Esimesel võimalusel', 'active', '2026-11-19 01:44:51'),
(4, 1, 8, 6, 'UI/UX disainer', 'Kujundame kasutajasõbralikke rakendusi. Töö toimub kaugtööna.', '- Figma kogemus\n- Portfoolio', 2000, 3000, 'Kaugtöö', 'Kokkuleppel', 'active', '2026-11-19 01:44:51'),
(5, 2, 2, 2, 'Klienditeenindaja', 'Vastame klientide päringutele ja aitame neil leida sobivaid lahendusi.', '- Hea suhtlemisoskus\n- Eesti ja inglise keel', 1200, 1600, 'Osaline tööaeg', 'Esimesel võimalusel', 'active', '2026-11-19 01:44:51');

-- --------------------------------------------------------

--
-- Table structure for table `job_locations`
--

CREATE TABLE `job_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_locations`
--

INSERT INTO `job_locations` (`id`, `name`) VALUES
(6, 'Kaugtöö'),
(4, 'Narva'),
(3, 'Pärnu'),
(1, 'Tallinn'),
(2, 'Tartu'),
(5, 'Viljandi');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `body`, `is_read`, `created_at`) VALUES
(1, 6, 3, 'wthtwh', 1, '2026-11-19 02:01:50'),
(2, 6, 3, 'gg;og;', 1, '2026-11-19 02:01:53'),
(3, 3, 6, 'tsjzrsrtj', 0, '2026-11-19 02:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `text`, `link`, `is_read`, `created_at`) VALUES
(1, 2, 'Sinu kandideerimine „Java Arendaja\" on ülevaatamisel.', 'index.php?page=applications', 0, '2026-11-19 01:44:51'),
(2, 2, 'Tere tulemast JobPortali!', 'index.php?page=profile', 0, '2026-11-19 01:44:51'),
(3, 6, 'Tere tulemast JobPortali!', 'index.php?page=profile', 1, '2026-11-19 01:49:37'),
(4, 3, 'denis kandideeris ametikohale „UI/UX disainer\".', 'index.php?page=employer_candidates&job_id=4', 1, '2026-11-19 02:01:30'),
(5, 3, 'Uus sõnum: denis', 'index.php?page=thread&with=6', 1, '2026-11-19 02:01:50'),
(6, 3, 'Uus sõnum: denis', 'index.php?page=thread&with=6', 1, '2026-11-19 02:01:53'),
(7, 6, 'Uus sõnum: TechSolutions OÜ', 'index.php?page=thread&with=3', 1, '2026-11-19 02:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `reporter_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED DEFAULT NULL,
  `reason` text NOT NULL,
  `status` enum('open','resolved') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `k` varchar(60) NOT NULL,
  `v` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`k`, `v`) VALUES
('contact_email', 'info@jobportal.eeheatrha'),
('jobs_per_page', '10'),
('site_name', 'JobPortal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','employer','admin') NOT NULL DEFAULT 'user',
  `phone` varchar(40) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `city`, `about`, `status`, `created_at`) VALUES
(1, 'Administraator', 'admin@jobportal.ee', '$2y$10$RpniA5AXk05jNEgoO9kkiOCZN/xLIPwiWDjVZkEFnxnGnhRj./LVe', 'admin', NULL, 'Tallinn', NULL, 'active', '2026-11-19 01:44:51'),
(2, 'Denis Kask', 'denis@gmail.com', '$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.', 'user', NULL, 'Tallinn', NULL, 'active', '2026-11-19 01:44:51'),
(3, 'TechSolutions OÜ', 'tech@techsolutions.ee', '$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.', 'employer', NULL, 'Tallinn', NULL, 'active', '2026-11-19 01:44:51'),
(4, 'DreamWorks OÜ', 'info@dreamworks.ee', '$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.', 'employer', NULL, 'Tartu', NULL, 'active', '2026-11-19 01:44:51'),
(5, 'Nordic Tech AS', 'hr@nordictech.ee', '$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.', 'employer', NULL, 'Tallinn', NULL, 'active', '2026-11-19 01:44:51'),
(6, 'denis', 'denis@mail.com', '$2y$10$/y0Fbbdoo6z.tlsBk.FiDOn8Nn6daj30MMSrVDg9oUL0Tf72bUVS.', 'user', NULL, NULL, NULL, 'active', '2026-11-19 01:49:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_job` (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `cv_id` (`cv_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cv_files`
--
ALTER TABLE `cv_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `idx_jobs_status` (`status`),
  ADD KEY `idx_jobs_title` (`title`);

--
-- Indexes for table `job_locations`
--
ALTER TABLE `job_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`k`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cv_files`
--
ALTER TABLE `cv_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_locations`
--
ALTER TABLE `job_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`cv_id`) REFERENCES `cv_files` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cv_files`
--
ALTER TABLE `cv_files`
  ADD CONSTRAINT `cv_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jobs_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `job_locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
