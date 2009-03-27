<?php
session_start();
$data = unserialize($_COOKIE['ci_session']);
if((! isset($data['id'])||!isset($data['level']))
	&& $data['id']!='-1' || $data['level']!='-1' )exit('access denied!');
?>
