<?php
class Widget extends Content{
	
	var $has_one = array('layout');
		
	function Widget(){
		parent::Content();
	}
	
}
