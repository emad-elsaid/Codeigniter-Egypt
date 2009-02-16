
CREATE TABLE `content` (
  `section` varchar(50) default NULL,
  `childs` tinyint(1) default '0',
  `parent` varchar(30) default NULL,
  `cell` varchar(30) default NULL,
  `name` varchar(30) NOT NULL default '',
  `path` varchar(100) default NULL,
  `sort` double default NULL,
  `view` longtext,
  `addin` longtext,
  `edit` longtext,
  `del` longtext,
  `by` varchar(30) default NULL,
  `date` varchar(14) default NULL,
  `type` varchar(30) default NULL,
  `br` varchar(4) default NULL,
  `info` longtext,
  `window` longtext,
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `section` (
  `name` varchar(50) NOT NULL default '',
  `text` longtext NOT NULL,
  `parent` varchar(50) default NULL,
  `view` longtext,
  `comment` longtext,
  `add` longtext,
  `edit` longtext,
  `sort` double default NULL,
  `date` varchar(14) default NULL,
  UNIQUE KEY `name` (`name`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `user` (
  `ID` double NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `level` varchar(100) NOT NULL default '',
  `lastenter` varchar(14) default NULL,
  `curenter` varchar(14) default NULL,
  `lang` char(2) default NULL,
  `email` varchar(50) default NULL,
  `info` longtext,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `userlevel` (
  `level` int(2) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  UNIQUE KEY `level` (`level`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
