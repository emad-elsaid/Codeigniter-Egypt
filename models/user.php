<?php
class User extends DataMapper {
	var $has_one = array('userlevel');
	
    function User()
    {
        parent::DataMapper();
    }
}
