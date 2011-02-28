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
<?= $info->columns*$info->rows ?>
<?php elseif( $mode=='view' ): ?>
<table style="<?= $info->style ?>">
<?php for( $i=0; $i<$info->rows; $i++ ){ ?>
	 <tr>
	<?php for( $j=0; $j<$info->columns; $j++ ){ ?>
		 <td>
		 <?= $cell[$i*$info->columns+$j] ?>
		 </td>
	<?php } ?>
	 </tr>
<?php } ?>
 </table>
<?php endif; ?>
