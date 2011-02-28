<?php if( $mode=='config' ): ?>
tip :  "you can user any text and the following text will be replaced with the variables values: <br>@site : site name<br>@user : the current username<br>@section : the current section name<br>@level : the user level name<br>@day : the current server day (1-31)<br>@month : the current server month (1-12)<br>@year : the current server year<br>exmple to display date: Current date is @year-@month-@day"
text :  
	type: textarea 
<?php elseif( $mode=='layout' ): ?>
0
<?php elseif( $mode=='view' ): ?>
<?php
$replaces = array(
"@site"		=> $ci->config->item('site_name'),
"@user"		=> $ci->system->user->username,
"@section"	=> $ci->system->section->name,
"@level"	=> $ci->system->level->name,
"@day"		=> date('j'),
"@month"	=> date('n'),
"@year"		=> date('Y')
);
$formatted = $info->text;
foreach ($replaces as $key=>$value) 
{
	$formatted = str_replace( $key, $value, $formatted );
}

echo $formatted;
?>
<?php endif; ?>
