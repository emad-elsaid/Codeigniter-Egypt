<?php
class User extends DataMapper {
	$has_one = array('userlevel');
	
    function User()
    {
        parent::DataMapper();
    }
}
