<?php
		$CI =& get_instance();
		add_js('jquery/jquery.js');
		add_js('jquery/jquery-ui.js');
		add_css('jquery/theme/ui.all.css');
		add_css("assets/960.gs/reset.css");
		add_css("assets/960.gs/text.css");
		
		add_dojo("dojo.parser"); 
		add_dojo("dijit.Menu");
		add_dojo("dijit.MenuBar");
		add_dojo("dijit.PopupMenuBarItem");


		// layouts used in page
		add_dojo("dijit.layout.ContentPane");
		add_dojo("dijit.layout.BorderContainer");
		add_dojo("dijit.Dialog");


?>
<html xmlns="http://www.w3.org/1999/xhtml\" >
	<head>
		<title><?= $app->name ?> <?= $app->page ?> </title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta name="generator" content="VUNSY system" />
		<?= $app->css_text() ?>
		<?= $app->js_text() ?>
		<?= $app->dojo_text() ?>
	<script type="text/javascript">
	$(function() {

		$("#aboutDlg").dialog({
			bgiframe: true,
			modal: true,
			autoOpen: false,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});

		$('#helpMenuItem').click(function() {
				$('#aboutDlg').dialog('open');
		});
	});
</script>
	</head>
	<body class="<?= $CI->vunsy->dojoStyle ?>">

<div style="position: relative; width: 100%; height: 100%;">
	<div dojoType="dijit.layout.BorderContainer" gutters="true" id="borderContainerTwo" style="width: 100%;height:100%;">
	  <?php if($app->show_toolbar){ ?>
	  <div dojoType="dijit.layout.ContentPane" region="top" splitter="false">
		<div  dojoType="dijit.MenuBar" >
			<div dojoType="dijit.PopupMenuBarItem" >
				<span>Menu</span>
				<div dojoType="dijit.Menu">
				
					<?php foreach( $app->pages as $key=>$item ){ ?>
						<div dojoType="dijit.MenuItem" onclick="window.location.href='<?=$app->app_url($key) ?>'" >
							<?=$key?>
						</div>
					<?php } ?>
					
				</div>
			</div>
			
			<div dojoType="dijit.PopupMenuBarItem" >
				<span>Help</span>
				<div dojoType="dijit.Menu">
					<div dojoType="dijit.MenuItem" onclick="$('#aboutDlg').dialog('open');" >About</div>
					<div dojoType="dijit.MenuItem" onclick="window.location.href='<?=$app->website ?>'" >Author website</div>
						
				</div>
			</div>
			
		</div>
	  </div>
	  <?php } ?>
	  <div dojoType="dijit.layout.ContentPane" region="center" id="mainSplit">
	  	<?php if($app->show_title){ ?>
		<h1 class="ui-widget-header ui-corner-all">&nbsp;<?=$app->page?></h1>
		<?php } ?>
			<?= $app->error_text() ?>
			<?= $app->info_text() ?>
			<?= $content ?>
	   </div>
	</div>
</div>

<div id="aboutDlg" title="About">
	<p><strong>App Name: </strong><?= $app->name ?></p>
	<p><strong>App Version: </strong><?= $app->ver ?></p>
	<p><strong>App Author: </strong><?= $app->author ?></p>
	<p><strong>Website: </strong>
		<a target="_blank" href="<?= $app->website ?>"><?=$app->website ?></a>
	</p>
</div>

	</body>
</html>
