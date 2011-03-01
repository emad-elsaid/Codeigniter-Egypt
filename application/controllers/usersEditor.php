<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UsersEditor extends Application {

	function __construct()
	{
		parent::__construct();

		$this->perm = 'admin';

		$this->name 	= "Users Editor";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";

		$this->show_toolbar 	= TRUE;
		$this->pages 			= array(
					'index'=>'Active Users',
					'inactive'=>'Inactive Users',
					'newGroup'=>'New Group',
					'newUser'=>'New User'
		);

		$this->load->library('gui');
	}

	function index(){

		add('dojo.data.ItemFileReadStore');
		add('dijit.tree.ForestStoreModel');
		add('dijit.Tree');
		
		$this->print_text('<div dojoType="dojo.data.ItemFileReadStore" url="'.site_url('usersEditor/queryGroups').'" jsId="ordJson"></div>');
		$this->print_text('<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="line" store="ordJson" jsId="ordModel"></div>');
		$this->print_text('<div dojoType="dijit.Tree" id="ordTree" model="ordModel" showRoot="false" >
		<script type="dojo/method" event="onClick" args="item">
		if( item.type=="group" )
			document.location.href = "'.site_url('usersEditor/editGroup').'/"+item.id;
		else
			document.location.href = "'.site_url('usersEditor/editUser').'/"+item.id;
		</script>
		</div>');
	}
	
	function queryGroups(){
		$this->ajax = TRUE;
		$groups = $this->ion_auth->get_groups();
		foreach( $groups as $key=>$group ){
			$groups[$key]->type = 'group';
			$groups[$key]->ident = 'g'.$group->id;
			$groups[$key]->line = array();
			
			$users = $this->ion_auth->get_active_users_array($group->name);
			foreach( $users as $uk=>$user ){
				$u = array();
				$u['ident'] = 'u'.$user['id'];
				$u['id'] = $user['id'];
				$u['name'] = $user['username'].' ('.$user['email'].')';
				$u['type'] = 'user';
				$groups[$key]->line[] = $u;
			}
		}
		
		$this->print_text( json_encode(array( 'identifier'=>'ident', 'label'=>'name','items'=>$groups)) );
	}
	
	function inactive(){

		add('dojo.data.ItemFileReadStore');
		add('dijit.tree.ForestStoreModel');
		add('dijit.Tree');
		
		$this->print_text('<div dojoType="dojo.data.ItemFileReadStore" url="'.site_url('usersEditor/queryInactiveGroups').'" jsId="ordJson"></div>');
		$this->print_text('<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="line" store="ordJson" jsId="ordModel"></div>');
		$this->print_text('<div dojoType="dijit.Tree" id="ordTree" model="ordModel" showRoot="false" >
		<script type="dojo/method" event="onClick" args="item">
		if( item.type=="group" )
			document.location.href = "'.site_url('usersEditor/editGroup').'/"+item.id;
		else
			document.location.href = "'.site_url('usersEditor/editUser').'/"+item.id;
		</script>
		</div>');
	}
	
	function queryInactiveGroups(){
		$this->ajax = TRUE;
		$groups = $this->ion_auth->get_groups();
		foreach( $groups as $key=>$group ){
			$groups[$key]->type = 'group';
			$groups[$key]->ident = 'g'.$group->id;
			$groups[$key]->line = array();
			
			$users = $this->ion_auth->get_inactive_users_array($group->name);
			foreach( $users as $uk=>$user ){
				$u = array();
				$u['ident'] = 'u'.$user['id'];
				$u['id'] = $user['id'];
				$u['name'] = $user['username'].' ('.$user['email'].')';
				$u['type'] = 'user';
				$groups[$key]->line[] = $u;
			}
		}
		
		$this->print_text( json_encode(array( 'identifier'=>'ident', 'label'=>'name','items'=>$groups)) );
	}
	
	function newGroup(){
		$this->print_text(
			$this->gui->form('usersEditor/newGroupAction',
			array(
				'Name :'=>$this->gui->textbox('name'),
				'Description :'=>$this->gui->textbox('description'),
				''=>$this->gui->button('submit','Save',array('type'=>'submit'))
			)
		));
	}
	
	function newGroupAction(){
		if(is_object($this->ion_auth->get_group_by_name($this->input->post('name'))))
			$this->add_error('This Group already Exists');
		else{
			$group = new Group();
			$group->name = $this->input->post('name');
			$group->description = $this->input->post('description');
			$group->save();
			$this->add_info('Group Added');
		}
		
	}
	
	function editGroup($id){
		
		$group = new Group($id);
		if( !$group->exists() )
			show_error('group Not found');
		
		$this->print_text(
			$this->gui->form('usersEditor/editGroupAction',
			array(
				'Name :'=>$this->gui->textbox('name',$group->name),
				'Description :'=>$this->gui->textbox('description',$group->description),
				''=>$this->gui->button('submit','Save',array('type'=>'submit')).
					anchor('usersEditor/deleteGroup/'.$id,'I want to delete that Group')
			),
			'',
			array('id'=>$id)
		));
	}
	
	function deleteGroup($id){
		$group = new Group($id);
		$group->delete();
		redirect('usersEditor');
	}
	
	function editGroupAction(){
		
		$group = new Group($this->input->post('id'));
		if( !$group->exists() )
			show_error('group Not found');
			
		$group->name = $this->input->post('name');
		$group->description = $this->input->post('description');
		$group->save();
		redirect('usersEditor');
		
	}
	
	function newUser(){

		$this->load->library('form_validation');
		//validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('phone1', 'First Part of Phone', 'xss_clean|min_length[3]|max_length[3]');
		$this->form_validation->set_rules('phone2', 'Second Part of Phone', 'xss_clean|min_length[3]|max_length[3]');
		$this->form_validation->set_rules('phone3', 'Third Part of Phone', 'xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('company', 'Company Name', 'xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array('first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
			);
		}
		if ($this->form_validation->run() == true 
			&& $this->ion_auth->register($username, $password, $email, $additional_data,$this->input->post('group')))
		{ //check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', "User Created");
			redirect("usersEditor");
		}
		else
		{ //display the create user form
			//set the flash data error message if there is one
			$groups = new Group();
			$groups->get();
			$groups_array = array();
			foreach( $groups as $group )
				$groups_array[$group->name] = $group->name;
				
			$this->print_text(
				$this->gui->form('usersEditor/newUser',
					array(
					' ' => (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'))),
					'Group ' => $this->gui->dropdown('group','',$groups_array),
					'First name*' => $this->gui->textbox('first_name',$this->form_validation->set_value('first_name')),
					'Last name*' => $this->gui->textbox('last_name',$this->form_validation->set_value('last_name')),
					'Email*' => $this->gui->textbox( 'email', $this->form_validation->set_value('email')),
					'Password*' => $this->gui->password( 'password', $this->form_validation->set_value('password')),
					'Password confirm*' => $this->gui->password( 'password_confirm', $this->form_validation->set_value('password_confirm')),
					'Company' => $this->gui->textbox( 'company', $this->form_validation->set_value('company')),
					'Phone' => $this->gui->textbox( 'phone1', $this->form_validation->set_value('phone1')).' - '.
					$this->gui->textbox( 'phone2', $this->form_validation->set_value('phone2')).' - '.
					$this->gui->textbox( 'phone3', $this->form_validation->set_value('phone3')),
					'' => $this->gui->button('submit','Create user',array('type'=>'submit'))
			)));
		}
	}
	
	function editUser($id)
	{
		$groups = new Group();
		$groups->get();
		$groups_array = array();
		foreach( $groups as $group )
			$groups_array[$group->id] = $group->name;
			
		$user = $this->ion_auth->get_user($id);
		$this->print_text(
			$this->gui->form('usersEditor/editUserAction',
				array(
					'Group'=>$this->gui->dropdown('group',$user->group_id,$groups_array),
					'Group description'=>$user->group_description,
					'Last IP Address'=>$user->ip_address,
					'User Name'=>$user->username,
					'Password'=>$this->gui->password('password'),
					'Password salt'=>$user->salt,
					'Email'=>$user->email,
					'Activation code'=>$user->activation_code,
					'Forgot password Code'=>$user->forgotten_password_code,
					'Remember me Code'=>$user->remember_code,
					'Created on'=>$user->created_on,
					'Last Login'=>$user->last_login,
					'Active'=>$this->gui->checkbox('active','active',$user->active),
					'First name'=>$this->gui->textbox('first_name',$user->first_name),
					'Last name'=>$this->gui->textbox('last_name',$user->last_name),
					''=>$this->gui->button('submit','Update user',array('type'=>'submit')).
						anchor('usersEditor/deleteUser/'.$id,'I want to delete that User')
				),'',
				array('id'=>$user->id)
			)
		);
	}
	
	function deleteUser($id){
		$user = new User($id);
		$user->delete();
		redirect('usersEditor');
	}
	
	function editUserAction(){
		$user = new User($this->input->post('id'));
		$user->group_id = $this->input->post('group');
		$user->active = ($this->input->post('active')===false)? 0:1;
		$user->save();
		$result = $this->input->post('password')==''
					?$this->ion_auth->update_user($user->id,
				array(
					'first_name'=>$this->input->post('first_name'),
					'last_name'=>$this->input->post('last_name')
				))
				:$this->ion_auth->update_user($user->id,
				array(
					'first_name'=>$this->input->post('first_name'),
					'last_name'=>$this->input->post('last_name'),
					'password'=>$this->input->post('password')
				));
		if($result)
			redirect('usersEditor');
		else
			$this->add_error($this->ion_auth->errors());
	}
}
