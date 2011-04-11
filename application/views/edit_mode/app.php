<?php
	theme_add("assets/style/reset.css");
	theme_add("assets/style/text.css");
	theme_add("assets/style/style.css");
	
	theme_add("dijit.Menu");
	theme_add("dijit.MenuBar");
	theme_add("dijit.PopupMenuBarItem");


	// layouts used in page
	theme_add("dijit.layout.ContentPane");
	theme_add("dijit.layout.BorderContainer");
	theme_add("dijit.Dialog");
?>
<?=theme_doctype()?>
<html>
	<head>
		<?= theme_head() ?>
		
	<style>
	html,body{
		width: 99.9%;
		height: 99.8%;
	}
	body{
		font-size: 12px;
	}
	label{
		display: block;
		text-align: right;
		white-space:nowrap;
		padding:3px;
	}
	td{
		vertical-align: top;
		padding: 3px;
	}
	</style>
	</head>
	<body class="<?=theme_dojotheme()?>" >

<div dojoType="dijit.layout.BorderContainer" gutters="false" id="borderContainerTwo" style="width: 100%;height:100%;">
	
	  <?php if($app->show_toolbar){ ?>
	  <div dojoType="dijit.layout.ContentPane" region="top" splitter="false">
		<div  dojoType="dijit.MenuBar" >
			<div dojoType="dijit.PopupMenuBarItem" >
				<span><?=lang('system_menu')?></span>
				<div dojoType="dijit.Menu">
				
					<?php foreach( $app->pages as $key=>$item ){ ?>
					<div dojoType="dijit.MenuItem" onclick="window.location.href='<?=site_url(strtolower(get_class($app)).'/'.$key) ?>'" >
							<?=$item?>
						</div>
					<?php } ?>
					
				</div>
			</div>
			
			<div dojoType="dijit.PopupMenuBarItem" >
				<span><?=lang('system_help')?></span>
				<div dojoType="dijit.Menu">
					<div dojoType="dijit.MenuItem" onclick="dijit.byId( 'aboutDlg' ).show();" ><?=lang('system_about')?></div>
					<div dojoType="dijit.MenuItem" onclick="window.location.href='<?=$app->website ?>'" ><?=lang('system_author_website')?></div>
						
				</div>
			</div>
			
		</div>
	  </div>
	  <?php } ?>
	  
	  
<!-- \\\\\\\\\\\\\\\\\\\That is the PAGE HTML/////////////////// -->
		<div dojoType="dijit.layout.ContentPane" region="center" id="mainSplit">
			<?= $app->error_text() ?>
			<?= $app->info_text() ?>
			<?= $content ?>
		</div>
<!-- \\\\\\\\\\\\\\\\\\\That is the PAGE HTML/////////////////// -->
	</div>


<!-- \\\\\\\\\\\\\\\\\\\That is the help dialog HTML/////////////// -->
		<div  dojoType="dijit.Dialog" id="aboutDlg" title="<?=lang('system_about')?>">
			<p><strong><?=lang('system_app_name')?></strong> <?= $app->name ?></p>
			<p><strong><?=lang('system_app_ver')?></strong> <?= $app->version ?></p>
			<p><strong><?=lang('system_app_author')?></strong> <?= $app->author ?></p>
			<p><strong><?=lang('system_author_website')?></strong>
				<a target="_blank" href="<?= $app->website ?>"><?=$app->website ?></a>
			</p>
		</div>
<!-- \\\\\\\\\\\\\\\\\\\That is the help dialog HTML/////////////// -->	
		<?=theme_foot()?>
	</body>
</html>
