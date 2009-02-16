<?php
class Content extends DataMapper {
	var $table = 'content';
	var $has_one = array('section');
	
    function Content()
    {
        parent::DataMapper();
    }
}
