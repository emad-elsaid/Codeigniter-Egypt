<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * codeigniter library the contains current system configuration and user and section
 * 
 * it has current $section object, $user object and User group object
 * plus the current website mode specific to that user
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 * @package Libraries
 */ 
class System {

	public $CI 			= NULL;
	public $section 	= NULL;
	public $user 		= NULL;
	public $group		= NULL;
	public $mode 		= 'view';

	public function __construct(){

		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library(array('datamapper','session','ion_auth'));
		$this->CI->load->helper(array('perm', 'html','url','theme'));

		// getting the current section
		$this->section = new Section($this->CI->uri->rsegment(3));
		if(!$this->section->exists())
		$this->section->get_by_id(1);

		if( $this->CI->ion_auth->logged_in()){
			//getting the current user data
			$this->user = $this->CI->ion_auth->get_user();
			// getting group
			$this->group = $this->CI->ion_auth->get_group($this->user->group_id);
		} else {
			$this->user = new User();
			$this->group = new Group();
		}

		// getting the site mode
		$this->mode = $this->CI->session->userdata('mode');
		if($this->mode!='view' and $this->mode!='edit')
		$this->mode = 'view';
	}
	
	/**
	 * set current user mode to edit or view
	 * 
	 * @param string  $mode [view,edit] are the values that will work for now,
	 * @return void
	 */
	public function set_mode($mode){
		$this->mode = $mode;
		$this->CI->session->set_userdata('mode', $mode);
	}
}

?>
