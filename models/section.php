<?php
class Section extends DataMapper {
	$has_many = array('content','section');
    function Section()
    {
        parent::DataMapper();
    }
}
