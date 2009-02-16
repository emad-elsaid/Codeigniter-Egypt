<?php
class Section extends DataMapper {
	var $table = 'section';
	var $has_many = array('content','section');
    function Section()
    {
        parent::DataMapper();
    }
}
