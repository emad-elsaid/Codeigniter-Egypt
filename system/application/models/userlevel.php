<?php
/** \addtogroup Models
 * Userlevel class: datamapper class that holds userlevel data from the database
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Userlevel extends DataMapper {
	var $table = 'userlevel';
	
    function Userlevel()
    {
        parent::DataMapper();
    }
}
