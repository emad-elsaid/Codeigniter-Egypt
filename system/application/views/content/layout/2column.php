<?php if($mode=='config') echo 2;// number of cells
else{
add_css( 'jquery/theme/ui.all.css' );
?>
<table width="100%" class="ui.helper.reset" >
	  <tr>
		  <td>
		  	<?= $cell[0] ?>
		  </td>
		  <td>
		  	<?= $cell[1] ?>
		  </td>
	  </tr>
  </table>
<?php } ?>
