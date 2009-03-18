<div vunsyID="<?= $id ?>" >

<?php

$ci =& get_instance();
$ci->load->library( 'gui' );
add_dojo( 'dijit.form.Button' );
add_dojo('dijit.Menu');
add_js( 'jquery/jquery.js' );
?>

<button dojoType="dijit.form.ComboButton">
	<span>Edit</span>
	<script type="dojo/method" event="onClick" args="evt">
		open("<?= site_url("admin/app/content Inserter/data/$id") ?>");
	</script>
	<div name="foo" dojoType="dijit.Menu">
		<div dojoType="dijit.MenuItem" label="Add before">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>");
			</script>
		</div>
		<div dojoType="dijit.MenuItem" label="Add after">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/".($sort+1)) ?>");
			</script>
		</div>
		<div dojoType="dijit.MenuItem" label="Delete">
			<script type="dojo/method" event="onClick" args="evt">
				open("<?= site_url("admin/app/content Inserter/delete/$id") ?>");
			</script>
		</div>
		<?php if( $sort>0 ){ ?>
		<div dojoType="dijit.MenuItem" label="Move up">
			<script type="dojo/method" event="onClick" args="evt">
				
			</script>
		</div>
		<?php } ?>
		<div dojoType="dijit.MenuItem" label="Move down">
			<script type="dojo/method" event="onClick" args="evt">
				
			</script>
		</div>
	</div>
</button>
<?= $text ?>
</div>
