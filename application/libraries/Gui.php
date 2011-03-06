<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*! \addtogroup Libraries
 * Gui class:  used to generate multiple HTML text
 * used with content, applications, it's very useful with
 * generating forms, tables, titlepanels, splitters, error messages,
 * information messages, colorpickers and a lot more
 *
 * @package	Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/Codeigniter-Egypt
 */
class Gui {

	function __construct(){
		
		$CI =& get_instance();
		$CI->load->helper('form');
		
	}


	/**
	 * a form maker
	 * @param $action: target page of the form
	 * @param $data: array of key( label text )=>value(corresponding input HTML ), values can be generated with functions like textbox,color,file,folder,app.etc
	 * @param $attributes: array or object of key(attribute)=>value(value of the attribute)
	 * @param $hidden: array of hiden fields and values as key=>value
	 */
	public function form($action = '', $data=array(), $attributes = array(), $hidden = array()){

		theme_add( 'dijit.form.Form' );
		$attributes = $this->attribute( $attributes, 'dojoType', 'dijit.form.Form') ;
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
	 * @param $NAME: hidden field name
	 * @param $value: hidden field value
	 */
	public function hidden($NAME='', $value='' ){
		
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
	public function file_chooser( $connector='', $NAME='',$value='', $attr=array(), $style=array() ){

		theme_add('dojo.data.ItemFileReadStore');
		theme_add('dijit.tree.ForestStoreModel');
		theme_add('dijit.Tree');
		theme_add('dijit.Dialog');
		
		$attr = $this->attribute( $attr, 'onclick', "dijit.byId('filedlg_$NAME').show();");
		$attr = $this->attribute( $attr, 'id', $NAME );
		
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
	 * Folder chooser using the fsbrowser, use it with CAUTION
	 * it makes a dojo text input linked with a jquery fsbrowser
	 * @param $connector: path to php file to use as ajax backend
	 * @param $NAME: text input name and id
	 * @param $value: text input value
	 * @param $attr: attributes->value associative array
	 * @param $param: sfbrowser config object paramter->value associative array
	 * @param $style: property->value associative array
	 */
	public function folder_chooser( $connector='', $NAME='',$value='', $attr=array(), $style=array() ){

		theme_add('dojo.data.ItemFileReadStore');
		theme_add('dijit.tree.ForestStoreModel');
		theme_add('dijit.Tree');
		theme_add('dijit.Dialog');
		
		$attr = $this->attribute( $attr, 'onclick', "dijit.byId('filedlg_$NAME').show();");
		$attr = $this->attribute( $attr, 'id', $NAME );
		
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
	 * a date field picker using dojo
	 */
	public function date( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.DateTextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.DateTextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * a Time chooser input field
	 */
	public function time( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.TimeTextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.TimeTextBox" name="'.$NAME.'" value="'.$value.'" $attr >';
		return $text;
		
	}

	/**
	 * an input field with dojo
	 */
	public function textbox( $NAME='', $value='', $attr=array() ){
		
		theme_add('dijit.form.TextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.TextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * an button with dojo
	 */
	public function button( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Button');

		$attr = $this->_attributes_to_string( $attr );

		$text = '<button dojoType="dijit.form.Button" name="'.$NAME.'" '.$attr.' >'.$value.'</button>';
		return $text;
		
	}

	/**
	 * an tooltipbutton with dojo
	 * @param $text: button text
	 * @param $dialog: tooltip dialog content text
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
	 * an password field with dojo
	 */
	public function password( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.TextBox');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="password" dojoType="dijit.form.TextBox" name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * an input number spinner with dojo
	 */
	public function number( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.NumberSpinner');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<input type="text" dojoType="dijit.form.NumberSpinner"name="'.$NAME.'" value="'.$value.'" '.$attr.' >';
		return $text;
		
	}

	/**
	 * a textarea field auto grown
	 */
	public function textarea( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Textarea');

		$value = form_prep( $value );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<textarea  dojoType="dijit.form.Textarea" name="'.$NAME.'" '.$attr.' >'.$value.'</textarea>';
		return $text;
		
	}

	/**
	 * a permission field auto grow textarea
	 */
	public function permission( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.form.Textarea');

		$ci =& get_instance();
		$ci->load->helper('perm');
		$p_arr = perm_array();
		$t_text = 	'<b>Boolean variables : </b>'.implode('|', array_keys($p_arr['boolVars']) )
		.'<br><b>Variables : </b>'.implode('|', array_keys($p_arr['vars']) );

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
	 * an simple richtext editor with dojo
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
	 * a dropdown menu using dojo
	 * @param: options[ 'Label'=>'value' ]
	 */
	public function dropdown( $NAME='', $value='', $options=array(), $attr=array() ){

		theme_add('dijit.form.FilteringSelect');
		foreach( $options as $key=>$item )
		if( is_array($options) )
		$options[$key] = form_prep( $item );
		else
		$options->$key = form_prep( $item );

		$attr = $this->attribute( $attr, 'dojoType', 'dijit.form.FilteringSelect');
		$attr = $this->_attributes_to_string( $attr );

		$text = form_dropdown($NAME, $options, $value, $attr);
		return $text;
		
	}

	/**
	 * a section chooser dropdown field
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
	 * a checkbox using dojo
	 */
	public function checkbox( $NAME='', $value='', $checked=FALSE, $attr=array() ){

		theme_add('dijit.form.CheckBox');
		$attr = $this->attribute( $attr, 'dojoType', 'dijit.form.CheckBox');
		$attr = $this->_attributes_to_string( $attr );

		return form_checkbox($NAME, $value, $checked, $attr);
		
	}

	/**
	 * a radio button using dojo
	 */
	public function radio( $NAME='', $value='', $checked=FALSE, $attr=array() ){

		theme_add('dijit.form.CheckBox');
		$attr = $this->attribute( $attr, 'dojoType', 'dijit.form.RadioButton');
		$attr = $this->_attributes_to_string( $attr );

		return form_radio($NAME, $value, $checked, $attr);
		
	}

	/**
	 * a Tooltip using dojo
	 */
	public function tooltip( $NAME='', $value='', $attr=array() ){

		theme_add('dijit.Tooltip');
		$attr = $this->attribute( $attr, 'position', 'below', FALSE );
		$attr = _attributes_to_string( $attr );
		return '<div dojoType="dijit.Tooltip" connectId="'.$NAME.'" '.$attr.'>'.$value.'</div>';
		
	}

	/**
	 * an accordion of dojo toolkit
	 * @param
	 * 		$data: associative array of title=>panelHTML
	 */
	public function accordion( $data=array(), $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.AccordionContainer' );
		$style = $this->style( $style, 'width', '100%' , FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE );
		$style = $this->_array_to_style( $style );

		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.AccordionContainer" style="'.$style.'" '.$attr.' >';

		foreach($data as $key=>$value )
		$text .= '<div dojoType="dijit.layout.AccordionPane" title="'.$key.'" >'.$value.'</div>';
			
		$text .= "</div>";
		return $text;
		
	}

	/**
	 * a Tab container of dojo toolkit
	 * @param
	 * 		$data: associative array of tabTitle->panelHTML
	 */
	public function tab( $data=array(), $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.TabContainer' );
		theme_add( 'dijit.layout.ContentPane' );
		$style = $this->style( $style, 'width', '100%', FALSE );
		$style = $this->style( $style, 'height', '300px', FALSE );

		$style = $this->_array_to_style( $style );
		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.TabContainer" style="'.$style.'" '.$attr.' >';

		foreach($data as $key=>$value )
		$text .= '<div dojoType="dijit.layout.ContentPane" title="'.$key.'" >'.$value.'</div>';
			
		$text .= '</div>';
		return $text;
		
	}
	
	/**
	 * title panel with dojo
	 * @param $titel: panel title
	 * @param $body: panel contents
	 */
	public function titlepane( $title='', $body='', $attr=array() ){
		
		theme_add( 'dijit.TitlePane' );
		$attr = $this->_attributes_to_string( $attr );
		return '<div dojoType="dijit.TitlePane" title="'.$title.'" '.$attr.' >'.$body.'</div>';
		
	}

	/**
	 * a error box using jquery
	 */
	public function error( $text='', $attr=array() ){
		
		theme_add( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );

		return '<div class="error" '.$attr.' >'.$text.'</div>';
		
	}

	/**
	 * an Info box using jquery
	 */
	public function info( $text='', $attr=array() ){
		
		theme_add( 'assets/style/style.css' );
		$attr = $this->_attributes_to_string( $attr );

		return '<div class="info" '.$attr.' >'.$text.'</div>';
		
	}

	/**
	 * a horizontal spliter box with dojo
	 */
	public function hbox( $content='', $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.SplitContainer' );
		theme_add( 'dijit.layout.ContentPane' );

		$style = $this->style( $style, 'width', '100%', FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE);
		$style = $this->_array_to_style( $style );

		$attr = $this->_attributes_to_string( $attr );

		$text = '<div dojoType="dijit.layout.SplitContainer" orientation="horizontal" '.$attr.' style="'.$style.'" >';
		
		foreach( $content as $item )
			$text .= '<div dojoType="dijit.layout.ContentPane" >'.$item.'</div>';
		
		$text .= '</div>';

		return $text;
		
	}

	/**
	 * a vertical splitter box with dojo
	 */
	public function vbox( $content='', $attr=array(), $style=array() ){
		
		theme_add( 'dijit.layout.SplitContainer' );
		theme_add( 'dijit.layout.ContentPane' );

		$style = $this->style( $style, 'width', '100%', FALSE);
		$style = $this->style( $style, 'height', '300px', FALSE);
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
	 * return the result attributes object or array
	 * @param $attr: object or array of attributes wanted to be manipulated
	 * @param $key: attribute to set
	 * @param $value: value of the attribute to set
	 * @param $replace: will replace current attribute value if exists or add it if not otherwise will it will add attribute of not exists only
	 */
	public function attribute( $attr=array(), $key='', $value='', $replace=TRUE ){
		
		if( is_array($attr) ){
			if( !isset($attr[$key]) or (isset($attr[$key]) and $replace) ) $attr[$key] = $value;
		}else if( is_object($attr) ){
			if( !isset($attr->$key) or (isset($attr->$key) and $replace) ) $attr->$key =  $value;
		}else if( is_string($attr) ){
			$attr .= " $key=\"$value\"";
		}

		return $attr;
		
	}
	
	/**
	 * a file list chooser that return a text with some lines every line is
	 * a choosen file ( it's a textarea interface with jquery)
	 **/
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
	 */
	public function style( $attr=array(), $key='', $value='', $replace=TRUE ){
		
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

		return $attr;
		
	}

	/**
	 * helper function to convert the attribute array to HTML attributes
	 */
	protected function _attributes_to_string( $attr= array() ){
		
		if( is_string($attr) ) return $attr;

		$att = '';
		foreach ($attr as $key => $val)
		$att .= $key . '="' . str_replace('"','\"',$val) . '" ';

		return $att;
		
	}

	/**
	 * helper function to convert the paramters array to javascript object
	 */
	protected function _params_to_js( $param=array() ){
		
		$att = '';

		foreach ($param as $key => $val)
			$att .= ( $val[0]=='[' and $val[strlen($val)-1]==']' )
					?', ' . $key . ': ' . addslashes(str_replace('"','\"',$val)) .' '
					:', ' . $key . ':\'' . addslashes(str_replace('"','\"',$val)) . '\' ';

		return $att;
		
	}

	/**
	 * helper function to convert style array to CSS text
	 */
	protected function _array_to_style( $style=array() ){
		
		if( is_string( $style ) ) return $style;
		$arr = array();
		foreach( $style as $key=>$value )
			array_push( $arr, $key.':'.$value.';');
		
		return implode( '', $arr );
		
	}
}
