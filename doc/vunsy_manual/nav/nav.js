function create_menu(basepath)
{
	var base = (basepath == 'null') ? '' : basepath;

	document.write(
		'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +
		'<td class="td" valign="top">' +

		'<ul>' +
		'<li><a href="'+base+'index.html">User Guide Home</a></li>' +	
		'</ul>' +	

		'<h3>Basic Info</h3>' +
		'<ul>' +
			'<li><a href="'+base+'requirements.html">Server Requirements</a></li>' +
			'<li><a href="'+base+'license.html">License Agreement</a></li>' +
			'<li><a href="'+base+'changelog.html">Change Log</a></li>' +
			'<li><a href="'+base+'credits.html">Credits</a></li>' +
		'</ul>' +	
		
		'<h3>Installation</h3>' +
		'<ul>' +
			'<li><a href="'+base+'downloads.html">Downloading Vunsy</a></li>' +
			'<li><a href="'+base+'install.html">Installation Instructions</a></li>' +
		'</ul>' +
		
		'<h3>Introduction</h3>' +
		'<ul>' +
			'<li><a href="'+base+'getting_started.html">Getting Started</a></li>' +
		'</ul>' +	

				
		'</td><td class="td_sep" valign="top">' +

		'<h3>General Topics</h3>' +
		'<ul>' +
			'<li><a href="'+base+'components.html">Vunsy components</a></li>' +
			'<li><a href="'+base+'section_general.html">Sections Idea</a></li>' +
			'<li><a href="'+base+'applications_general.html">Applications Idea</a></li>' +
			'<li><a href="'+base+'layout_general.html">Layout Idea</a></li>' +
			'<li><a href="'+base+'widget_general.html">Widget Idea</a></li>' +
		'</ul>' +
		
		'</td><td class="td_sep" valign="top">' +

				
		'<h3>Library Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'app_library.html">Application library</a></li>' +
		'<li><a href="'+base+'gui_library.html">GUI library</a></li>' +
		'<li><a href="'+base+'vunsy_library.html">Vunsy library</a></li>' +
		'</ul>' +
		
		'<h3>Helper Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'perm_helper.html">Permissions helper</a></li>' +
		'<li><a href="'+base+'page_helper.html">Page helper</a></li>' +
		'</ul>' +
		
		'<h3>Model Reference</h3>' +
		'<ul>' +
		'<li><a href="'+base+'content_model.html">Content model</a></li>' +
		'<li><a href="'+base+'layout_model.html">Layout model</a></li>' +
		'<li><a href="'+base+'widget_model.html">Widget model</a></li>' +
		'<li><a href="'+base+'section_model.html">Section model</a></li>' +
		'<li><a href="'+base+'user_model.html">User model</a></li>' +
		'<li><a href="'+base+'userlevel_model.html">User level model</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>Additional Resources</h3>' +
		'<ul>' +
		'<li><a href="http://Vunsy.com/forums/">Community Forums</a></li>' +
		'<li><a href="http://Vunsy.com/wiki/">Community Wiki</a></li>' +
		'</ul>' +	
		
		'</td></tr></table>');
}
