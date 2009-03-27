<?php
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."directories(
		id INTEGER PRIMARY KEY auto_increment,
		name text,
		parent integer not null
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files(
		id INTEGER PRIMARY KEY auto_increment,
		name text,
		directory integer not null
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files_images(
		id INTEGER PRIMARY KEY auto_increment,
		caption text,
		file_id integer not null,
		width integer default 0,
		height integer default 0
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files_images_thumbs(
		id INTEGER PRIMARY KEY auto_increment,
		image_id integer not null,
		width integer default 0,
		height integer default 0
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."parameters(
		name text,
		value text
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session (
		`id` int(11) NOT NULL auto_increment,
		`cookie` varchar(32) default NULL,
		`last_accessed` datetime default NULL,
		PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session_vars (
		`session_id` int(11) default NULL,
		`varname` text,
		`varvalue` text,
		KEY `session_id` (`session_id`)
	) DEFAULT CHARSET=utf8");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."tagged_files(
		file_id	INTEGER,
		tag_id	INTEGER
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."tags(
		id INTEGER PRIMARY KEY auto_increment,
		name text
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

	$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters values('version','1.3')");
	$kfmdb->query("insert into ".KFM_DB_PREFIX."directories values(1,'root',0)");

	if(!PEAR::isError($kfmdb))$db_defined=1;
?>
