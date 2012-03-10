
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(3) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `accessed` datetime default NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `name` (`username`),
  KEY `status` (`status`)
);

INSERT INTO `users` (`username`, `password`, `salt`, `email`, `created`, `accessed`, `status`) VALUES
('admin', 'c2bf2ea3e1868c05c926d142abe17fba', '-f0', 'root@example.com', '2010-06-13 19:51:15', NULL, 2);
