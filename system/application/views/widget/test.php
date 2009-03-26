<?php if( $mode=='config' ) { ?>
<? }else{
	$ci =& get_instance();
	$ci->load->library( 'gui' );
echo $ci->gui->titlepane('title','text');
}
?>
