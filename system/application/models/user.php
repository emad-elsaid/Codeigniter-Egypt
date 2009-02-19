<?php
class User extends DataMapper {
	var $table = 'user';
	//var $has_one = array('userlevel');
	
    function User()
    {
        parent::DataMapper();
    }
}
