<?php if( $mode=='config' ): ?>
columns:
	type:number
	default:1
rows:
	type:number
	default:1
style:
	type:textarea
<?php elseif( $mode=='layout' ): ?>
<?= $columns*$rows ?>
<?php elseif( $mode=='view' ): ?>
<table style="<?= $style ?>">
<?php for( $i=0; $i<$rows; $i++ ){ ?>
	 <tr>
	<?php for( $j=0; $j<$columns; $j++ ){ ?>
		 <td>
		 <?= $cell[$i*$columns+$j] ?>
		 </td>
	<?php } ?>
	 </tr>
<?php } ?>
 </table>
<?php endif; ?>
