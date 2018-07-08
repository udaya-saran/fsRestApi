CREATE DATABASE `fsRestApi`;

CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(64) CHARACTER SET latin1 NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `device_entry` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `reported_at` datetime NOT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `dev_ind` (`device_id`),
  CONSTRAINT `device_entry_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_pass` varchar(128) NOT NULL,
  `time_zone` varchar(64) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`first_name`, `last_name`, `user_email`, `user_pass`, `time_zone`, `approved`, `created_at`, `modified_at`) 
VALUES ('Admin', 'User', 'admin@fsRestApi.com', '0e7517141fb53f21ee439b355b5a1d0a', 'UTC', '1', NOW(), NOW());
