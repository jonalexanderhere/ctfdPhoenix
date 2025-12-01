-- CTFd Database Schema for PHP
-- This is a simplified version. For full schema, refer to CTFd migrations

-- Config table
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` text NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_id` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `type` varchar(80) DEFAULT 'user',
  `secret` varchar(128) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `affiliation` varchar(128) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `bracket_id` int(11) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT 0,
  `banned` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `language` varchar(32) DEFAULT NULL,
  `change_password` tinyint(1) DEFAULT 0,
  `team_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `oauth_id` (`oauth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Teams table
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_id` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `secret` varchar(128) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `affiliation` varchar(128) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `bracket_id` int(11) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT 0,
  `banned` tinyint(1) DEFAULT 0,
  `captain_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `oauth_id` (`oauth_id`),
  KEY `captain_id` (`captain_id`),
  FOREIGN KEY (`captain_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Challenges table
CREATE TABLE IF NOT EXISTS `challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `description` text,
  `attribution` text,
  `connection_info` text,
  `next_id` int(11) DEFAULT NULL,
  `max_attempts` int(11) DEFAULT 0,
  `value` int(11) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `type` varchar(80) DEFAULT 'standard',
  `state` varchar(80) NOT NULL DEFAULT 'visible',
  `logic` varchar(80) NOT NULL DEFAULT 'any',
  `initial` int(11) DEFAULT NULL,
  `minimum` int(11) DEFAULT NULL,
  `decay` int(11) DEFAULT NULL,
  `function` varchar(32) DEFAULT 'static',
  `requirements` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `next_id` (`next_id`),
  FOREIGN KEY (`next_id`) REFERENCES `challenges` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Flags table
CREATE TABLE IF NOT EXISTS `flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` int(11) NOT NULL,
  `type` varchar(80) DEFAULT NULL,
  `content` text,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hints table
CREATE TABLE IF NOT EXISTS `hints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `type` varchar(80) DEFAULT 'standard',
  `challenge_id` int(11) NOT NULL,
  `content` text,
  `cost` int(11) DEFAULT 0,
  `requirements` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tags table
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` int(11) NOT NULL,
  `value` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Submissions table
CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `ip` varchar(46) DEFAULT NULL,
  `provided` text,
  `type` varchar(32) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`),
  FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Solves table (inherits from submissions)
CREATE TABLE IF NOT EXISTS `solves` (
  `id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `ip` varchar(46) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `challenge_user` (`challenge_id`, `user_id`),
  UNIQUE KEY `challenge_team` (`challenge_id`, `team_id`),
  FOREIGN KEY (`id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pages table
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `route` varchar(128) DEFAULT NULL,
  `content` text,
  `draft` tinyint(1) DEFAULT 0,
  `hidden` tinyint(1) DEFAULT 0,
  `auth_required` tinyint(1) DEFAULT 0,
  `format` varchar(80) DEFAULT 'markdown',
  `link_target` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `content` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

