-- Database and tables for Youth Skills System
CREATE DATABASE IF NOT EXISTS `youth_skills_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `youth_skills_system`;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `job_offers`;
DROP TABLE IF EXISTS `trainings`;
DROP TABLE IF EXISTS `certifications`;
DROP TABLE IF EXISTS `skills`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(150),
  `email` VARCHAR(200) UNIQUE,
  `password` VARCHAR(255),
  `role` VARCHAR(50),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `skills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `skill_name` VARCHAR(200),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `certifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `certificate_name` VARCHAR(255) NOT NULL,
  `issue_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `trainings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_id` INT,
  `title` VARCHAR(255),
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `job_offers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_id` INT,
  `title` VARCHAR(255),
  `description` TEXT,
  `offer_type` VARCHAR(80),
  `required_skill` VARCHAR(200),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Users with hashed password (password: 'password')
INSERT IGNORE INTO users (id,fullname,email,password,role) VALUES 
(1,'Juan Dela Cruz','juan@example.com','$2y$10$YourHashedPasswordHere123456','youth'),
(2,'Tech Innovations Inc','company@techinnovations.com','$2y$10$YourHashedPasswordHere123456','company');

-- Youth Skills (for Juan Dela Cruz - ID 1)
INSERT IGNORE INTO skills (user_id,skill_name) VALUES 
(1,'HTML/CSS'),
(1,'JavaScript'),
(1,'Basic Python'),
(1,'Communication');

-- Youth Certifications (for Juan Dela Cruz - ID 1)
INSERT IGNORE INTO certifications (user_id,certificate_name,issue_date) VALUES 
(1,'Google IT Support Professional','2024-06-15'),
(1,'HubSpot Digital Marketing','2024-09-20');

-- Company Job Offers (posted by Tech Innovations Inc - ID 2)
INSERT IGNORE INTO job_offers (id,company_id,title,description,offer_type,required_skill) VALUES 
(1,2,'Junior Web Developer','We are looking for enthusiastic junior developers to join our team. You will work on real projects from day one.','Internship','JavaScript'),
(2,2,'Content Writer - Part-time','Create engaging content for our blog and social media channels. Flexible hours, work from home.','Part-time','Communication'),
(3,2,'Data Analyst Trainee','Learn data analytics while working on actual business data. Great opportunity for career growth.','Internship','Basic Python'),
(4,2,'UI/UX Designer','Join our creative team and design beautiful user experiences for our web and mobile products.','Full-time','Design'),
(5,2,'Technical Support Representative','Provide excellent customer support and troubleshooting. Work from home with flexible hours.','Part-time','Communication');

-- Company Trainings (offered by Tech Innovations Inc - ID 2)
INSERT IGNORE INTO trainings (id,company_id,title,description) VALUES 
(1,2,'Web Development Bootcamp','Learn HTML, CSS, JavaScript, and modern frameworks in 12 weeks. Hands-on projects included.'),
(2,2,'Data Analytics Fundamentals','Master data analysis, Excel, SQL, and visualization tools. Industry-standard tools and real-world datasets.'),
(3,2,'UI/UX Design Masterclass','Learn design principles, prototyping, user research, and Figma. Build a portfolio while learning.'),
(4,2,'Python for Automation','From Python basics to advanced scripting for automation and web scraping. Perfect for career changers.'),
(5,2,'Digital Marketing Strategy','Master SEO, social media marketing, content strategy, and analytics. Learn from industry professionals.');
