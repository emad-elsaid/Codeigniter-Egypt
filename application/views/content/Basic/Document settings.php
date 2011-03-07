<?php if( $mode=='config' ): ?>
background attributes:if you specified a background image all the related attributes would be wrriten as well in the rendered HTML otherwise they will be ignored
background_image :  
	type : file 
repeat :  
	type : dropdown
	options :
		no-repeat:no-repeat
		repeat:repeat
		repeat-x:repeat-x
		repeat-y:repeat-y
	default : 0 
attachment :  
	type : dropdown 
	options : 
		scroll:scroll
		fixed:fixed 
	default: 0 
horizontal_position :  
	type : textbox 
	default : left 
vertical_position :  
	type : textbox 
	default : top 
style sheets: you can include Reset and Text stylesheets from here 
Resetstyle:
	type:checkbox
Textstyle:
	type:checkbox
extra attributs:you can specify extra body classes and style
class:
	type:textbox
style:
	type:textarea
favIcon:
	type:file
javascript_files : 
	type:file list
css_files : 
	type:file list
<?php elseif( $mode=='layout' ): ?>
0
<?php elseif( $mode=='view' ): ?>

<?php 
$ci =& get_instance();
if( $ci->system->mode=='edit' )
{
	
	if( $ci->ion_auth->is_admin() )
	{
		$ci->load->library( 'gui' );
		echo $ci->gui->info( 'Document settings here' );
	}
}

?>

<?php
	$local = base_url();
	
	if( $Resetstyle ) theme_add( 'assets/style/reset.css' );
	if( $Textstyle ) theme_add( 'assets/style/text.css' );
	if( !empty($favIcon) ) theme_add('<link rel="icon" href="'.base_url().$favIcon.'">');
	theme_add(explode("\n",$javascript_files));
	theme_add(explode("\n",$css_files));
	
	$style = '';
	if( $background_image != '' )
	{
		$style .= "background-image: url({$local}{$background_image});";
		$style .= "background-position: {$horizontal_position} {$vertical_position};";
		$style .= "background-repeat: {$repeat};";
		$style .= "background-attachment: {$attachment};";
	}
	$style .= $style;
	if( $style!='' )
	{
		theme_add("
<style>
body{
	{$style}
}
</style>

		");
		if(!empty($class))
		{ 
			theme_add('jquery/jquery.js');
			theme_add(
<<<EOT
<script language="javascript" >
	$(function(){
		$(document).addClass('{$class}');
	});
</script>
EOT
			);
		}
	}
?>
<?php endif; ?>
