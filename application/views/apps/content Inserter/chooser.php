<style>
.links div {
display:inline-block;
padding:10px;
width:190px;
}
.links div a {
font-size:1.2em;
text-decoration:none;
color: black;
display: block;
}

.links div a:hover {
	color: #003A49;
}
.links img {
float:left;
margin-right:10px;
}
</style>	
<?php
add('jquery/jquery.js');
add(<<<EOT
<script language="javascript" >
$(function(){
	$('.links div').click(function(){
		v = $(this).attr('rel');
		$(this).siblings('input').val(v);
		$(this).parents('form').submit();
	});	
});
</script>
EOT
);

$ci->load->library('gui');
$ci->load->helper('directory');
$contents = directory_map( 'system/application/views/content' );

function icon( $icon, $text, $path )
{
	
	$path = ($path=='')? '' : substr( $path, 1).'/';
	$text = substr( $text, 0, strrpos($text, '.') );
	$base = base_url();
	return <<<EOT
	<div rel="{$path}{$text}.php" ><a href="#">
	<img src="$base$icon" >$text
	</a></div>
EOT;
}

function render( $path, $arr )
{
	$header = substr( $path, 1);
	$txt =  "<hr /><h3>$header</h3>";
	foreach ($arr as $key=>$value) 
	{
		if( is_string($value) and $value!='index.html' )
		{
			$img = substr( $value, 0, strrpos($value, '.') ).'.png';
			$img_p = 'assets/admin/content/'.$path.'/';
			if( file_exists( $img_p.$img ) )
			{
				$txt .= icon( $img_p.$img, $value, $path );
			}
			else
			{
				$txt .= icon( 'assets/admin/content/template.png', $value, $path );
			}
		}
		
	}
	foreach ($arr as $key=>$value)
	{
		if( is_array($value) )
		{
			$txt .= render( $path.'/'.$key, $value );
		}
	}
	
	return $txt;
}
$chooser = '<div class="links" >'.$ci->gui->hidden( 'path' ).render( '', $contents).'</div>';

$hidden = array(
				'parent_section'=>$ci->uri->segment(5)
				,'parent_content'=>$ci->uri->segment(6)
				,'cell'=>$ci->uri->segment(7)
				,'sort'=>$ci->uri->segment(8) 
				);
				
echo $ci->gui->form(
		 $ci->app->app_url('Data Editor'), 
		array(""=>$chooser),
		'',
		$hidden
	);

$recycle = new Content();
$recycle->get_by_parent_content(0);
if( count($recycle->all) >0 )
{
	foreach ( $recycle->all as $item ) 
	{
		$s = new Section();
		$s->get_by_id( $item->parent_section );
		$recycle_array->{$item->id} = $item->path.'('.$s->name.')';
	}
	
	echo $ci->gui->form(
			 $ci->app->app_url('restore'), 
			array(
		"from Recycle bin"=>$ci->gui->dropdown( "id", '',$recycle_array )
		,""=> $ci->gui->button( "", "Restore that content", array("type"=>"submit") )
			)
			,''
			,$hidden
	);
}
?>
