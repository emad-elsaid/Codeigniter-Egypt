<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"parent":{"type":"number"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
add_css( 'assets/jquery tree/jquery.treeview.css' );
?>
<?php
if( ! function_exists('jquery_treenode' ) )
{
	function jquery_treenode( $section )
	{
		$url = site_url( $section->id );
		$name = $section->name;
		$text = '';
		
		$sub_sections = new Section();
		$sub_sections->get_by_parent_section( $section->id );
		if( count($sub_sections->all) > 0 )
		{
			$text = '<ul>';
			foreach( $sub_sections->all as $item )
			{
				$text .= jquery_treenode( $item );
			}
			$text .= '</ul>';
		}
		
		if(count($sub_sections->all) > 0 )
		{
		return  "<li><span><b>{$name}</b></span>{$text}</li>";
		}
		else
		{
			return  "<li><a href=\"{$url}\" >{$name}</a></li>";
		}
	}
}
?>
<?php 
		$section_p = new Section();
		$section_p->get_by_parent_section( $info->parent );
		if( count($section_p->all) > 0 )
		{
			$childless = array();
			echo '<table class="treeview treeview-famfamfam" width="100%" ><tr>';
			foreach( $section_p->all as $item )
			{
				$child = new Section();
				$child->get_by_parent_section( $item->id );
				if( count($child->all)>0 )
				{
					echo "\n<td style=\"vertical-align: text-top;font-size: 0.8em;\">\n<ul class=\"treeview treeview-famfamfam\">";
					echo jquery_treenode( $item );
					echo "\n</ul>\n</td>";
				}
				else
				{
					array_push( $childless, $item );
				}
			}
			echo "\n<td style=\"vertical-align: text-top;font-size: 0.8em;\">\n<ul class=\"treeview treeview-famfamfam\">";
			foreach( $childless as $item )
			{
				
					echo jquery_treenode( $item );
					
			}
			echo "\n</ul>\n</td>";
			echo '</tr></table>';
		}
		?>
<?php } ?>
