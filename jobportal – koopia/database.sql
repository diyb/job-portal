-- JobPortal andmebaas (MySQL 8 / MariaDB 10.4+)
-- Import: phpMyAdmin -> Import, või: mysql -u root < database.sql

CREATE DATABASE IF NOT EXISTS jobportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobportal;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS settings, notifications, reports, messages, favorites, applications, cv_files, jobs, job_locations, categories, companies, users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','employer','admin') NOT NULL DEFAULT 'user',
  phone VARCHAR(40) NULL,
  city VARCHAR(80) NULL,
  about TEXT NULL,
  status ENUM('active','blocked') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE companies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  logo VARCHAR(255) NULL,
  website VARCHAR(190) NULL,
  email VARCHAR(190) NULL,
  phone VARCHAR(40) NULL,
  city VARCHAR(80) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE job_locations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  company_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NULL,
  location_id INT UNSIGNED NULL,
  title VARCHAR(190) NOT NULL,
  description TEXT NOT NULL,
  requirements TEXT NULL,
  salary_min INT UNSIGNED NULL,
  salary_max INT UNSIGNED NULL,
  employment_type ENUM('Täistööaeg','Osaline tööaeg','Kaugtöö','Praktika') NOT NULL DEFAULT 'Täistööaeg',
  start_date VARCHAR(80) NOT NULL DEFAULT 'Esimesel võimalusel',
  status ENUM('active','closed') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  FOREIGN KEY (location_id) REFERENCES job_locations(id) ON DELETE SET NULL,
  INDEX idx_jobs_status (status),
  INDEX idx_jobs_title (title)
) ENGINE=InnoDB;

CREATE TABLE cv_files (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_name VARCHAR(255) NOT NULL,
  size INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  job_id INT UNSIGNED NOT NULL,
  cv_id INT UNSIGNED NULL,
  message TEXT NULL,
  status ENUM('Uus','Ülevaatamisel','Sobiv','Tagasi lükatud') NOT NULL DEFAULT 'Uus',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_job (user_id, job_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (cv_id) REFERENCES cv_files(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE favorites (
  user_id INT UNSIGNED NOT NULL,
  job_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, job_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_id INT UNSIGNED NOT NULL,
  receiver_id INT UNSIGNED NOT NULL,
  body TEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reports (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reporter_id INT UNSIGNED NOT NULL,
  job_id INT UNSIGNED NULL,
  reason TEXT NOT NULL,
  status ENUM('open','resolved') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  text VARCHAR(255) NOT NULL,
  link VARCHAR(255) NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE settings (
  k VARCHAR(60) PRIMARY KEY,
  v VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ===== Algandmed =====
INSERT INTO categories (name) VALUES
 ('IT ja arendus'),('Müük ja klienditeenindus'),('Haldus ja juhtimine'),('Ehitus'),
 ('Transport ja logistika'),('Tervishoid'),('Haridus'),('Turundus ja disain'),('Finants ja raamatupidamine');

INSERT INTO job_locations (name) VALUES
 ('Tallinn'),('Tartu'),('Pärnu'),('Narva'),('Viljandi'),('Kaugtöö');

INSERT INTO settings (k, v) VALUES
 ('site_name','JobPortal'),('contact_email','info@jobportal.ee'),('jobs_per_page','10');

-- Demokontod. Admin: admin@jobportal.ee / Admin123!   Teised: Parool123!
INSERT INTO users (id, name, email, password, role, city) VALUES
 (1,'Administraator','admin@jobportal.ee','$2y$10$RpniA5AXk05jNEgoO9kkiOCZN/xLIPwiWDjVZkEFnxnGnhRj./LVe','admin','Tallinn'),
 (2,'Denis Kask','denis@gmail.com','$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.','user','Tallinn'),
 (3,'TechSolutions OÜ','tech@techsolutions.ee','$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.','employer','Tallinn'),
 (4,'DreamWorks OÜ','info@dreamworks.ee','$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.','employer','Tartu'),
 (5,'Nordic Tech AS','hr@nordictech.ee','$2y$10$BfnVIinMH8qaJmUitZvNCOz6Y6QgmfJdJ1GQz09h/hfXj/B.I1a7.','employer','Tallinn');

INSERT INTO companies (id, user_id, name, description, website, email, city) VALUES
 (1,3,'TechSolutions OÜ','Kasvav tarkvaraarenduse ettevõte, mis loob veebi- ja mobiilirakendusi Eesti ja Põhjamaade klientidele.','https://techsolutions.example','tech@techsolutions.ee','Tallinn'),
 (2,4,'DreamWorks OÜ','Digiagentuur, mis ehitab veebilehti ja e-poode ning pakub ka turundusteenuseid.','https://dreamworks.example','info@dreamworks.ee','Tartu'),
 (3,5,'Nordic Tech AS','IT-infrastruktuuri ja tugiteenuste pakkuja ettevõtetele.','https://nordictech.example','hr@nordictech.ee','Tallinn');

INSERT INTO jobs (company_id, category_id, location_id, title, description, requirements, salary_min, salary_max, employment_type, start_date) VALUES
 (1,1,1,'Java Arendaja','Otsime oma meeskonda kogenud Java arendajat, kes soovib töötada huvitavate projektidega ja areneda koos meiega.\n\nPeamised ülesanded:\n- Veebirakenduste arendus ja hooldus\n- Uute funktsionaalsuste loomine\n- Koodi ülevaatus ja dokumenteerimine','- Vähemalt 3 aastat Java kogemust\n- Spring Boot ja SQL tundmine\n- Hea suhtlemisoskus eesti ja inglise keeles',2500,3500,'Täistööaeg','Esimesel võimalusel'),
 (2,1,2,'Veebiarendaja (PHP)','Ehitame kliendiprojekte PHP ja MySQL baasil. Ootame meeskonda arendajat, kes oskab töötada iseseisvalt.','- PHP 8 ja MySQL kogemus\n- HTML, CSS, JavaScript\n- Git',2000,2800,'Täistööaeg','Kokkuleppel'),
 (3,1,1,'Süsteemiadministraator','Hoiame klientide IT-süsteemid töökorras. Pakume kasvuvõimalusi ja koolitusi.','- Windows Server ja Linux kogemus\n- Võrgunduse alused',1800,2500,'Täistööaeg','Esimesel võimalusel'),
 (1,8,6,'UI/UX disainer','Kujundame kasutajasõbralikke rakendusi. Töö toimub kaugtööna.','- Figma kogemus\n- Portfoolio',2000,3000,'Kaugtöö','Kokkuleppel'),
 (2,2,2,'Klienditeenindaja','Vastame klientide päringutele ja aitame neil leida sobivaid lahendusi.','- Hea suhtlemisoskus\n- Eesti ja inglise keel',1200,1600,'Osaline tööaeg','Esimesel võimalusel');

INSERT INTO applications (user_id, job_id, message, status) VALUES
 (2,1,'Tere! Olen huvitatud sellest ametikohast.','Ülevaatamisel'),
 (2,2,'Tere! Soovin kandideerida.','Uus');
INSERT INTO favorites (user_id, job_id) VALUES (2,1),(2,3),(2,4);
INSERT INTO notifications (user_id, text, link) VALUES
 (2,'Sinu kandideerimine „Java Arendaja" on ülevaatamisel.','index.php?page=applications'),
 (2,'Tere tulemast JobPortali!','index.php?page=profile');
