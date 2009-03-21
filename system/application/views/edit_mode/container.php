<div style="font-size:13px">
<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
add_dojo( 'dijit.form.Button' );
add_dojo('dijit.Menu');
?>
<button dojoType="dijit.form.ComboButton" iconClass="dijitEditorIcon dijitEditorIconSave">
	<span>Edit</span>
	<script type="dojo/method" event="onClick" args="evt">
		open("<?= site_url("admin/app/content Inserter/data/$id") ?>");
	</script>
	<div dojoType="dijit.Menu" style="font-size:13px">
		<div dojoType="dijit.MenuItem" label="Add before"  iconClass="dijitEditorIcon dijitEditorIconUndo">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>");
			</script>
		</div>
		<div dojoType="dijit.MenuItem" label="Add after" style="font-size:13px" iconClass="dijitEditorIcon dijitEditorIconRedo">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/".($sort+1)) ?>");
			</script>
		</div>
		<div dojoType="dijit.MenuItem" label="Delete" style="font-size:13px" iconClass="dijitEditorIcon dijitEditorIconDelete">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/delete/$id") ?>");
			</script>
		</div>
	</div>
</button>
</div>
<?= $text ?>
