-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 30, 2007 at 12:20 AM
-- Server version: 4.1.15
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `vunsy`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE `config` (
  `folder` varchar(250) NOT NULL default '',
  `variable` varchar(250) NOT NULL default '',
  `value` longtext,
  KEY `folder` (`folder`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `content`
-- 

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

-- --------------------------------------------------------

-- 
-- Table structure for table `lang`
-- 

CREATE TABLE `lang` (
  `lang` varchar(30) NOT NULL default '',
  `app` char(2) NOT NULL default '',
  UNIQUE KEY `lang` (`lang`,`app`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `langkeys`
-- 

CREATE TABLE `langkeys` (
  `id` double NOT NULL auto_increment,
  `word` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `langvalues`
-- 

CREATE TABLE `langvalues` (
  `lang` char(2) NOT NULL default '',
  `id` double NOT NULL default '0',
  `value` longtext,
  KEY `lang` (`lang`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `section`
-- 

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

-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

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

-- --------------------------------------------------------

-- 
-- Table structure for table `userinfo`
-- 

CREATE TABLE `userinfo` (
  `field` varchar(50) NOT NULL default '',
  `title` longtext,
  `type` varchar(30) default NULL,
  `signin` char(1) default NULL,
  `req` char(1) default NULL,
  UNIQUE KEY `field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `userlevel`
-- 

CREATE TABLE `userlevel` (
  `level` int(2) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  UNIQUE KEY `level` (`level`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `userlog`
-- 

CREATE TABLE `userlog` (
  `ip` varchar(20) default NULL,
  `count` int(2) default NULL,
  `lastenter` varchar(14) default NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
