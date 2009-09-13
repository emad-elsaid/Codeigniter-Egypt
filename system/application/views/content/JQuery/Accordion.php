<?php if($mode=='config'){ ?>
{
	"titles":{"type":"textarea"},
	"active":{"type":"textbox", "default":"first child" },
	"animated":{"type":"textbox", "default":"slide" },
	"event":{"type":"textbox", "default":"click" },
	"collapsible":{"type":"checkbox", "default":false },
	"autoHeight":{"type":"checkbox", "default":true },
	"fillSpace":{"type":"checkbox", "default":false }
	
}
<?php } ?>

<?php if($mode=='layout'){ ?>
<?= count( explode( "\n", $info->titles ) ); ?>
<?php } ?>

<?php if($mode=='view'){ ?>
	
	<?php
	$ci->load->library( 'gui' );
	add( 'jquery/theme/ui.all.css' );
	add( 'jquery/jquery.js' );
	add( 'jquery/jquery-ui.js' );
	
	$widget_id = 'id'.$id;
	//assign every key to it's value
	$info->titles = explode( "\n", $info->titles );
	$content = array();
	?>
	
	<?php 
	add(<<<EOT
		<script type="text/javascript">
			$(function() {
				$(".{$widget_id}").accordion({ header: "h3", active: "{$info->active}", animated: "{$info->animated}", event: "{$info->event}", collapsible: {$info->collapsible}, autoHeight: {$info->autoHeight}, fillSpace: {$info->fillSpace}  });
			});
		</script>

EOT
	); ?>
	
	<div class="<?=$widget_id?>">    
		<?php foreach( $info->titles as $index=>$item ){ ?>
			<h3><a href="#"><?= $item ?></a></h3>
		    <div><?= $cell[$index] ?></div>
		<?php } ?>
	</div>

<?php } ?>
