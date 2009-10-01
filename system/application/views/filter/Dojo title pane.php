<?php
$ci =& get_instance();
$ci->load->library( 'gui' );

$cont = new Content();
$cont->get_by_id($id);

echo $ci->gui->titlepane( $cont->title, $text);
?>
