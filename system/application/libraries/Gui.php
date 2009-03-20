<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	/*******************************************
	 * File chooser using the fsbrowser, use it with CAUTION
	 * it makes an dojo textinput linked with a jquery fsbrowser
	 *******************************************/
	function file( $ID='',$value='', $attr=array(), $param=array())
	{
		
		return $this->textbox( $ID, $value, $attr );
		/*add_js( "jquery/jquery.js");
		add_dojo( "dijit.form.TextBox" );
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		$param = $this->_params_to_js( $param );
		define( 'BASE_URL', base_url() );
		include( 'jquery/sfbrowser/init.php' );

		$text = "\n<input type=\"text\" 
		dojoType=\"dijit.form.TextBox\"
		id=\"$ID\" 
		name=\"$ID\" 
		onclick=\"
			$.sfb({select:function(ofile){
				ofile[0].file = ofile[0].file.split( '//')[1];
				ofile[0].file = ofile[0].file.replace( $.sfbrowser.defaults.base, '' );
				document.getElementById('$ID').value=ofile[0].file;
			}
			$param
			}
			);\"
		value=\"$value\" $attr >";
		return $text;*/
	}
	
	/*******************************************
	 * a color chooser field linked with a dojo color picker dialog
	 *******************************************/
	function color( $ID='',$value='', $attr=array() )
	{
		add_dojo( "dojox.widget.ColorPicker" );

		add_dojo( "dijit.Dialog" );
		add_dojo( "dijit.form.TextBox" );

		add_css( "dojo/dojox/widget/ColorPicker/ColorPicker.css" );
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "
		<input type=\"text\" id=\"$ID\" name=\"$ID\" dojoType=\"dijit.form.TextBox\" onclick=\"dijit.byId('{$ID}dlg').show()\" value=\"$value\" $attr >
		<div id=\"{$ID}dlg\" dojoType=\"dijit.Dialog\" >
		<div  dojoType=\"dojox.widget.ColorPicker\"
		showHsv=\"false\"
  		showRgb=\"false\"  
		 onclick=\"document.getElementById('{$ID}').value=this.value\">
		</div></div>";
		  
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
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.DateTextBox\" id=\"$ID\" name=\"$ID\" value=\"$value\" $attr >";
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
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TimeTextBox\" id=\"$ID\" name=\"$ID\" value=\"$value\" $attr >";
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
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.TextBox\" id=\"$ID\" name=\"$ID\" value=\"$value\" $attr >";
		return $text;
	}
	
	/*******************************************
	 * an button with dojo
	 *******************************************/
	function button( $ID='', $value='', $attr=array() )
	{

		add_dojo("dijit.form.Button");
  
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<button dojoType=\"dijit.form.Button\" id=\"$ID\" name=\"$ID\" $attr >$value</button>";
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
		
		$text = "<input type=\"password\" dojoType=\"dijit.form.TextBox\" id=\"$ID\" name=\"$ID\" value=\"$value\" $attr >";
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
		
		$text = "<input type=\"text\" dojoType=\"dijit.form.NumberSpinner\" id=\"$ID\" name=\"$ID\" value=\"$value\" $attr >";
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
		"<textarea  dojoType=\"dijit.form.Textarea\" id=\"$ID\" name=\"$ID\" $attr >$value</textarea>";
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
		"<div  dojoType=\"dijit.Editor\" id=\"$ID\" name=\"$ID\" $attr  >
		$value
		</div>";
		return $text;
	}
	
	/*******************************************
	 * a dropdown menu using dojo
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
		if( ! isset($attr['width']) ) $attr['width'] = "98%";
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
	<tbody class=\"\">";

		foreach( $body as $item )
		{
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
	
	/*******************************************
	 * a error box using jquery
	 *******************************************/
	function error( $text='', $attr=array() )
	{
		add_css( 'jquery/theme/ui.all.css' );
		$attr = $this->_attributes_to_string( $attr );
		
		return <<<EOT
		<div class="ui-widget" $attr >
				<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
					<strong>Error : </strong>$text</p>
				</div>
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
		<div class="ui-widget" $attr >
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					<strong>Info : </strong>$text</p>
				</div>
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
			if( $val[0]=='[' and $val[strlen($val)-1] )
				$att .= ', ' . $key . ': ' . str_replace('"','\"',$val) .' ';
			else
				$att .= ', ' . $key . ':\'' . str_replace('"','\"',$val) . '\' ';
		}

		return $att;
	}
	
	function _array_to_style( $style=array() )
	{
		$arr = array();
		foreach( $style as $key=>$value )
		{
			array_push( $arr, $key.':'.$value.';');
		}
		return implode( '', $arr );
	}
}
