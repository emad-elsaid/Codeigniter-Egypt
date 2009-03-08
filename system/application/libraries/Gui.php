<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gui {
	
	function Gui()
	{
		$CI =& get_instance();
		$CI->load->helper('form');
	}
	function file( $ID='',$value='', $attr=array(), $param=array())
	{
		add_js( "jquery/jquery.js");
		add_dojo( "dijit.form.TextBox" );
		
		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );
		$param = $this->_params_to_js( $param );
		define( 'BASE_URL', base_url() );
		include( 'jquery/sfbrowser/init.php' );

		$text = "<input type=\"text\" 
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
	
	function checkbox( $ID='', $value='', $attr=array() )
	{
		add_css( "dojo/dijit/themes/tundra/tundra.css" );
		add_dojo("dijit.form.CheckBox");
  		add_dojo("dojo.parser");
	}
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
