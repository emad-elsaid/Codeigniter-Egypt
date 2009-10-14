<?php
	$help = $ci->load->database( 'help', TRUE );
	/*$help->query(
		"CREATE TABLE posts (
	    id  INTEGER NOT NULL ,
	    title  TEXT,
	    text  TEXT,
	   PRIMARY KEY ( id )
		);"
	);*/
	$ci->load->library( 'gui' );
	
	//generating the view
	
	//titles ===========================
	$titles = $help	->from("posts")
					->select(array('id', 'title'))
					->get()
					->result();
	
	$titlesHTML = "<ul id=\"titles\">\n";
	foreach( $titles as $item )
	{
		$titlesHTML .= "\t<li rel=\"$item->id\" >$item->title</li>\n";
	}
	$titlesHTML .= "</ul>\n";
	
	
	//viewer DIV =========================
	$viewerURL = $ci->app->app_url('post', TRUE).'/';
	$viewDIV = "<div id=\"view\" rel=\"$viewerURL\" ></div>";
	
	echo $ci->gui->hbox( array( $titlesHTML, $viewDIV ) );
	
	//scripts and styles ===========================
	add( $ci->app->full_url.'style.css');
	add( 'jquery/jquery.js');
	add( <<<EOT
<script language="javascript">
$(function(){
	$('#titles li').click(function(){
		
		url = $('#view').attr('rel')+$(this).attr('rel');
		$.get(url,function(response){
				$('#view').html(response);
		});
	});
});
</script>	
EOT
);

?>
