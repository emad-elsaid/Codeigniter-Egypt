<?php
class Content extends DataMapper {
	var $has_one = array('section');
	
    function Content()
    {
        parent::DataMapper();
    }
}
