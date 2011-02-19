<?php
$ci =& get_instance();
$ci->vunsy->mode( 'view' );
$ci->load->library( 'gui' );
$text = anchor( $ci->app->app_url('Edit Mode') , 'Go to Edit Mode' );
?>
<center><?= $ci->gui->button('',$text ) ?></center>

