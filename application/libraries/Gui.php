<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * codeigniter library for creating UI controls 
 *
 * that library uses dojo to create controlles for applications and 
 * website, and it needs also theme_helper.php for recording 
 * the css, js, dojo, requirements
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 * @package Libraries
 */ 
class Gui {

	function __construct(){
		
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->helper('language');
		$CI->lang->load('system');
	}


	/**
	 * a form maker
	 * 
	 * @param string $action target page of the form
	 * @param array $data array of key( label text )=>value(corresponding input HTML ), values can be generated with functions like textbox,color,file,folder,app.etc
	 * @param array/object $attributes: array or object of key(attribute)=>value(value of the attribute)
	 * @param array $hidden array of hiden fields and values as key=>value
	 * @return string form HTML string wich will be added directly to page
	 */
	public function form($action = '', $data=array(), $attributes = array(), $hidden = array()){

		theme_add( 'dijit.form.Form' );
		$this->attribute( $attributes, 'dojoType', 'dijit.form.Form') ;
		$attributes = $this->_attributes_to_string( $attributes );

		$text =	form_open( $action, $attributes, $hidden).
					'<table>';
		foreach( $data as $key=>$value ){
			$text .=	'<tr>
						<th width="1%" >'.form_label( $key ).'</th>
						<td>'.$value.'</td>
						</tr>';
		}
		$text .= '</table>'.form_close();

		return $text;
	}

	/**
	 * generate hidden field HTML
	 * 
	 * @param $NAME hidden field name
	 * @param $value hidden field value
	 * @return string hidden input tag string
	 */
	public function hidden($NAME='', $value='' ){
		
		return form_hidden( $NAME, $value );
		
	}

	/**
	 * File chooser using dojo tree, use it with
	 * it makes a dojo text input linked with a dojo dialog contains dojo tree
	 * it's just like the upload input tag, but serverside
	 * be aware that tree will load all the tree as JSON string
	 * from the connector URL
	 * 
	 * @param string $connector URL to php file to use as ajax backend, with object like this
	 * <pre>
	 * {"identifier":"id","label":"description","items":[
	 * 	{"id":2,"description":"leaf1"},{"id":3,"description":"category","c":[
	 * 		{"id":4,"description":"subleaf1"},
	 * 		{"id":5,"description":"subleaf2"}
	 * 	]}
	 * ]}
	 * </pre>
	 * @param string $NAME text input name and id
	 * @param strin $value text input value
	 * @param array/object/string $attr attributes->value associative array
	 * @param array/object/string $style property->value associative array
	 * @return string HTML string for the chooser
	 */
	public function file_chooser( $connector='', $NAME='',$value='', $attr=array(), $style=array() ){

		theme_add('dojo.data.ItemFileReadStore');
		theme_add('dijit.tree.ForestStoreModel');
		theme_add('dijit.Tree');
		theme_add('dijit.Dialog');
		
		$this->attribute( $attr, 'onclick', "dijit.byId('filedlg_$NAME').show();");
		$this->attribute( $attr, 'id', $NAME );
		
		$txtBox = $this->textbox( $NAME, $value, $attr, $style );
		
		return <<<EOT
<div dojoType="dojo.data.ItemFileReadStore" url="$connector" jsId="ordJson_$NAME"></div>
<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="c" store="ordJson_$NAME" jsId="ordModel_$NAME"></div>
<div id="filedlg_$NAME" dojoType="dijit.Dialog" title="Choose...">
	<div style="width:400px;height:300px;overflow:auto;" ><div dojoType="dijit.Tree" id="ordTree_$NAME" model="ordModel_$NAME" showRoot="false" >
	<script type="dojo/method" event="onClick" args="item">
	if(item.c==undefined){
	dijit.byId('$NAME').setValue(item.i[0]);
	dijit.byId('filedlg_$NAME').hide();
	}	
	</script>
	</div></div>
</div>
$txtBox
EOT;

	}
	
	/**
	 * Folder chooser using dojo tree, use it with
	 * it makes a dojo text input linked with a dojo dialog contains dojo tree
	 * it's just like the upload input tag, but serverside
	 * be aware that tree will load all the tree as JSON string
	 * from the connector URL
	 * 
	 * @param string $connector URL to php file to use as ajax backend, with object like this
	 * <pre>
	 * {"identifier":"id","label":"description","items":[
	 * 	{"id":2,"description":"leaf1"},{"id":3,"description":"category","c":[
	 * 		{"id":4,"description":"subleaf1"},
	 * 		{"id":5,"description":"subleaf2"}
	 * 	]}
	 * ]}
	 * </pre>
	 * @param string $NAME text input name and id
	 * @param strin $value text input value
	 * @param array/object/string $attr attributes->value associative array
	 * @param array/object/string $style property->value associative array
	 * @return string HTML string for the chooser
	 */
	public function folder_chooser( $connector='', $NAME='',$value='', $attr=array(), $style=array() ){

		theme_add('dojo.data.ItemFileReadStore');
		theme_add('dijit.tree.ForestStoreModel');
		theme_add('dijit.Tree');
		theme_add('dijit.Dialog');
		
		$this->attribute( $attr, 'onclick', "dijit.byId('filedlg_$NAME').show();");
		$this->attribute( $attr, 'id', $NAME );
		
		$txtBox = $this->textbox( $NAME, $value, $attr, $style );
		
		return <<<EOT
<div dojoType="dojo.data.ItemFileReadStore" url="$connector" jsId="ordJson_$NAME"></div>
<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="c" store="ordJson_$NAME" jsId="ordModel_$NAME"></div>
<div id="filedlg_$NAME" dojoType="dijit.Dialog" title="Choose...">
	<div style="width:400px;height:300px;overflow:auto;" ><div dojoType="dijit.Tree" id="ordTree_$NAME" model="ordModel_$NAME" showRoot="false" >
	<script type="dojo/method" event="onClick" args="item">
	if(item.c!=undefined){
	dijit.byId('$NAME').setValue(item.i[0]);
	dijit.byId('filedlg_$NAME').hide();
	}	
	</script>
	</div></div>
</div>
$txtBox
EOT;

	}

	/**
	 * create a date chooser field using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function date( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.DateTextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.DateTextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * create a time chooser field using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function time( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.TimeTextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.TimeTextBox" name="'.$NAME.'" value="'.$value.'" $attr >';
		return $text;
		
	}

	/**
	 * create a text field using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function textbox( $NAME='', $value='', $attr=array() ){
		
		theme_add('dijit.form.TextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.TextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * create a dojo button
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function button( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Button');

		$attr = $this->_attributes_to_string( $attr );

		$text = '<button dojoType="dijit.form.Button" name="'.$NAME.'" '.$attr.' >'.$value.'</button>';
		return $text;
		
	}

	/**
	 * create tooltip button with dojo, it is a button
	 * that has an associated tooltip dialog you can add any 
	 * HTML text in it
	 * 
	 * @param string $text button value text
	 * @param string $dialog tooltip dialog content text
	 * @param array/object/string attributes to be added to the button
	 * @return string button HTML string
	 */
	public function tooltipbutton( $text='', $dialog='', $attr=array() ){

		theme_add('dijit.form.Button');
		theme_add( 'dijit.Dialog' );

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
	 * create a password field using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function password( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.TextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="password" dojoType="dijit.form.TextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * create a number field using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function number( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.NumberSpinner');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.NumberSpinner"name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * create an auto expandable textarea using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function textarea( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Textarea');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<textarea  dojoType="dijit.form.Textarea" name="'.$NAME.'" '.$attr.' >'.$value.'</textarea>';
		return $text;
		
	}

	/**
	 * create a permission textarea using dojo
	 * and add a tooltip with the variables/booleans/operators 
	 * that could be used in the expression
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function permission( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Textarea');

		$ci =& get_instance();
		$ci->load->helper('perm');
		$p_arr = perm_array();
		$t_text = 	'<b>'.lang('system_boolean_vars').' : </b>'.implode('|', array_keys($p_arr['boolVars']) )
		.'<br><b>'.lang('system_vars').' : </b>'.implode('|', array_keys($p_arr['vars']) );

		$value = form_prep( $value );
		$id_g = 'p'.microtime();

		$this->attribute( $attr, 'id', $id_g , FALSE);
		$id_g = $attr['id'];

		$text = $this->textarea( $NAME, $value, $attr)
		.$this->tooltip($id_g, $t_text );
		return $text;
		
	}

	/**
	 * create a WYSIWYG editor using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function editor( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.Editor');
		theme_add('dijit._editor.plugins.AlwaysShowToolbar');
		theme_add('dijit._editor.plugins.EnterKeyHandling');
		theme_add('dijit._editor.plugins.TextColor');
		theme_add('dijit._editor.plugins.LinkDialog');
		theme_add('dijit._editor.plugins.FontChoice');
		theme_add('dijit._editor.plugins.ToggleDir');

		$attr['plugins'] = "['undo','redo','|','cut','delete','copy','paste','|','bold','italic','underline','strikethrough','|','justifyLeft','justifyCenter','justifyRight','justifyFull','|','toggleDir','|','createLink','foreColor','hiliteColor','|','selectAll','removeFormat','|','insertUnorderedList','insertOrderedList','|','indent','outdent','|','subscript','superscript','|','fontName','fontSize','formatBlock']";
		$attr = $this->_attributes_to_string( $attr );

		return '<div  dojoType="dijit.Editor" name="'.$NAME.'" '.$attr.'  >'.$value.'</div>';
	}

	/**
	 * create a text field using dojo but a small one
	 * with the nessesary basic editing controls
	 * 
	 * @param string $NAME field name
	 * @param string $value field current value
	 * @param array/object/string $attr attribute to be added to the field
	 * @return string HTML tag of the field
	 */
	public function smalleditor( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.Editor');

		$attr = $this->_attributes_to_string( $attr );

		$text =
		'<div  dojoType="dijit.Editor" name="'.$NAME.'" '.$attr.'  >
		'.$value.'
		</div>';
		return $text;
		
	}

	/**
	 * create a select dropdown menu control using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field default value
	 * @param array $options assoc array of options in the form [value=>label]
	 * @param array/object/string $attr
	 * @return string the field HTML string
	 */
	public function dropdown( $NAME='', $value='', $options=array(), $attr=array() ){

		theme_add('dijit.form.FilteringSelect');
		foreach( $options as $key=>$item )
		if( is_array($options) )
		$options[$key] = form_prep( $item );
		else
		$options->$key = form_prep( $item );

		$this->attribute( $attr, 'dojoType', 'dijit.form.FilteringSelect');
		$attr = $this->_attributes_to_string( $attr );

		$text = form_dropdown($NAME, $options, $value, $attr);
		return $text;
		
	}

	/**
	 * create a select dropdown menu control using dojo
	 * with the options set automatically to the system sections
	 * 
	 * @param string $NAME field name
	 * @param string $value field default value
	 * @param array/object/string $attr
	 * @return string the field HTML string
	 */
	public function section( $NAME='', $value='', $attr=array() ){
		
		$options = array('s1'=>'index');
		if( ! function_exists( 'rec_section' ) ){
			function rec_section( $id, $spacer='--' ){
				$op = array();
				$sec = new Section();
				$sec->order_by( 'sort', 'asc' );
				$sec->get_by_parent_section( $id );
				foreach( $sec as $item ){
					$op[ 's'.$item->id ] = $spacer.$item->name;
					$op = array_merge( $op, rec_section( $item->id, $spacer.$spacer ) );
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
	 * create a checkbox element using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field value
	 * @param boolean $checked field current check state
	 * @param array/object/string $attr attributes to be added to the field
	 * @return string the HTML string of the element
	 */
	public function checkbox( $NAME='', $value='', $checked=FALSE, $attr=array() ){

		theme_add('dijit.form.CheckBox');
		$this->attribute( $attr, 'dojoType', 'dijit.form.CheckBox');
		$attr = $this->_attributes_to_string( $attr );

		return form_checkbox($NAME, $value, $checked, $attr);
		
	}

	/**
	 * create a radio button element using dojo
	 * 
	 * @param string $NAME field name
	 * @param string $value field value
	 * @param boolean $checked field current check state
	 * @param array/object/string $attr attributes to be added to the field
	 * @return string the HTML string of the element
	 */
	public function radio( $NAME='', $value='', $checked=FALSE, $attr=array() ){

		theme_add('dijit.form.CheckBox');
		$this->attribute( $attr, 'dojoType', 'dijit.form.RadioButton');
		$attr = $this->_attributes_to_string( $attr );

		return form_radio($NAME, $value, $checked, $attr);
		
	}

	/**
	 * create a dojo tooltip an associate it to
	 * another HTML element
	 * 
	 * @param string $NAME HTML element ID to associate the tooltip to
	 * @param string $value HTML content of teh tooltip
	 * @param array/object/string $attr attributes to be added to the element
	 * @return string the HTML string of the tooltip
	 */
	public function tooltip( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.Tooltip');
		$this->attribute( $attr, 'position', 'below', FALSE );
		$attr = _attributes_to_string( $attr );
		return '<div dojoType="dijit.Tooltip" connectId="'.$NAME.'" '.$attr.'>'.$value.'</div>';
		
	}

	/**
	 * create a dojo accordion contains the elements 
	 * of $data as tabs where $data keys are  the accordion
	 * titles, and $data values are the accordion contents
	 * 
	 * @param array $data assoc array of accordion titles as
	 * key and accordion content as values
	 * @param array/object/string $attr accordion attributes
	 * @param array/object/string $style accordion style
	 * @return string HTML tag of the accordion
	 */
	public function accordion( $data=array(), $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.AccordionContainer' );
		$this->style( $style, 'width', '100%' , FALSE);
		$this->style( $style, 'height', '300px', FALSE );
		$style = $this->_array_to_style( $style );

		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.AccordionContainer" style="'.$style.'" '.$attr.' >';

		foreach($data as $key=>$value )
		$text .= '<div dojoType="dijit.layout.AccordionPane" title="'.$key.'" >'.$value.'</div>';
			
		$text .= "</div>";
		return $text;
		
	}

	/**
	 * create a dojo tab contains the elements 
	 * of $data as tabs where $data keys are  the tab
	 * titles, and $data values are the tab contents
	 * 
	 * @param array $data assoc array of tab titles as
	 * key and accordion content as values
	 * @param array/object/string $attr tab attributes
	 * @param array/object/string $style tab style
	 * @return string HTML tag of the tab
	 */
	public function tab( $data=array(), $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.TabContainer' );
		theme_add( 'dijit.layout.ContentPane' );
		$this->style( $style, 'width', '100%', FALSE );
		$this->style( $style, 'height', '300px', FALSE );

		$style = $this->_array_to_style( $style );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.TabContainer" style="'.$style.'" '.$attr.' >';

		foreach($data as $key=>$value )
		$text .= '<div dojoType="dijit.layout.ContentPane" title="'.$key.'" >'.$value.'</div>';
			
		$text .= '</div>';
		return $text;
		
	}
	
	/**
	 * create a dojo title panel 
	 * 
	 * @param string $title title panel title
	 * @param string $body title panel HTML content
	 * @param array/object/string $attr title panel attributes
	 * @return string the HTML string of teh element
	 */
	public function titlepane( $title='', $body='', $attr=array() ){
		
		theme_add( 'dijit.TitlePane' );
		$attr = $this->_attributes_to_string( $attr );
		return '<div dojoType="dijit.TitlePane" title="'.$title.'" '.$attr.' >'.$body.'</div>';
		
	}

	/**
	 * create an error div , it's a normal 
	 * DIV with class=error
	 * 
	 * @param string $text error message, it could be HTML if you want
	 * @param object/array/string $attr div extra attributes
	 */
	public function error( $text='', $attr=array() ){
		
		theme_add( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );

		return '<div class="error" '.$attr.' >'.$text.'</div>';
		
	}

	/**
	 * create an information div , it's a normal 
	 * DIV with class=info
	 * 
	 * @param string $text information message, it could be HTML if you want
	 * @param object/array/string $attr div extra attributes
	 * @return string the HTML tag of the div
	 */
	public function info( $text='', $attr=array() ){
		
		theme_add( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );

		return '<div class="info" '.$attr.' >'.$text.'</div>';
		
	}

	/**
	 * horizontal splitted box using dojo
	 * 
	 * @param array $content content of every hbox cell
	 * @param array/object/string $attr tag attributes
	 * @param array/object/string $style style of tag
	 * @return string HTML of the Hbox tag
	 */
	public function hbox( $content=array(), $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.SplitContainer' );
		theme_add( 'dijit.layout.ContentPane' );

		$this->style( $style, 'width', '100%', FALSE);
		$this->style( $style, 'height', '300px', FALSE);
		$style = $this->_array_to_style( $style );

		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.SplitContainer" orientation="horizontal" '.$attr.' style="'.$style.'" >';
		
		foreach( $content as $item )
			$text .= '<div dojoType="dijit.layout.ContentPane" >'.$item.'</div>';
		
		$text .= '</div>';

		return $text;
		
	}

	/**
	 * vertical splitted box using dojo
	 * 
	 * @param array $content content of every vbox cell
	 * @param array/object/string $attr tag attributes
	 * @param array/object/string $style style of tag
	 * @return string HTML of the vbox tag
	 */
	public function vbox( $content='', $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.SplitContainer' );
		theme_add( 'dijit.layout.ContentPane' );

		$this->style( $style, 'width', '100%', FALSE);
		$this->style( $style, 'height', '300px', FALSE);
		$style = $this->_array_to_style( $style );

		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.SplitContainer" orientation="vertical" '.$attr.' style="'.$style.'" >';
		
		foreach( $content as $item )
			$text .= '<div dojoType="dijit.layout.ContentPane" >'.$item.'</div>';
		
		$text .= '</div>';

		return $text;
		
	}

	/**
	 * used to manipulate attributes paramter in other functions
	 * @param $attr object or array of attributes wanted to be manipulated
	 * @param $key attribute to set
	 * @param $value value of the attribute to set
	 * @param $replace will replace current attribute value if exists or add it if not otherwise will it will add attribute of not exists only
	 * @return void
	 */
	public function attribute( &$attr=array(), $key='', $value='', $replace=TRUE ){
		
		if( is_array($attr) ){
			if( !isset($attr[$key]) or (isset($attr[$key]) and $replace) ) $attr[$key] = $value;
		}else if( is_object($attr) ){
			if( !isset($attr->$key) or (isset($attr->$key) and $replace) ) $attr->$key =  $value;
		}else if( is_string($attr) ){
			$attr .= " $key=\"$value\"";
		}
				
	}
	
	/**
	 * a file list chooser that return a text with some lines every line is
	 * a choosen file ( it's a textarea interface with jquery)
	 * 
	 * @param string $connector URL to php file to use as ajax backend, with object like this
	 * <pre>
	 * {"identifier":"id","label":"description","items":[
	 * 	{"id":2,"description":"leaf1"},{"id":3,"description":"category","c":[
	 * 		{"id":4,"description":"subleaf1"},
	 * 		{"id":5,"description":"subleaf2"}
	 * 	]}
	 * ]}
	 * </pre>
	 * @param string $NAME text input name and id
	 * @param strin $value text input value
	 * @param array/object/string $attr attributes->value associative array
	 * @param array/object/string $style property->value associative array
	 * @return string HTML string for the chooser
	 */
	public function file_list( $connector, $NAME='',$value='', $attr=array(), $param=array(), $style=array()){
		
		theme_add('jquery/jquery.js');
		$ci =& get_instance();
		$ci->load->library('gui');
		theme_add(<<<EOT
<script language="javascript" >
function updateList(list){

	$(list).each(function(){
		i = $(this).children('textarea').val();
		if( i!="" ){
			i = i.split("\\n");
			i = '<div class="item dijitTitlePaneTitle" >'+i.join('</div><div class="item dijitTitlePaneTitle" >')+'</div>';
		}
		
		$(this).children('.items').html(i);
		
		$(this).children('.items').children('.item').click(
				function(){
					$(this).parent().parent().find('input').val($(this).text());
				});
	});
}

$(function(){
	$('.filelist>textarea').hide();
	
	$('.filelist').each(function(){
			updateList(this);
	
			$(this).children('.add').click(function(){
				input = $(this).parent().find('input');
				textarea = $(this).siblings('textarea');
				if( textarea.val().length>0)
					i = "\\n"+input.val();
				else
					i = input.val();
				textarea.val(textarea.val()+i);
				updateList($(this).parent());
				input.val('');
			});
			$(this).children('.del').click(function(){
				ta = $(this).siblings('textarea');
				inp = $(this).parent().find('input').val();
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
				 $(this).parent().find('input').val('');
			});
	});
	
});
</script>
<style>
.filelist .items .item{padding: 3px;}
.filelist img{padding-top: 5px;}
</style>
EOT
		);

		$input = $this->file_chooser( $connector, 'd'.rand(1,10000), '', $attr, $param, $style);
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
	 * 
	 * @param object/array/string $attr
	 * @param string $key the css attribute you want to add/modify
	 * @param string $value the attribute value
	 * @param boolean $replace if you want to replace the value or not if exists in teh $attr
	 */
	public function style( &$attr=array(), $key='', $value='', $replace=TRUE ){
		
		if( is_array($attr) ){
			if( !isset($attr[$key]) or (isset($attr[$key]) and $replace) )
			$attr[$key] = $value;
		}else if( is_object($attr) ){
			if( !isset($attr->$key) or (isset($attr->$key) and $replace) )
			$attr->$key =  $value;
		}else if( is_string($attr) ){
			if( $replace )
			$attr .= " $key:$value;";
			else
			$attr = " $key:$value;".$attr;
		}		
	}

	/**
	 * function to convert the attribute array to HTML attributes
	 * 
	 * @param array/object $attr the attributes array you want to convert
	 * @return string HTML attibute="value" string sequence 
	 */
	protected function _attributes_to_string( $attr= array() ){
		
		if( is_string($attr) ) return $attr;

		$att = '';
		foreach ($attr as $key => $val)
		$att .= $key . '="' . str_replace('"','\"',$val) . '" ';

		return $att;
		
	}

	/**
	 * function to convert the style array to CSS attributes
	 * 
	 * @param array/object $attr the attributes array you want to convert
	 * @return string HTML key value; string sequence 
	 */
	protected function _array_to_style( $style=array() ){
		
		if( is_string( $style ) ) return $style;
		$arr = array();
		foreach( $style as $key=>$value )
			array_push( $arr, $key.':'.$value.';');
		
		return implode( '', $arr );
		
	}
}
