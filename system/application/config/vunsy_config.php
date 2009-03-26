<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vunsy config file
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	Config file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
$config["site_name"] = "siteName";
$config["doctype"] = "XHTML 1.0 Strict";
$config["charset"] = "utf-8";

/*
 * site autoloading css style sheets
 * and javascript files that autoloads in every page
 */
$config["dojoStyle"] = "nihilo";
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
