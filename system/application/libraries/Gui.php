<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gui {
	
	function Gui()
	{
		$CI =& get_instance();
		$CI->load->helper('form');
	}
	
	
	/*******************************************
	 * a form maker 
	 *******************************************/
	function form($action = '', $data=array(), $attributes = '', $hidden = array())
	{
		$text = form_open( $action, $attributes, $hidden);
		$text .= "\n\t<table  class=\"ui-helper-reset\">";
		foreach( $data as $key=>$value )
		{
			$text .= "\n\t<tr>";
			$text .= "\n\t<td>".form_label( $key )."</td>";
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
		add_js( "jquery/jquery.js");
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
		return $text;
	}
	
	/*******************************************
	 * a color chooser field linked with a dojo color picker dialog
	 *******************************************/
	function color( $ID='',$value='', $attr=array() )
	{
		add_dojo( "dojox.widget.ColorPicker" );
		add_dojo( "dojo.parser" );
		add_dojo( "dijit.Dialog" );
		add_dojo( "dijit.form.TextBox" );

		add_css( "dojo/dojox/widget/ColorPicker/ColorPicker.css" );
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.DateTextBox");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.TimeTextBox");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.TextBox");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.Button");
  		add_dojo("dojo.parser");
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = "<button dojoType=\"dijit.form.Button\" id=\"$ID\" name=\"$ID\" $attr >$value</button>";
		return $text;
	}
	
	/*******************************************
	 * an password field with dojo
	 *******************************************/
	function password( $ID='', $value='', $attr=array() )
	{
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.TextBox");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.NumberSpinner");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.Textarea");
  		add_dojo("dojo.parser");
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<textarea  dojoType=\"dijit.form.Textarea\" id=\"$ID\" name=\"$ID\" $attr >
		$value
		</textarea>";
		return $text;
	}
	
	/*******************************************
	 * an rich text editor with dojo
	 *******************************************/
	function editor( $ID='', $value='', $attr=array() )
	{
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.Editor");
  		add_dojo("dojo.parser");
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		
		$text = 
		"<textarea  dojoType=\"dijit.Editor\" id=\"$ID\" name=\"$ID\" $attr >
		$value
		</textarea>";
		return $text;
	}
	
	/*******************************************
	 * a dropdown menu using dojo
	 *******************************************/
	function dropdown( $ID='', $value='', $options=array(), $attr=array() )
	{
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.FilteringSelect");
  		add_dojo("dojo.parser");
		
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
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.CheckBox");
  		add_dojo("dojo.parser");
		
		$attr['dojoType'] = "dijit.form.CheckBox";
		$attr = $this->_attributes_to_string( $attr );
		
		
		return "\n\t".form_checkbox($ID, $value, $checked, $attr);
	}
	
	/*******************************************
	 * a Tooltip using dojo
	 *******************************************/
	function tooltip( $ID='', $value='' )
	{
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.Tooltip");
  		add_dojo("dojo.parser");
		
		return "\n\t<div dojoType=\"dijit.Tooltip\" connectId=\"$ID\">$value</div>";
	}
	
	
	/*******************************************
	 * helper functions to convert paramters to JS object paramters 
	 * and convert the attribute array to HTML attributes
	 *******************************************/
	function _attributes_to_string( $attr= array() )
	{
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
}
