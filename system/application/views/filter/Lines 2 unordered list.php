<ul>
<?php $lines = explode( "\n", trim($text) ); ?>
<?php foreach( $lines as $line ): ?>
	<li><?=$line?></li>
<?php endforeach;?>
</ul> 
