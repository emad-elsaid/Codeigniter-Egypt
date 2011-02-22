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
include( 'config.php' );

$config["site_name"] = $config_site_name;
$config["doctype"] = "xhtml1-strict";
$config["charset"] = "utf-8";

/*
 * site autoloading css style sheets
 * and javascript files that autoloads in every page
 */
$config["dojoStyle"] = "claro";
$config["css"] = array();
$config["js"] = array();

