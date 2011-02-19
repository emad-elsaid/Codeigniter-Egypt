<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"suitability"		: "this plugin is suitable for nl2ul and nl2br filters",
	"username" 	: { "type":"textbox", "default":"Username" },
	"password" 	: { "type":"textbox", "default":"Password" },
	"submit" 		: { "type":"textbox", "default":"Login" },
	"dojo"		: { "type":"checkbox", "label":"Use dojo textbox and button", "default":true},
	"replace_rule"	: " if you checked the below checkbox the form will be replaced with logout link else the login form will be allways visible for guest and normal user",
	"replace" 		: { "type":"checkbox", "label":"Replace form with logout link on logeed user" },
	"logout_rule"	: "@user will be replaced with user name in the below textboxbox",
	"logout"		: { "type":"textbox", "label":"logout text", "default":"Logout, @user" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php if( $ci->vunsy->user->is_guest() or $info->replace==FALSE ): ?>
	<?php
		if( $info->dojo ){
			$ci->load->library('gui');
			$user_box		= $ci->gui->textbox('user');
			$pass_box		= $ci->gui->password('pass');
			$submit_button	= $ci->gui->button( '', $info->submit, array('type'=>'submit') );
		}
		else
		{
			$user_box		= '<input name="user" type="text" >';
			$pass_box		= '<input name="pass" type="password" >';
			$submit_button	= '<button type="submit" >'.$info->submit.'</button>';
		}	
	?>
		
		
		
<form action="<?=site_url('login')?>" method="POST" ><label for="user" ><?=$info->username?></label><?=$user_box?>

<label for="pass" ><?=$info->password?></label><?=$pass_box?>

<?=$submit_button?></form>
<?php else: ?>
<a href="<?=site_url('logout')?>" ><?=str_replace( '@user', $ci->vunsy->user->name, $info->logout )?></a>
<?php endif; ?>
<?php } ?>
