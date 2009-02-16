<?php
class Userlevel extends DataMapper {
	var $has_many = array('user');
    function Userlevel()
    {
        parent::DataMapper();
    }
}
