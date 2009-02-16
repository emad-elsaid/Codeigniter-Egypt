<?php
class Userlevel extends DataMapper {
	$has_many = array('user');
    function Userlevel()
    {
        parent::DataMapper();
    }
}
