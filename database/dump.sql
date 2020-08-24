# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 35.201.0.22 (MySQL 5.7.25-google-log)
# Database: sentral
# Generation Time: 2020-08-24 01:53:26 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) DEFAULT NULL,
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`category_id`, `category_name`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,'Excursion','2020-08-23 10:58:53','2020-08-23 10:58:53',1),
	(2,'Camping','2020-08-23 10:58:53','2020-08-23 10:58:53',1),
	(3,'Sports','2020-08-23 10:58:53','2020-08-23 10:58:53',1),
	(4,'Co-curricular ','2020-08-23 10:58:53','2020-08-23 10:58:53',1),
	(5,'Other','2020-08-23 10:58:53','2020-08-23 10:58:53',1);

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned NOT NULL DEFAULT '0',
  `venue_id` int(11) unsigned NOT NULL DEFAULT '0',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `event_name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `event_datetime` datetime DEFAULT NULL,
  `distance` float DEFAULT NULL,
  `travel_time` int(11) DEFAULT NULL COMMENT 'minutes',
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_id`),
  KEY `event_school_fk` (`school_id`),
  KEY `event_venue_fk` (`venue_id`),
  KEY `event_category_fk` (`category_id`),
  CONSTRAINT `event_category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `event_school_fk` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`),
  CONSTRAINT `event_venue_fk` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;

INSERT INTO `event` (`event_id`, `school_id`, `venue_id`, `category_id`, `event_name`, `description`, `event_datetime`, `distance`, `travel_time`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,1,1,2,'Campign Narrabeen','Campign with all the students for the weekend','2020-08-29 10:00:00',NULL,NULL,'2020-08-23 11:49:53','2020-08-23 20:41:12',1);

/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event_attendee
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_attendee`;

CREATE TABLE `event_attendee` (
  `event_attendee_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL DEFAULT '0',
  `person_id` int(11) unsigned NOT NULL DEFAULT '0',
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_attendee_id`),
  KEY `event_attendee_event_fk` (`event_id`),
  KEY `event_attendee_person_fk` (`person_id`),
  CONSTRAINT `event_attendee_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  CONSTRAINT `event_attendee_person_fk` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table event_organiser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_organiser`;

CREATE TABLE `event_organiser` (
  `event_organiser_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL DEFAULT '0',
  `person_id` int(11) unsigned NOT NULL DEFAULT '0',
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_organiser_id`),
  KEY `event_organiser_event_fk` (`event_id`),
  KEY `event_organiser_person_fk` (`person_id`),
  CONSTRAINT `event_organiser_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  CONSTRAINT `event_organiser_person_fk` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `event_organiser` WRITE;
/*!40000 ALTER TABLE `event_organiser` DISABLE KEYS */;

INSERT INTO `event_organiser` (`event_organiser_id`, `event_id`, `person_id`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,1,1,'2020-08-23 11:51:23','2020-08-23 11:51:23',1);

/*!40000 ALTER TABLE `event_organiser` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event_participant
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_participant`;

CREATE TABLE `event_participant` (
  `event_participant_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL DEFAULT '0',
  `person_id` int(11) unsigned NOT NULL DEFAULT '0',
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_participant_id`),
  KEY `event_participant_event_fk` (`event_id`),
  KEY `event_participant_person_fk` (`person_id`),
  CONSTRAINT `event_participant_event_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  CONSTRAINT `event_participant_person_fk` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table person
# ------------------------------------------------------------

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `person_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `person_type_id` int(11) unsigned NOT NULL DEFAULT '0',
  `person_name` varchar(100) DEFAULT NULL,
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`person_id`),
  KEY `person_type_fk` (`person_type_id`),
  CONSTRAINT `person_type_fk` FOREIGN KEY (`person_type_id`) REFERENCES `person_type` (`person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;

INSERT INTO `person` (`person_id`, `person_type_id`, `person_name`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,1,'Tom Allison','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(3,1,'Harvir Rosas','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(4,2,'Heidi Stevenson','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(5,2,'Brodie Firth','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(6,3,'Eduardo England','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(7,3,'Arvin Harrington','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(8,4,'Hal Liu','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(9,4,'Bayley Ramirez','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(10,5,'Mitchel Fraser','2020-08-23 11:46:53','2020-08-23 11:46:53',1),
	(12,5,'Alena Henson','2020-08-23 11:46:53','2020-08-23 11:46:53',1);

/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table person_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `person_type`;

CREATE TABLE `person_type` (
  `person_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `person_type` varchar(100) NOT NULL DEFAULT '0',
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`person_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `person_type` WRITE;
/*!40000 ALTER TABLE `person_type` DISABLE KEYS */;

INSERT INTO `person_type` (`person_type_id`, `person_type`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,'Organiser','2020-08-23 11:44:23','2020-08-23 11:44:23',1),
	(2,'Staff','2020-08-23 11:44:23','2020-08-23 11:44:23',1),
	(3,'Parent','2020-08-23 11:44:23','2020-08-23 11:44:23',1),
	(4,'Volunteer','2020-08-23 11:44:23','2020-08-23 11:44:23',1),
	(5,'Other','2020-08-23 11:44:23','2020-08-23 11:44:23',1);

/*!40000 ALTER TABLE `person_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table school
# ------------------------------------------------------------

DROP TABLE IF EXISTS `school`;

CREATE TABLE `school` (
  `school_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_name` varchar(150) DEFAULT NULL,
  `school_address` varchar(250) DEFAULT NULL,
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;

INSERT INTO `school` (`school_id`, `school_name`, `school_address`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,'Chatswood Public School','5 Centennial Ave, Chatswood NSW 2067','2020-08-23 11:51:23','2020-08-23 11:51:23',1);

/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table venue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venue`;

CREATE TABLE `venue` (
  `venue_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(150) DEFAULT NULL,
  `venue_address` varchar(250) DEFAULT NULL,
  `inserted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `venue` WRITE;
/*!40000 ALTER TABLE `venue` DISABLE KEYS */;

INSERT INTO `venue` (`venue_id`, `venue_name`, `venue_address`, `inserted_at`, `updated_at`, `current`)
VALUES
	(1,'NRMA Sydney Lakeside Holiday Park','38 Lake Park Rd, North Narrabeen NSW 2101','2020-08-23 11:42:25','2020-08-23 11:42:25',1);

/*!40000 ALTER TABLE `venue` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
