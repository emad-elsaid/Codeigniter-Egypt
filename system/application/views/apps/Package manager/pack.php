<?php
$ci =& get_instance();
$ci->load->library('gui');
?>
<script language="javascript" >
dojo.addOnLoad(function(){
	dojo.connect( dijit.byId('addFile'),'onClick', null, function(){
			files = dijit.byId('files');
			packfile = dijit.byId('packfile');
			
			if( packfile.getValue()!='' )
			{
				if( files.getValue() != '' )
					files.setValue( files.getValue()+"\n"+packfile.getValue() );
				else
					files.setValue( packfile.getValue() );
					
				packfile.setValue('');
			}
	} );
	dojo.connect( dijit.byId('addFolder'),'onClick', null, function(){
			files = dijit.byId('files');
			packfolder = dijit.byId('packfolder');
			
			if( packfolder.getValue()!='' )
			{
				if( files.getValue() != '' )
					files.setValue( files.getValue()+"\n"+packfolder.getValue() );
				else
					files.setValue( packfolder.getValue() );
					
				packfolder.setValue('');
			}
	} );
});
</script>
<table>
	<tr>
		<td>
			<?php
			$addForm['File :'] 	= 		$ci->gui->file( 'packfile' );
			$addForm['File :'] .= 		$ci->gui->button( 'add', 'Add', 'id="addFile"' );
			$addForm['Folder :'] = 	$ci->gui->folder( 'packfolder' );
			$addForm['Folder :'] .= 	$ci->gui->button( 'add', 'Add', 'id="addFolder"' );
			echo $ci->gui->form( '#', $addForm );
			?>
		</td>
		<td>
			<?php 
			$form['Plugin Name : '] = $ci->gui->textbox( 'pluginName', 'Untitled' );
			$form['Version : '] = $ci->gui->textbox( 'version', '1.0' );
			$form['Author : '] = $ci->gui->textbox( 'author', 'Vunsy system' );
			$form['Website : '] = $ci->gui->textbox( 'website', 'http://' );
			$form['Description : '] = $ci->gui->textarea( 'description' );
			$form['Files to pack : '] = $ci->gui->textarea( 'files', '', ' id="files" cols="50" ' ); 
			$form[''] = $ci->gui->button( 'packfiles', 'Pack files', 'type="submit"' );
			echo $ci->gui->form( $ci->app->app_url('packaction'), $form );
			?>
		</td>
	</tr>
</table>
