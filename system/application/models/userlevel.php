<?php
class Userlevel extends DataMapper {
	var $table = 'userlevel';
	var $has_many = array('user');
    function Userlevel()
    {
        parent::DataMapper();
    }
}
