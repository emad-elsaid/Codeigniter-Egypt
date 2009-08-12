<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * gui class
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
	
	
	/*******************************************
	 * a form maker 
	 *******************************************/
	function form($action = '', $data=array(), $attributes = array(), $hidden = array())
	{
		add_dojo( "dijit.form.Form" );
		
		$attributes['dojoType'] = 'dijit.form.Form';
		$attributes = $this->_attributes_to_string( $attributes );
		
		$text = form_open( $action, $attributes, $hidden);
		$text .= "\n\t<table  class=\"ui-helper-reset\">";
		foreach( $data as $key=>$value )
		{
			$text .= "\n\t<tr>";
			$text .= "\n\t<td align=\"right\" >".form_label( $key )."</td>";
			$text .= "\n\t<td>".$value."</td>";
			$text .= "\n\t</tr>";
		}
		$text .= "\n\t</table>";
		$text .= form_close();
		return $text;
	}
	function hidden($ID='', $value='' )
	{
		return form_hidden( $ID, $value );
	}
	/*******************************************
	 * File chooser using the fsbrowser, use it with CAUTION
	 * it makes an dojo textinput linked with a jquery fsbrowser
	 *******************************************/
	function activeTree( $connector='', $ID='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		
		//adding the nessecery javascripts and CSSs
		add_js( "jquery/jquery.js");
		add_js( "jquery/fileTree/jqueryFileTree.js" );
		add_js( "jquery/easing.js" );
		add_css( "jquery/fileTree/jqueryFileTree.css" );
		add_css( "jquery/theme/ui.all.css");
		add_dojo( "dijit.Dialog" );
		
		// adding the styles if not there
		$attr = $this->_attributes_to_string( $attr );
		if( !isset($style['width']) ) $style['width'] ='400px';
		if( !isset($style['height']) ) $style['height'] = '300px';
		if(!isset($style['overflow']) ) $style['overflow'] = 'auto';
		$style = $this->_array_to_style( $style );
		
		//preparing the paramters
		if(!isset($param['root'])) $param['root'] =  PATH;
		else $param['root'] =  PATH.$param['root'];
		if( ! is_dir( $param['root'] ) ) $param['root'] = str_replace( '/','\\',$param['root'] );
		$root = $param['root'];
		$script = $connector;
		$param = $this->_params_to_js($param);
		
		//the output is here
		$root = addslashes( $root );
		$t = $this->textbox( $ID, $value, 
				array(
					'onclick'=>"dijit.byId('{$ID}dlg').show();"
					,'id' => $ID
				)
		);
		return <<<EOT
		$t
		<div id="{$ID}dlg" dojoType="dijit.Dialog" title="Choose an Item" >
			<div id="{$ID}DIV" style="$style" ></div>
		</div>
		<script language="javascript" >
		$(document).ready( function() {
    	$('#{$ID}DIV').fileTree({script:'$script' $param }, 
		function(file) {
			file = file.split( '{$root}')[1];
        	dijit.byId('$ID').setValue( file );
			dijit.byId('{$ID}dlg').hide();
    	});
		});
		</script>
EOT;
	}
	
	function file( $ID='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		return $this->activeTree(site_url('remote/file'), $ID, $value,$attr,$param,$style);
	}
	
	function model( $ID='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		$param['root'] = 'system/application/models/';
		return $this->activeTree(site_url('remote/file'), $ID, $value,$attr,$param,$style);
	}
	
	function folder( $ID='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		return $this->activeTree(site_url('remote/dir'), $ID, $value,$attr,$param,$style);
	}
	
	function app( $ID='',$value='', $attr=array(), $param=array(), $style=array() )
	{
		$param['root'] = 'system/application/views/apps/';
		return $this->activeTree(site_url('remote/dir'), $ID, $value,$attr,$param,$style);
	}
	/*******************************************
	 * a color chooser field linked with a dojo color picker dialog
	 *******************************************/
	function color( $ID='',$value='', $attr=array() )
	{
		add_dojo( "dojox.widget.ColorPicker" );
		add_dojo( "dijit.Dialog" );
		add_dojo( "dijit.form.TextBox" );
		add_js( "jquery/jquery.js" );
		add_css( "dojo/dojox/widget/ColorPicker/ColorPicker.css" );
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = <<< EOT
		<input type="text" id="$ID" name="$ID" dojoType="dijit.form.TextBox" onclick="dijit.byId('{$ID}dlg').show()" value="$value" $attr >
		<span id="{$ID}box" class="ui.helper.reset ui-corner-all" style="width:20px;height:20px;background-color:$value" >&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<div id="{$ID}dlg" dojoType="dijit.Dialog" title="Choose a color" >
		<div  dojoType="dojox.widget.ColorPicker"
		showHsv="false"
  		showRgb="false" 
		hexCode="$value"
		liveUpdate="true"
		 onChange="dijit.byId('{$ID}').setValue(this.value);$('#{$ID}box').css('background-color',this.value);" class="ui.helper.clearfix" >
		</div></div>
EOT;
		 return $text;
	}
	
	/*******************************************
	 * a date field picker using dojo
	 *******************************************/
	function date( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.DateTextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.DateTextBox\" name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * a Time chooser input field
	 *******************************************/
	function time( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.TimeTextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TimeTextBox\" name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * an input field with dojo
	 *******************************************/
	function textbox( $ID='', $value='', $attr=array() )
	{
		add_dojo("dijit.form.TextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TextBox\" name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * an button with dojo
	 *******************************************/
	function button( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Button");
  
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<button dojoType=\"dijit.form.Button\" name=\"$ID\" $attr >$value</button>";
		return $text;
	}
	
	/*******************************************
	 * an tooltipbutton with dojo
	 *******************************************/
	function tooltipbutton( $text='', $title='', $dialog='', $attr=array() )
	{

		add_dojo("dijit.form.Button");
		add_dojo( "dijit.Dialog" );
  
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
<div dojoType="dijit.form.DropDownButton" $attr >
  <span>$text</span>
  <div dojoType="dijit.TooltipDialog" title="$title" >
  $dialog
  </div>
</div>
EOT;
	}
	
	/*******************************************
	 * an password field with dojo
	 *******************************************/
	function password( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.TextBox");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"password\" dojoType=\"dijit.form.TextBox\" name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * an input spinner with dojo
	 *******************************************/
	function number( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.NumberSpinner");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.NumberSpinner\"name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * a textarea field auto grown
	 *******************************************/
	function textarea( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Textarea");
  
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<textarea  dojoType=\"dijit.form.Textarea\" name=\"$ID\" $attr >$value</textarea>";
		return $text;
	}
	
	/*******************************************
	 * an rich text editor with dojo
	 *******************************************/
	function editor( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.Editor");
		add_dojo("dijit._editor.plugins.AlwaysShowToolbar");
		add_dojo("dijit._editor.plugins.EnterKeyHandling");
		add_dojo("dijit._editor.plugins.TextColor");
		add_dojo("dijit._editor.plugins.LinkDialog");
		add_dojo("dijit._editor.plugins.FontChoice");
		add_dojo("dijit._editor.plugins.ToggleDir");
		add_css('jquery/theme/ui.all.css');
  
		$attr['plugins'] = "['undo','redo','|','cut','delete','copy','paste','|','bold','italic','underline','strikethrough','|','justifyLeft','justifyCenter','justifyRight','justifyFull','|','toggleDir','|','createLink','foreColor','hiliteColor','|','selectAll','removeFormat','|','insertUnorderedList','insertOrderedList','|','indent','outdent','|','subscript','superscript','|','fontName','fontSize','formatBlock']";
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<div  dojoType=\"dijit.Editor\" name=\"$ID\" $attr  >
		$value
		</div>";
		return $text;
	}
	
	/*******************************************
	 * a dropdown menu using dojo
	 * @param: options[ 'Label'=>'value' ]
	 *******************************************/
	function dropdown( $ID='', $value='', $options=array(), $attr=array() )
	{

		add_dojo("dijit.form.FilteringSelect");
		foreach( $options as $key=>$item )
			$options[$key] = form_prep( $item );
			
		$attr = $this->_attributes_to_string( $attr );
		
		$text = form_dropdown($ID, $options, $value, 'dojoType="dijit.form.FilteringSelect" '.$attr);
		return $text;
	}
	
	function section( $ID='', $value='', $attr=array() )
	{
		$options = array('1'=>'index');
		function rec_section( $id, $spacer='&nbsp;' )
		{
			$op = array();
			$sec = new Section();
			$sec->get_by_parent_section( $id );
			foreach( $sec->all as $item )
			{
				$op[ $item->id ] = $spacer.$item->name;
				$op = array_merge( $op, rec_section( $item->id, $spacer.'&nbsp;' ) );
			}
			return $op;
			
		}
		return $this->dropdown( $ID, $value, array_merge( $options, rec_section(1)), $attr );
	}
	/*******************************************
	 * a checkbox using dojo
	 *******************************************/
	function checkbox( $ID='', $value='', $checked=FALSE, $attr=array() )
	{

		add_dojo("dijit.form.CheckBox");
		$attr['dojoType'] = "dijit.form.CheckBox";
		$attr = $this->_attributes_to_string( $attr );
		
		
		return "\n\t".form_checkbox($ID, $value, $checked, $attr);
	}
	/*******************************************
	 * a radio button using dojo
	 *******************************************/
	function radio( $ID='', $value='', $checked=FALSE, $attr=array() )
	{

		add_dojo("dijit.form.CheckBox");
		$attr['dojoType'] = "dijit.form.RadioButton";
		$attr = $this->_attributes_to_string( $attr );
		
		
		return "\n\t".form_radio($ID, $value, $checked, $attr);
	}
	
	/*******************************************
	 * a Tooltip using dojo
	 *******************************************/
	function tooltip( $ID='', $value='' )
	{

		add_dojo("dijit.Tooltip");
		return "\n\t<div dojoType=\"dijit.Tooltip\" connectId=\"$ID\">$value</div>";
	}
	
	/*******************************************
	 * an accordion of dojo toolkit
	 *******************************************/
	function accordion( $data=array(), $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.AccordionContainer" );
		if( !isset($style['width']) ) $style['width'] ='100%';
		if( !isset($style['height']) ) $style['height'] = '300px';
		$style = $this->_array_to_style( $style );
		
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<div dojoType=\"dijit.layout.AccordionContainer\" style=\"$style\"  $attr >";
		
		foreach($data as $key=>$value )
			$text .= "<div dojoType=\"dijit.layout.AccordionPane\" title=\"$key\" >$value</div>";
			
		$text .= "</div>";
		return $text;
	}
	
	/*******************************************
	 * a jquery styled grid
	 *******************************************/
	function grid( $headers = array(), $body=array(), $attr=array() )
	{
		
		add_css( 'jquery/theme/ui.all.css' );
		
		if( ! isset($attr['align']) ) $attr['align'] = "center";
		if( ! isset($attr['width']) ) $attr['width'] = "100%";
		if( !isset($attr['class']) )
			$attr['class'] = "ui-widget-content ui-corner-all ui-helper-reset";
		else
			$attr['class'] .= "ui-widget-content ui-corner-all ui-helper-reset";
		
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
	
	/*******************************************
	 * a error box using jquery
	 *******************************************/
	function error( $text='', $attr=array() )
	{
		add_css( 'jquery/theme/ui.all.css' );
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
				<div class="ui-state-error ui-corner-all" style="padding:5px;" $attr > 
					$text
				</div>
EOT;
	}
	
	/*******************************************
	 * an Info box using jquery
	 *******************************************/
	function info( $text='', $attr=array() )
	{
		add_css( 'jquery/theme/ui.all.css' );
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
				<div class="ui-state-highlight ui-corner-all" style="padding:5px;" $attr > 
					$text
				</div>
EOT;
	}
	
	/*******************************************
	 * a horizontal box with dojo
	 *******************************************/
	function hbox( $content='', $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.SplitContainer" );
		add_dojo( "dijit.layout.ContentPane" );
		
		if( !isset($style['width']) ) $style['width'] ='100%';
		if( !isset($style['height']) ) $style['height'] = '300px';
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
	/*******************************************
	 * a vertical box with dojo
	 *******************************************/
	function vbox( $content='', $attr=array(), $style=array() )
	{
		add_dojo( "dijit.layout.SplitContainer" );
		add_dojo( "dijit.layout.ContentPane" );
		
		if( !isset($style['width']) ) $style['width'] ='100%';
		if( !isset($style['height']) ) $style['height'] = '300px';
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
	/*******************************************
	 * helper functions to convert paramters to JS object paramters 
	 * and convert the attribute array to HTML attributes
	 *******************************************/
	function _attributes_to_string( $attr= array() )
	{
		if( is_string($attr) ) return $attr;
		$att = '';

		foreach ($attr as $key => $val)
		{
			$att .= $key . '="' . str_replace('"','\"',$val) . '" ';
		}

		return $att;
	}
	
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
