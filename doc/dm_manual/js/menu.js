/**
 * Menu
 * 
 * Adds a sliding menu to the top of the page.
 *
 * Usage:
 *
 *	window.addEvent('domready', function() {
 *		new Menu();
 *	});
 *
 * @licence	MIT License
 * @author	stensi
 * @link	http://stensi.com
 */
var Menu = new Class({

	// implements
	Implements: [Options],

	// options
	options: {
		basepath: '',
		pagespath: ''
	},

	/**
	 * Constructur - Class.initialize
	 * 
	 * @param	object
	 * @return	void
	 */
	initialize: function(options) {

		this.setOptions(options);


		var mySlide = new Fx.Slide('nav_inner').hide();

		$('nav_toggle').addEvent('click', function() {
			mySlide.toggle();
		});

		$('nav_inner').set('html',
			
			'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +

			'<td class="td" valign="top">' +


			'<ul>' +

			'<li><a href="' + this.options.basepath + 'index.html">User Guide Home</a></li>' +	

			'<li><a href="' + this.options.pagepath + 'toc.html">Table of Contents Page</a></li>' +

			'</ul>' +	


			'<h3>Basic Info</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'requirements.html">Server Requirements</a></li>' +

				'<li><a href="' + this.options.pagepath + 'license.html">License Agreement</a></li>' +

				'<li><a href="' + this.options.pagepath + 'changelog.html">Change Log</a></li>' +

				'<li><a href="' + this.options.pagepath + 'roadmap.html">Road Map</a></li>' +

				'<li><a href="' + this.options.pagepath + 'credits.html">Credits</a></li>' +

			'</ul>' +	
		

			'<h3>Installation</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'download.html">Downloading DataMapper</a></li>' +

				'<li><a href="' + this.options.pagepath + 'installation.html">Installation Instructions</a></li>' +

				'<li><a href="' + this.options.pagepath + 'troubleshooting.html">Troubleshooting</a></li>' +

			'</ul>' +
		

			'</td><td class="td_sep" valign="top">' +


			'<h3>General Topics</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'gettingstarted.html">Getting Started</a></li>' +

				'<li><a href="' + this.options.pagepath + 'config.html">Configuration</a></li>' +

				'<li><a href="' + this.options.pagepath + 'reservednames.html">Reserved Names</a></li>' +

				'<li><a href="' + this.options.pagepath + 'database.html">Database Tables</a></li>' +

				'<li><a href="' + this.options.pagepath + 'models.html">DataMapper Models</a></li>' +

				'<li><a href="' + this.options.pagepath + 'prefix.html">Setting up Table Prefixes</a></li>' +

				'<li><a href="' + this.options.pagepath + 'relationtypes.html">Relationship Types</a></li>' +

				'<li><a href="' + this.options.pagepath + 'settingrelations.html">Setting up Relationships</a></li>' +

				'<li><a href="' + this.options.pagepath + 'controllers.html">DataMapper in Controllers</a></li>' +

				'<li><a href="' + this.options.pagepath + 'timestamp.html">Automated Timestamps</a></li>' +

				'<li><a href="' + this.options.pagepath + 'validation.html">Validation</a></li>' +
				
				'<li><a href="' + this.options.pagepath + 'transactions.html">Transactions</a></li>' +

			'</ul>' +

		
			'</td><td class="td_sep" valign="top">' +


			'<h3>Functions</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'get.html">Get</a></li>' +

				'<li><a href="' + this.options.pagepath + 'getby.html">Get By</a></li>' +

				'<li><a href="' + this.options.pagepath + 'getclone.html">Get Clone</a></li>' +

				'<li><a href="' + this.options.pagepath + 'getcopy.html">Get Copy</a></li>' +

				'<li><a href="' + this.options.pagepath + 'save.html">Save</a></li>' +

				'<li><a href="' + this.options.pagepath + 'delete.html">Delete</a></li>' +

				'<li><a href="' + this.options.pagepath + 'deleteall.html">Delete All</a></li>' +

				'<li><a href="' + this.options.pagepath + 'refreshall.html">Refresh All</a></li>' +

				'<li><a href="' + this.options.pagepath + 'validate.html">Validate</a></li>' +

				'<li><a href="' + this.options.pagepath + 'clear.html">Clear</a></li>' +

				'<li><a href="' + this.options.pagepath + 'count.html">Count</a></li>' +

				'<li><a href="' + this.options.pagepath + 'exists.html">Exists</a></li>' +

				'<li><a href="' + this.options.pagepath + 'query.html">Query</a></li>' +

			'</ul>' +


			'</td><td class="td_sep" valign="top">' +


			'<h3>Relationships</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'accessingrelations.html">Accessing Relationships</a></li>' +

				'<li><a href="' + this.options.pagepath + 'savingrelations.html">Saving Relationships</a></li>' +

				'<li><a href="' + this.options.pagepath + 'deletingrelations.html">Deleting Relationships</a></li>' +

			'</ul>' +	
			
			
			'<h3>Examples</h3>' +

			'<ul>' +

				'<li><a href="' + this.options.pagepath + 'schema.html">Database Schema</a></li>' +
				
				'<li><a href="' + this.options.pagepath + 'examples.html">Usage Examples</a></li>' +

			'</ul>' +	


			'</td></tr></table>'
		);
	}
});
