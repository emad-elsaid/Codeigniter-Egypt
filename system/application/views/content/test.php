<?php if($mode=='config'){ ?>
{
	"tabs": { "type":"number" }
}
<?php } ?>

<?php if($mode=='layout'){
		echo $info->tabs;
} ?>

<?php if($mode=='view'){ 
		$ci =& get_instance();
		$ci->load->library('gui');
		echo $ci->gui->hbox($cell);
} ?>
