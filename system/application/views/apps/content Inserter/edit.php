<?php
if( $ci->uri->segment(5)==FALSE )
	$ci->app->add_error("you didn't select a content" );
else
{
	$id = $ci->uri->segment(5);
	$cont = new Content();
	$cont->get_by_id($id);
	
	$can_edit = $cont->can_edit();
	$can_delete = $cont->can_delete();
	$parent = $cont->parent_content;
	$cell = $cont->cell;
	$sort = $cont->sort;
	
	$sec = $ci->uri->segment(6);
	
	$p = new Content();
	$p->get_by_id($cont->parent_content);
	
	$img_url = $ci->app->full_url.'images/';
?>
<style>
.links div {
display:inline-block;
padding:10px;
width:200px;
}
.links div a {
font-size:1.5em;
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

<div class="links" >
	<?php if( $can_edit ){ ?>
		<div><a href="<?= $ci->app->app_url("data/$id") ?>" >
			<img src="<?=$img_url?>edit.png" >Edit
		</a></div>
		<div><a href="<?= $ci->app->app_url("up/$id") ?>" >
			<img src="<?=$img_url?>move up.png" >Move Up
		</a></div>
		<div><a href="<?= $ci->app->app_url("down/$id") ?>" >
			<img src="<?=$img_url?>move down.png" >Move Down
		</a></div>
	<?php } ?>
	
	<?php if( $p->can_addin() ){ ?>
		<div><a href="<?= $ci->app->app_url("index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>" >
			<img src="<?=$img_url?>add before.png" >Add Before
		</a></div>
		
		<div><a href="<?= $ci->app->app_url("index/{$sec}/$parent/$cell/".($sort+1)) ?>" >
			<img src="<?=$img_url?>add after.png" >Add After
		</a></div>
	<?php } ?>
	
	<?php if( $can_delete ){ ?>
		<div><a href="<?= $ci->app->app_url("delete/$id") ?>" >
		<img src="<?=$img_url?>delete.png" >Delete
		</a></div>
		
		<div><a href="<?= $ci->app->app_url("recycle/$id") ?>" >
		<img src="<?=$img_url?>recycle.png" >Put in Recycle
		</a></div>
	<?php } ?>
	
		<div><a class="iframe" href="<?= $ci->app->app_url("info/$id") ?>" >
		<img src="<?=$img_url?>info.png" >Information
		</a></div>
</div>
<?php } ?>
