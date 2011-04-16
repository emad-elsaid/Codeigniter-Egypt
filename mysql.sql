SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `contents` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `parent_content` int(9) unsigned DEFAULT NULL,
  `parent_section` int(9) unsigned DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `user` int(9) unsigned DEFAULT NULL,
  `subsection` tinyint(4) DEFAULT '1',
  `cell` tinyint(4) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `view` text,
  `filter` text,
  `info` longtext,
  PRIMARY KEY (`id`),
  KEY `parent_content` (`parent_content`),
  KEY `parent_section` (`parent_section`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `contents` (`id`, `parent_content`, `parent_section`, `title`, `user`, `subsection`, `cell`, `sort`, `path`, `view`, `filter`, `info`) VALUES
(1, NULL, 1, NULL, 1, 1, 0, 0, NULL, NULL, NULL, 'PAGE_BODY_LOCKED'),
(2, 1, 1, NULL, 1, 0, 0, 0, 'default.php', NULL, NULL, NULL);

CREATE TABLE `groups` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

CREATE TABLE `meta` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(9) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `meta` (`id`, `user_id`, `first_name`, `last_name`) VALUES
(1, 1, 'Admin', 'istrator');

CREATE TABLE `sections` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `parent_section` int(9) unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `view` text,
  PRIMARY KEY (`id`),
  KEY `parent_section` (`parent_section`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `sections` (`id`, `parent_section`, `name`, `sort`, `view`) VALUES
(1, NULL, NULL, 0, NULL);

CREATE TABLE `users` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(9) unsigned NOT NULL,
  `ip_address` char(16) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `group_id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `remember_code`, `created_on`, `last_login`, `active`) VALUES
(1, 1, '127.0.0.1', 'administrator', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'admin@admin.com', '', NULL, NULL, 1268889823, 1268889823, 1);

CREATE TABLE `ci_sessions` (
session_id varchar(40) DEFAULT '0' NOT NULL,
ip_address varchar(16) DEFAULT '0' NOT NULL,
user_agent varchar(50) NOT NULL,
last_activity int(10) unsigned DEFAULT 0 NOT NULL,
user_data text DEFAULT '' NOT NULL,
PRIMARY KEY (session_id)
);

ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`parent_content`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_2` FOREIGN KEY (`parent_section`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_3` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `meta`
  ADD CONSTRAINT `meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`parent_section`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
