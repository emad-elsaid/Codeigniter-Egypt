<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Site config variables
 * config the site name
 * and site style
 */
$config["site_name"] = "siteName";
$config["doctype"] = "XHTML 1.0 Strict";
$config["charset"] = "utf-8";

/*
 * site autoloading css style sheets
 * and javascript files that autoloads in every page
 */
$config["dojoStyle"] = "soria";
$config["css"] = array();
$config["js"] = array();

/*
 * root user has all previllages
 * he the designer of the website
 * all components ignores privilages when 
 * the current logged user is the root
 */
$config["root"] = "root";
$config["root_password"] = "toor";
