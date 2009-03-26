<?php
/**
 * user level class
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Userlevel extends DataMapper {
	var $table = 'userlevel';
	//var $has_many = array('user');
	
    function Userlevel()
    {
        parent::DataMapper();
    }
}
