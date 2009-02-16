<?php
class Content extends DataMapper {
	$has_one = array('section');
	
    function Content()
    {
        parent::DataMapper();
    }
}
