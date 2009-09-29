<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*! \addtogroup Libraries
 * Gui class:  used to generate multiple HTML text 
 * used with content, applications, it's very useful with 
 * generating forms, tables, titlepanels, splitters, error messages,
 * information messages, colorpickers and a lot more
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Gui {
	
	function Gui()
	{
		$CI =& get_instance();
		$CI->load->helper('form');
		add_dojo( "dojo.parser" );
	}
	
	
	/**
	 * a form maker 
	 * @param $action: target page of the form
	 * @param $data: array of key( label text )=>value(corresponding input HTML ), values can be generated with functions like textbox,color,file,folder,app.etc
	 * @param $attributes: array or object of key(attribute)=>value(value of the attribute)
	 * @param $hidden: array of hiden fields and values as key=>value
	 */
	function form($action = '', $data=array(), $attributes = array(), $hidden = array())
	{
		
		add_dojo( "dijit.form.Form" );
		$attributes = $this->attribute( $attributes, 'dojoType', 'dijit.form.Form') ;
		$attributes = $this->_attributes_to_string( $attributes );
		
		$text =	form_open( $action, $attributes, $hidden).
					"\n\t<table>";
		foreach( $data as $key=>$value )
		{
			$text .=	"\n\t<tr>".
						"\n\t<td width=\"1%\" >".form_label( $key )."</td>".
						"\n\t<td>".$value."</td>".
						"\n\t</tr>";
		}
		$text .= "\n\t</table>".
					form_close();
		
		return $text;
	}
	
	/**
	 * generate hidden field HTML
	 * @param $NAME: hidden field name
	 * @param $value: hidden field value
	 */
	function hidden($NAME='', $value='' )
	{
		return form_hidden( $NAME, $value );
	}
	
	/**
	 * File chooser using the fsbrowser, use it with CAUTION
	 * it makes a dojo text input linked with a jquery fsbrowser
	 * @param $connector: path to php file to use as ajax backend
	 * @param $NAME: text input name and id
	 * @param $value: text input value
	 * @param $attr: attributes->value associative array
	 * @param $param: sfbrowser config object paramter->value associative array
	 * @param $style: property->value associative array
	 */
	function activeTree( $connector='', $NAME='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		
		//adding the nessecery javascripts and CSSs
		add_js	( "jquery/jquery.js");
		add_js	( "jquery/fileTree/jqueryFileTree.js" );
		add_js	( "jquery/easing.js" );
		add_css	( "jquery/fileTree/jqueryFileTree.css" );
		add_css	( "jquery/theme/ui.all.css");
		add_dojo	( "dijit.Dialog" );
		
		//preparing the paramters
		if(!isset($param['root']))
			$param['root'] =  PATH;
		else
			$param['root'] =  PATH.$param['root'];
			
		if( ! is_dir( $param['root'] ) ) 
			$param['root'] = str_replace( '/','\\',$param['root'] );
			
		$root		= $param['root'];
		$script	= $connector;
		
		$param	= $this->_params_to_js($param);
		
		// adding the styles if not there
		
		$style = $this->style( $style, 'width', '400px', FALSE );
		$style = $this->style( $style, 'height', '300px', FALSE );
		$style = $this->style( $style, 'overflow', 'auto', FALSE );
		$style = $this->_array_to_style( $style );
		
		$attr = $this->attribute( $attr, 'id',  "{$NAME}dlg" );
		$attr = $this->attribute( $attr, 'dojoType',  "dijit.Dialog" );
		$attr = $this->attribute( $attr, 'title',  "Choose an Item" );
		$attr = $this->_attributes_to_string( $attr );
		
		//the output is here
		$root = addslashes( $root );
		$t = $this->textbox( $NAME, $value, 
				array(
					'onclick'=>"dijit.byId('{$NAME}dlg').show();"
					,'id' => $NAME
				)
		);
		return <<<EOT
		$t
		<div {$attr} >
			<div id="{$NAME}DIV" style="$style" ></div>
		</div>
		<script language="javascript" >
		$(document).ready( function() {
    	$('#{$NAME}DIV').fileTree({script:'$script' $param }, 
		function(file) {
			file = file.split( '{$root}')[1];
        	dijit.byId('$NAME').setValue( file );
			dijit.byId('{$NAME}dlg').hide();
    	});
		});
		</script>
EOT;
	}
	
	/**
	 * generate file chooser HTML using activeTree with connector 
	 * controller file remote/file function
	 */
	function file( $NAME='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		return $this->activeTree(site_url('remote/file'), $NAME, $value,$attr,$param,$style);
	}
	
	/**
	 * generate model chooser HTML using activeTree with connector 
	 * controller file remote/file function
	 */
	function model( $NAME='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		$param['root'] = 'system/application/models/';
		return $this->activeTree(site_url('remote/file'), $NAME, $value,$attr,$param,$style);
	}
	
	/**
	 * generate folder chooser HTML using activeTree with connector 
	 * controller file remote/file function
	 */
	function folder( $NAME='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		return $this->activeTree(site_url('remote/dir'), $NAME, $value,$attr,$param,$style);
	}
	
	/**
	 * generate application chooser HTML using activeTree with connector 
	 * controller file remote/file function
	 */
	function app( $NAME='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		$param['root'] = 'system/application/views/apps/';
		return $this->activeTree(site_url('remote/dir'), $NAME, $value,$attr,$param,$style);
	}
	
	/**
	 * a color chooser field linked with a dojo color picker dialog
	 */
	function color( $NAME='',$value='', $attr=array() )
	{
		add_dojo( "dojox.widget.ColorPicker" );
		add_dojo( "dijit.Dialog" );
		add_dojo( "dijit.form.TextBox" );
		add_js( "jquery/jquery.js" );
		add_css( "dojo/dojox/widget/ColorPicker/ColorPicker.css" );
		
		$value = form_prep( $value );
		
		$attr = $this->attribute( $attr, 'type', 'text');
		$attr = $this->attribute( $attr, 'id', $NAME );
		$attr = $this->attribute( $attr, 'name', $NAME );
		$attr = $this->attribute( $attr, 'dojoType', "dijit.form.TextBox" );
		$attr = $this->attribute( $attr, 'onclick', "dijit.byId('{$NAME}dlg').show()" );
		$attr = $this->attribute( $attr, 'value', $value );
		
		$attr = $this->_attributes_to_string( $attr );
		
		$text = <<< EOT
		<input $attr >
		<span id="{$NAME}box" class="ui.helper.reset ui-corner-all" style="width:20px;height:20px;background-color:$value" >&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<div id="{$NAME}dlg" dojoType="dijit.Dialog" title="Choose a color" >
		<div  dojoType="dojox.widget.ColorPicker"
		showHsv="false"
  		showRgb="false" 
		hexCode="$value"
		liveUpdate="true"
		 onChange="dijit.byId('{$NAME}').setValue(this.value);$('#{$NAME}box').css('background-color',this.value);" class="ui.helper.clearfix" >
		</div></div>
EOT;
		 return $text;
	}
	
	/**
	 * a date field picker using dojo
	 */
	function date( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.DateTextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.DateTextBox\" name=\"$NAME\" value=\"$value\" $attr >";
		return $text;
	}
	
	/**
	 * a Time chooser input field
	 */
	function time( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.TimeTextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TimeTextBox\" name=\"$NAME\" value=\"$value\" $attr >";
		return $text;
	}
	
	/**
	 * an input field with dojo
	 */
	function textbox( $NAME='', $value='', $attr=array() )
	{
		add_dojo("dijit.form.TextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TextBox\" name=\"$NAME\" value=\"$value\" $attr >";
		return $text;
	}
	
	/**
	 * an button with dojo
	 */
	function button( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Button");
  
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<button dojoType=\"dijit.form.Button\" name=\"$NAME\" $attr >$value</button>";
		return $text;
	}
	
	/**
	 * an tooltipbutton with dojo
	 * @param $text: button text
	 * @param $dialog: tooltip dialog content text
	 */
	function tooltipbutton( $text='', $dialog='', $attr=array() )
	{

		add_dojo("dijit.form.Button");
		add_dojo( "dijit.Dialog" );
  
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
<div dojoType="dijit.form.DropDownButton" $attr >
  <span>$text</span>
  <div dojoType="dijit.TooltipDialog" >
  $dialog
  </div>
</div>
EOT;
	}
	
	/**
	 * an password field with dojo
	 */
	function password( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.TextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"password\" dojoType=\"dijit.form.TextBox\" name=\"$NAME\" value=\"$value\" $attr >";
		return $text;
	}
	
	/**
	 * an input number spinner with dojo
	 */
	function number( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.NumberSpinner");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.NumberSpinner\"name=\"$NAME\" value=\"$value\" $attr >";
		return $text;
	}
	
	/**
	 * a textarea field auto grown
	 */
	function textarea( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Textarea");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<textarea  dojoType=\"dijit.form.Textarea\" name=\"$NAME\" $attr >$value</textarea>";
		return $text;
	}
	
	/**
	 * a permission field auto grow textarea
	 */
	function permission( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Textarea");
		
		$ci =& get_instance();
		$ci->load->helper('perm');
		$p_arr = perm_array();
		$t_text = 	"<b>Boolean variables : </b>".implode('|', array_keys($p_arr['boolVars']) )
						."<br><b>Variables : </b>".implode('|', array_keys($p_arr['vars']) );
		
		$value = form_prep( $value );
		$id_g = 'p'.microtime();
		
		$attr = $this->attribute( $attr, 'id', $id_g , FALSE);
		$id_g = $attr['id'];
		
		$text = $this->textarea( $NAME, $value, $attr)
					.$this->tooltip($id_g, $t_text );
		return $text;
	}
	
	/**
	 * an richtext editor with dojo
	 */
	function editor( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.Editor");
		add_dojo("dijit._editor.plugins.AlwaysShowToolbar");
		add_dojo("dijit._editor.plugins.EnterKeyHandling");
		add_dojo("dijit._editor.plugins.TextColor");
		add_dojo("dijit._editor.plugins.LinkDialog");
		add_dojo("dijit._editor.plugins.FontChoice");
		add_dojo("dijit._editor.plugins.ToggleDir");
  
		$attr['plugins'] = "['undo','redo','|','cut','delete','copy','paste','|','bold','italic','underline','strikethrough','|','justifyLeft','justifyCenter','justifyRight','justifyFull','|','toggleDir','|','createLink','foreColor','hiliteColor','|','selectAll','removeFormat','|','insertUnorderedList','insertOrderedList','|','indent','outdent','|','subscript','superscript','|','fontName','fontSize','formatBlock']";
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<div  dojoType=\"dijit.Editor\" name=\"$NAME\" $attr  >
		$value
		</div>";
		return $text;
	}
	
	/**
	 * an simple richtext editor with dojo
	 */
	function smalleditor( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.Editor");
  
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<div  dojoType=\"dijit.Editor\" name=\"$NAME\" $attr  >
		$value
		</div>";
		return $text;
	}
	
	/**
	 * a dropdown menu using dojo
	 * @param: options[ 'Label'=>'value' ]
	 */
	function dropdown( $NAME='', $value='', $options=array(), $attr=array() )
	{

		add_dojo("dijit.form.FilteringSelect");
		foreach( $options as $key=>$item )
			if( is_array($options) )
				$options[$key] = form_prep( $item );
			else
				$options->$key = form_prep( $item );
				
		$attr = $this->attribute( $attr, 'dojoType', "dijit.form.FilteringSelect");
		$attr = $this->_attributes_to_string( $attr );
		
		$text = form_dropdown($NAME, $options, $value, $attr);
		return $text;
	}
	
	/**
	 * a section chooser dropdown field 
	 */
	function section( $NAME='', $value='', $attr=array() )
	{
		$options = array('s1'=>'index');
		if( ! function_exists( 'rec_section' ) )
		{
			function rec_section( $id, $spacer='--' )
			{
				$op = array();
				$sec = new Section();
				$sec->order_by( 'sort', 'asc' );
				$sec->get_by_parent_section( $id );
				foreach( $sec->all as $item )
				{
					$op[ 's'.$item->id ] = $spacer.$item->name;
					$op = array_merge( $op, rec_section( $item->id, $spacer.'&nbsp;' ) );
				}
				return $op;
				
			}
		}
		$total_sections = array_merge( $options, rec_section(1) );
		$total_sections_keys = array_keys( $total_sections );
		$total_sections_values = array_values( $total_sections );
		
		foreach( $total_sections_keys as $index=>$key )
			$total_sections_keys[$index] = substr( $key, 1 );
			
		$total_sections = array_combine( $total_sections_keys, $total_sections_values );
		return $this->dropdown( $NAME, $value, $total_sections, $attr );
	}
	
	/**
	 * a checkbox using dojo
	 */
	function checkbox( $NAME='', $value='', $checked=FALSE, $attr=array() )
	{

		add_dojo("dijit.form.CheckBox");
		$attr = $this->attribute( $attr, 'dojoType', "dijit.form.CheckBox");
		$attr = $this->_attributes_to_string( $attr );
		
		
		return form_checkbox($NAME, $value, $checked, $attr);
	}
	
	/**
	 * a radio button using dojo
	 */
	function radio( $NAME='', $value='', $checked=FALSE, $attr=array() )
	{

		add_dojo("dijit.form.CheckBox");
		$attr = $this->attribute( $attr, 'dojoType', "dijit.form.RadioButton");
		$attr = $this->_attributes_to_string( $attr );
		
		
		return form_radio($NAME, $value, $checked, $attr);
	}
	
	/**
	 * a Tooltip using dojo
	 */
	function tooltip( $NAME='', $value='', $attr=array() )
	{

		add_dojo("dijit.Tooltip");
		$attr = $this->attribute( $attr, 'position', 'below', FALSE );
		$attr = _attributes_to_string( $attr );
		return "<div dojoType=\"dijit.Tooltip\" connectId=\"$NAME\" $attr>$value</div>";
	}
	
	/**
	 * an accordion of dojo toolkit
	 * @param
	 * 		$data: associative array of title=>panelHTML
	 */
	function accordion( $data=array(), $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.AccordionContainer" );
		$style = $this->style( $style, 'width', '100%' , FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE );
		$style = $this->_array_to_style( $style );
		
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<div dojoType=\"dijit.layout.AccordionContainer\" style=\"$style\"  $attr >";
		
		foreach($data as $key=>$value )
			$text .= "<div dojoType=\"dijit.layout.AccordionPane\" title=\"$key\" >$value</div>";
			
		$text .= "</div>";
		return $text;
	}
	
	/**
	 * a Tab container of dojo toolkit
	 * @param
	 * 		$data: associative array of tabTitle->panelHTML
	 */
	function tab( $data=array(), $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.TabContainer" );
		add_dojo( "dijit.layout.ContentPane" );
		$style = $this->style( $style, 'width', '100%', FALSE );
		$style = $this->style( $style, 'height', '300px', FALSE );
		
		$style = $this->_array_to_style( $style );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<div dojoType=\"dijit.layout.TabContainer\" style=\"$style\"  $attr >";
		
		foreach($data as $key=>$value )
			$text .= "<div dojoType=\"dijit.layout.ContentPane\" title=\"$key\" >$value</div>";
			
		$text .= "</div>";
		return $text;
	}
		
	/**
	 * a jquery styled grid
	 * @param $headers: associative array of member->columnTitle
	 * @param $body: array of (arrays or objects) to extract information members spcified in head from them
	 */
	function grid( $headers = array(), $body=array(), $attr=array() )
	{
		
		add_css( 'jquery/theme/ui.all.css' );
		
		$attr = $this->attribute( $attr, 'align', 'center', FALSE );
		$attr = $this->attribute( $attr, 'width', '100%', FALSE );
		$attr = $this->attribute( $attr, 'class', "ui-widget-content");	
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "
<table $attr >
	<thead class=\"ui-widget-header\" >
	<tr>";

		foreach( $headers as $item=>$value )
		{
		$text .= "\n\t<th>$value</th>";
		}
		
		$text .= "
	</tr>
	</thead>
	<tbody>";
		$even = TRUE;
		foreach( $body as $item )
		{
			$even = !$even;
			if( $even )
				$text .= "\n\t\t<tr style=\"background-color:white\">";
			else
				$text .= "\n\t\t<tr>";
			foreach( $headers as $key=>$value )
			{
				$text .= (is_object($item))? "<td>{$item->$key}</td>":"<td>{$item[$key]}</td>";
			}
			$text .= "\n\t\t</tr>";
		}
		
		$text .= "
	</tbody>
</table>";

		return $text;
	}
	
	/**
	 * title panel with dojo
	 * @param $titel: panel title
	 * @param $body: panel contents
	 */
	function titlepane( $title='', $body='', $attr=array() )
	{
		add_dojo( 'dijit.TitlePane' );
		$attr = $this->_attributes_to_string( $attr );
		return <<<EOT
		<div dojoType="dijit.TitlePane" title="$title" $attr >
			$body
		</div>
EOT;
	}
	
	/**
	 * a error box using jquery
	 */
	function error( $text='', $attr=array() )
	{
		add_css( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
				<div class="error" $attr > $text </div>
EOT;
	}
	
	/**
	 * an Info box using jquery
	 */
	function info( $text='', $attr=array() )
	{
		add_css( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
				<div class="info" $attr > $text </div>
EOT;
	}
	
	/**
	 * a horizontal spliter box with dojo
	 */
	function hbox( $content='', $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.SplitContainer" );
		add_dojo( "dijit.layout.ContentPane" );
		
		$style = $this->style( $style, 'width', '100%', FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE);
		$style = $this->_array_to_style( $style );
		
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<div dojoType=\"dijit.layout.SplitContainer\" orientation=\"horizontal\" $attr style=\"$style\" >";
		foreach( $content as $item )
		{
  			$text .= "<div dojoType=\"dijit.layout.ContentPane\" >$item</div>";
		}
		$text .= "</div>";

	return $text;
	}
	
	/**
	 * a vertical splitter box with dojo
	 */
	function vbox( $content='', $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.SplitContainer" );
		add_dojo( "dijit.layout.ContentPane" );
		
		$style = $this->style( $style, 'width', '100%', FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE);
		$style = $this->_array_to_style( $style );
		
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<div dojoType=\"dijit.layout.SplitContainer\" orientation=\"vertical\" $attr style=\"$style\" >";
		foreach( $content as $item )
		{
  			$text .= "<div dojoType=\"dijit.layout.ContentPane\" >$item</div>";
		}
		$text .= "</div>";

	return $text;
	}
	
	/**
	 * used to manipulate attributes paramter in other functions
	 * return the result attributes object or array
	 * @param $attr: object or array of attributes wanted to be manipulated
	 * @param $key: attribute to set
	 * @param $value: value of the attribute to set
	 * @param $replace: will replace current attribute value if exists or add it if not otherwise will it will add attribute of not exists only
	 */
	function attribute( $attr=array(), $key='', $value='', $replace=TRUE ){
		if( is_array($attr) )
		{
			if( !isset($attr[$key]) or (isset($attr[$key]) and $replace) ) $attr[$key] = $value;
		}
		else if( is_object($attr) )
		{
			if( !isset($attr->$key) or (isset($attr->$key) and $replace) ) $attr->$key =  $value;
		}
		else if( is_string($attr) ){
			$attr .= " $key=\"$value\"";
		}
		
		return $attr;
	}
	
	/**
	 * make a selectable sortable two linked lists one have the 
	 * list array an the other have the values array
	 * the values will be extracted before rendreing from the
	 * list array
	 * you can use it to allow the user to select some items 
	 * from a group and sort them
	 * in a specific order
	 * all that linked with textarea input that get the values list in 
	 * every change
	 * the values are line break separated you can handle them with 
	 * explode then
	 * @param $NAME: the textarea name
	 * @param $list: the original list of items
	 * @param $value: the selected items
	 * */
	function select_sort($NAME, $list=array(), $value=array(), $attr=array() )
{

	add(array(
		'jquery/jquery.js',
		'jquery/jquery-ui.js',
		'jquery/theme/ui.all.css'
	));
	
	$attr = $this->_attributes_to_string( $attr );
	
	if( is_string( $list ) )
	{
		$list = array_map( 'trim', explode( "\n", trim($list) ));
	}
	else if( is_array($list) )
	{
		array_map( 'trim', $list );
	}
	else
	{
		$list = array();
	}
	
	if( is_string( $value ) )
	{
		$value = trim($value);
		if( ! empty($value) )
			$value = array_map( 'trim', explode( "\n", $value));
		else
			$value = array();
	}
	else if( is_array($value) )
	{
		$value = array_map( 'trim', $value );
	}
	else
	{
		$value = array();
	}
		
	$values = array();
	foreach( $list as $item )
	{
		if( !in_array( $item, $value ) )
			array_push( $values, $item );
	}
	$list = $values;

add( <<<EOT
<style type="text/css">
	.filter .list { 
		float: left;
		background-color: #E9E9E9;
		width:40%;
	}
</style>
<script type="text/javascript">
	$(function() {
		$(".filterBox")
			.hide('fast')
			.siblings(".list")
				.sortable({connectWith: 'div',})
				.bind('sortupdate', function(event, ui) {
					x = Array();
					$('.list:last').find('div').each(function(){
					    x.push( $(this).text().trim() );
					});
					x = x.join( "\\n" );
					$(this).siblings('.filterBox').val(x);
				});
	});
</script>
EOT
);
$output = '
<div class="filter">

<textarea class="filterBox" name="'.$NAME.'" '.$attr.' >'.
implode( "\n", $value ).
'</textarea>
<div  class="list dijitTitlePaneContentInner">
Available items';

foreach( $list as $item)
{
	$output .= '<div class="dijitTitlePaneTitle">'.$item.'</div>';
}

$output .= '</div>
<div class="list dijitTitlePaneContentInner">
Selected items';

foreach( $value as $item)
{
	$output .= '<div class="dijitTitlePaneTitle">'.$item.'</div>';
}

$output .= '
	</div>
</div>';
return $output;
}

	/**
	 * a file list chooser that return a text with some lines every line is 
	 * a choosen file ( it's a textarea interface with jquery)
	 **/
function file_list($NAME='',$value='', $attr=array(), $param=array(), $style=array())
{
add('jquery/jquery.js');
$ci =& get_instance();
$ci->load->library('gui');
add(<<<EOT
<script language="javascript" >
function updateList(list)
{
	$(list).each(function(){
		i = $(this).children('textarea').val();
		i.trim();
		if( i!="" )
		{
			i = i.split("\\n");
			i = '<div class="item dijitTitlePaneTitle" >'+i.join('</div><div class="item dijitTitlePaneTitle" >')+'</div>';
		}
		
		$(this).children('.items').html(i);
		
		$(this).children('.items').children('.item').click(
				function(){
					$(this).parent().parent().children('input').val($(this).text());
				});
	});
}

$(function(){
	$('.filelist>textarea').hide();
	
	$('.filelist').each(function(){
			updateList(this);
	
			$(this).children('.add').click(function(){
				input = $(this).siblings('input');
				textarea = $(this).siblings('textarea');
				if( textarea.val()!='')
					i = "\\n"+input.val();
				else
					i = input.val();
				textarea.val(textarea.val()+i);
				updateList($(this).parent());
				input.val('');
			});
			$(this).children('.del').click(function(){
				ta = $(this).siblings('textarea');
				inp = $(this).siblings('input').val();
				list = ta.val();
				list = list.split("\\n");
				newList = new Array();
				for( i=0; i<list.length; i++)
				{
					if( inp!=list[i] )
						newList.push(list[i]);
				}
				if( newList.length>0)
					ta.val(newList.join("\\n"));
				else
					ta.val('');
				updateList($(this).parent());
				$(this).siblings('input').val('');
			});
	});
	
});
</script>
<style>
.filelist .items .item{
	border-bottom: 1px solid;
	padding: 3px;
}
.filelist img{
	padding-top: 5px;
}
</style>
EOT
);

$input = $this->file('d'.rand(1,10000), '', $attr, $param, $style);
$textarea = $this->textarea($NAME, $value);
$base_url = base_url();
return <<<EOT
<div class="filelist" >
<div class="items" ></div>
$textarea
$input
<img class="add" src="{$base_url}assets/admin/edit/add.png" >
<img class="del" src="{$base_url}assets/admin/edit/delete.png" >
</div>
EOT;
}
	/**
	 * just like attribute function but for style paramter
	 */
	function style( $attr=array(), $key='', $value='', $replace=TRUE ){
		if( is_array($attr) )
		{
			if( !isset($attr[$key]) or (isset($attr[$key]) and $replace) )
				$attr[$key] = $value;
		}
		else if( is_object($attr) )
		{
			if( !isset($attr->$key) or (isset($attr->$key) and $replace) )
				$attr->$key =  $value;
		}
		else if( is_string($attr) )
		{
			$attr .= " $key:$value;";
		}
		
		return $attr;
	}
	
	/**
	 * helper function to convert the attribute array to HTML attributes
	 */
	function _attributes_to_string( $attr= array() )
	{
		if( is_string($attr) ) return $attr;
		
		$att = '';
		foreach ($attr as $key => $val)
			$att .= $key . '="' . str_replace('"','\"',$val) . '" ';

		return $att;
	}
	
	/**
	 * helper function to convert the paramters array to javascript object
	 */
	function _params_to_js( $param=array() )
	{
		$att = '';

		foreach ($param as $key => $val)
		{
			if( $val[0]=='[' and $val[strlen($val)-1]==']' )
				$att .= ', ' . $key . ': ' . addslashes(str_replace('"','\"',$val)) .' ';
			else
				$att .= ', ' . $key . ':\'' . addslashes(str_replace('"','\"',$val)) . '\' ';
		}

		return $att;
	}
	
	/**
	 * helper function to convert style array to CSS text
	 */
	function _array_to_style( $style=array() )
	{
		if( is_string( $style ) ) return $style;
		$arr = array();
		foreach( $style as $key=>$value )
		{
			array_push( $arr, $key.':'.$value.';');
		}
		return implode( '', $arr );
	}
}
