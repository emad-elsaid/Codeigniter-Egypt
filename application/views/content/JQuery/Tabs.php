<?php if($mode=='config'){ ?>
{
	"what is prefix" : "tab container needs ID for each content panel and the prefix will be used as the id prefix and it must be unique for that panel or a collision will be happen",
	"prefix":{ "type":"textbox", "default":"tabs" },
	"titles":{"type":"textarea"},
	"event":{"type":"textbox", "default":"click" },
	"collapsible":{"type":"checkbox", "default":false },
	"selected":{"type":"textbox", "default":"0"}
}
<?php } ?>

<?php if($mode=='layout'){ ?>
<?= count( explode( "\n", $info->titles ) ); ?>
<?php } ?>

<?php if($mode=='view'){ ?>
	
	<?php
	$ci->load->library( 'gui' );
	$widget_id = 'id'.$id;
	add( 'jquery/theme/ui.all.css' );
	add( 'jquery/jquery.js' );
	add( 'jquery/jquery-ui.js' );
	
	//assign every key to it's value
	$info->titles = explode( "\n", $info->titles );
	$content = array();
	?>
	
	<?php 
	add(<<<EOT
		<script type="text/javascript">
			$(function() {
				$(".{$widget_id}").tabs({ event: "{$info->event}", collapsible: {$info->collapsible}, selected: {$info->selected} });
			});
		</script>

EOT
	); ?>
	
	<div class="<?=$widget_id?>"> 
		<ul>
		<?php foreach( $info->titles as $index=>$item ){ ?>
			<li><a href="#<?= $info->prefix ?>-<?= $index ?>"><?= $item ?></a></li>
		<?php } ?>
		</ul>
		<?php foreach( $info->titles as $index=>$item ){ ?>
			<div id="<?= $info->prefix ?>-<?= $index ?>"><?= $cell[$index] ?></div>
		<?php } ?>
	</div>

<?php } ?>
