<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UserEditor extends Application
{
	function __construct(){
		parent::__construct();

		$this->perm = 'root';

		$this->name 	= "User Editor";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";
		$this->pages 	= array(
							'index'=>'Information'
							,'viewlvl'=>'View levels'
							,'viewusr'=>'View users'
							);

							$this->load->library('gui');
	}

	function dellvl($id){

		$l = new userlevel();
		$l->get_by_id( $id );

		if( ! $l->exists() )
		$this->add_error( "level not found" );
		else
		{
			$usr = new User();
			$usr->get_by_level( $l->id );
			$usr->delete_all();

			$l->delete();
			$this->add_info( " level deleted " );
		}

	}

	function addusraction(){
		$u = new user();
		$u->get_by_name($this->input->post('name'));

		if( $u->exists() )
		$this->add_error( "user name duplication please choose another name " );

		else
		{
			$u->name = $this->input->post('name');
			$u->set_password( $this->input->post('password') );
			$u->email = $this->input->post('email');
			$u->level = $this->input->post( 'level' );
			$u->save();
			redirect( site_url('userEditor/viewusr'));
		}
	}

	function addlvlaction()
	{
		$l = new userlevel();
		$l->get_by_level( $this->input->post( 'level' ) );

		if( $l->exists() )
		$this->add_error( "Level Exists please choose another level number" );
		else
		{
			$l->name = $this->input->post( 'name' );
			$l->level = $this->input->post( 'level' );
			$l->save();
			redirect( site_url('userEditor/viewlvl'));
		}
	}

	function delusr($id)
	{
		$l = new user($id);

		if( ! $l->exists() )
		$this->add_error( "User not found" );
		else
		{
			$l->delete();
			$this->add_info( " user deleted " );
		}
	}

	function editlvl($id)
	{
		//getting the level;
		$level = new Userlevel($id);

		$this->print_text( $this->gui->form( site_url('userEditor/editlvlaction'),
		array(
			"Level name" => $this->gui->textbox( 'name' , $level->name)
		,"Level number" => $this->gui->number( 'level', $level->level )
		,"" => $this->gui->button( 'submit',  'Edit level' , array( 'type'=>'submit' ) )
		)
		,""
		,array('id'=>$id)
		));
	}

	function editlvlaction()
	{
		$level = new Userlevel($this->input->post('id'));
		$level->name = $this->input->post('name');
		$level->level = $this->input->post('level');
		$level->save();

		$this->add_info('level updated');
	}

	function editusr($id)
	{
		$user = new User($id);

		$levels = new Userlevel();
		$levels->get();
		$lvls = array();
		foreach( $levels as $item )
		{
			$lvls[ $item->level ] = $item->name;
		}

		$this->print_text( $this->gui->form( site_url('userEditor/editusraction')
		,array(
				'level' => $this->gui->dropdown( "level", $user->level, $lvls),
				'name' => $this->gui->textbox( "name", $user->name ),
				'password' => $this->gui->password( "password" ),
				'email' => $this->gui->textbox( "email", $user->email ),
				'' => $this->gui->button( "submit", "Edit user", array( "type"=>"submit" ) )
		)
		,''
		,array('id'=>$user->id )
		));
	}

	function editusraction()
	{
		$usr = new User($this->input->post('id'));
		$usr->name = $this->input->post('name');
		$usr->level = $this->input->post('level');
		$usr->set_password( $this->input->post('password') );
		$usr->email = $this->input->post('email');
		$usr->save();

		redirect( "userEditor/viewusr" );
	}

	function index()
	{
		$this->print_text(<<<EOT
		<b>This APPLICATION IS DESIGNED TO MANAGE</b>
<br>
1- adding and removing users levels
<br>
2- adding and deleting users
<br>
3- editing users and levels names
EOT
		);
	}

	function viewlvl()
	{
		$l = new userlevel();
		$l->get();

		$this->print_text( $this->gui->tooltipbutton( "Add level", $this->gui->form( site_url('userEditor/addlvlaction'),
		array(
		"Level name" => $this->gui->textbox( 'name' )
		,"Level number" => $this->gui->number( 'level', 1 )
		,"" => $this->gui->button( 'submit',  'Add level' , array( 'type'=>'submit' ) )
		)
		)));


		$a = $l->all;
		if( count( $a )==0 )
		{
			$this->add_info('No levels exists');
		}
		else
		{

			foreach( $a as $item )
			{
				$item->d = anchor(site_url('userEditor/dellvl/'.$item->id), "Delete");
				$item->e = anchor(site_url('userEditor/editlvl/'.$item->id), "Edit");
			}

			$this->print_text( $this->gui->grid(
			array('id'=>'ID','level'=>'Level','name'=>'Name','d'=>'Delete','e'=>'Edit' )
			, $l->all));

		}
	}
	
	function viewusr()
	{
		$l = new user();
		$l->get();
		$this->load->library('gui');
		
		//getting user levels and make an array of them
		$levels = new userlevel();
		$levels->get();
		$lvls = '';
		foreach( $levels as $item )
		{
			$lvvv = $item->level;
			$lvls->$lvvv = $item->name;
		}
		
		if( $lvls=='')
			$this->add_error( 'you have to create a new user level before starting to add users' );
		else
			$this->print_text( $this->gui->tooltipbutton(
							"Add user", 
							$this->gui->form( site_url('userEditor/addusraction'),
								array(
				'level' => $this->gui->dropdown( "level","",$lvls),
				'name' => $this->gui->textbox( "name" ),
				'password' => $this->gui->password( "password" ),
				'email' => $this->gui->textbox( "email" ),
				'' => $this->gui->button( "submit", "Add user", array( "type"=>"submit" ) )
								)
							)
						));
		
		if( $l->count() <= 0 )
			$this->add_info( " No users available" );
		else
		{
			$c = new userlevel();
			foreach( $l as $item )
			{
				$c->get_by_level( $item->level );
				$item->level = $c->name;
				$item->d = anchor(site_url('userEditor/delusr/'.$item->id), "Delete");
				$item->e = anchor(site_url('userEditor/editusr/'.$item->id), "Edit");
			}
		
			$this->print_text( $this->gui->grid(
					array('id'=>'ID','level'=>'Level','name'=>'Name','email'=>'Email','lastenter'=>'Last enter','d'=>'Delete','e'=>'Edit')
					,$l->all));
			
		}
				
	}
}