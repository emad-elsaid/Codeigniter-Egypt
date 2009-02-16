<?php
class Section extends DataMapper {
	var $has_many = array('content','section');
    function Section()
    {
        parent::DataMapper();
    }
}
