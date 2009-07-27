<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
add_dojo( 'dijit.form.Button' );
add_dojo('dijit.Menu');

$p = new Content();
$p->get_by_id($parent);

if( $can_edit OR $p->can_addin() OR $can_delete ){
?>
<button dojoType="dijit.form.ComboButton" iconClass="dijitEditorIcon dijitEditorIconSave" style="font-size:13px">
	<span>Edit (<?= $id ?>)</span>
	<script type="dojo/method" event="onClick" args="evt">
	<?php if( $can_edit ){ ?>
		open("<?= site_url("admin/app/content Inserter/data/$id") ?>","","height=500,width=500");
	<?php }else{ ?>
		alert("You have no permission to edit that content ! \nplease check your root use for permissions");
	<?php } ?>
	</script>
	<div dojoType="dijit.Menu" style="font-size:13px">
		<?php 
		if( $p->can_addin() ){
		?>
		<div dojoType="dijit.MenuItem" label="Add before"  iconClass="dijitEditorIcon dijitEditorIconUndo">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>","","height=500,width=500");
			</script>
		</div>
		<div dojoType="dijit.MenuItem" label="Add after" style="font-size:13px" iconClass="dijitEditorIcon dijitEditorIconRedo">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/".($sort+1)) ?>","","height=500,width=500");
			</script>
		</div>
		<?php } ?>
		<?php if( $can_delete ){ ?>
		<div dojoType="dijit.MenuItem" label="Delete" style="font-size:13px" iconClass="dijitEditorIcon dijitEditorIconDelete">
			<script type="dojo/method" event="onClick" args="evt">
				dojo.xhrGet({
						url: "<?= site_url("admin/app/content Inserter/delete/$id") ?>",
						load: function( data ){ 
							document.location.reload();
						},
						error: function( data ){
							alert( " Error occured with deletion !\n"+ data );
						}
				});
			</script>

		</div>
		<?php } ?>
	</div>
</button>
<?php } ?>
<?= $text ?>
