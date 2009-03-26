<?php
$ci =& get_instance();
$ci->vunsy->mode( 'edit' );
$ci->load->library( 'gui' );
$text = anchor( $ci->app->app_url('View Mode') , 'Go to View Mode' );
?>
<center><?= $ci->gui->button('',$text ) ?></center>
