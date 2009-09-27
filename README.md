Vunsy system
=====================
if you are a website developer then you know the amount of work getting the site components together you  have to make a database base minimal structure and 
1.users table: holding users information
2.pages table: holding pages names and titles, permissions
3.user groups table: users groups
4.content table: site articles and every components

and you need to get your javascript frameworks and some minimal backend pages.
We take about 20% of overall projects work hours in preperations. If you are tired of that then vunsy is for you.

Vunsy is a very easy CMS that built on a very simple idea like HTML and all the XML languages 
every website is consisting of pages every page may have subpages and so 
every page has a permissions to determine  it's displayable for that user or under any other conditions 
every page contain widgets inside widgets , the widget display data and may have cells that can contain another set of widgets and so on. 
Every widget may have properties and children , the widget is like a function that takes paramters make a process on them and return an HTML result 
vunsy support the idea of applications on the website every application has a JSON defination file and permissions to use 


[program built on]
---
* CodeIgniter PHP framework Version 1.7.2
* Datamapper OverZealous Edition Version 1.5.3
* Dojo+Dijit+Dojox Version 1.3.2
* Jquery UI Version (1.7.2: for jQuery 1.3+)
* KFM file browser Version 1.3

[The idea]
---
* the idea is to make a site with fast way debending on the widgets and layouts
giving permissions to evey content for several things
viewing, deleting and so.

* so as the section has the same thing
built on it some applications accessible by a task bar like facebook one

-----------------------------------------------------------------
[ Installation ]
---
* 1- copy the vunsy folder to your web folder
* 2- files needed to be configured vunsy/config.php  or make it writable by server and open the install.php in your browser
	
	if you want to make more configuration modify
			vunsy/system/application/config/config.php
			vunsy/system/application/config/database.php
			vunsy/system/application/config/vunsy_config.php
			vunsy/kfm/configuration.php
			
* 3- to install database : 
	open the vunsy website 
	ex: http://localhost/vunsy/
	you can use install.php but config.php must be writable
-----------------------------------------------------------------
[ Running ]
---
* 1- normal site: 
	vunsyURL/index.php
	ex: http://localhost/vunsy/index.php
* 2- login page:
	vunsyURL/index.php/login
	ex: http://localhost/vunsy/index.php/login
* 3- logout page:
	vunsyURL/index.php/logout
	ex: http://localhost/vunsy/index.php/logout
-----------------------------------------------------------------
[ Root default logon ]
---
* 1- user name: root
	password: toor
* you can change it from:
	vunsy/config.php
-----------------------------------------------------------------
[ How to help us ]
---
* contact me and tell you want to help on github
* watch that repo to tell me that you are intersted [ click the watch button ]
* fork that repo [ click the fork button ]
