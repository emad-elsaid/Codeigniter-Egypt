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
 * @license	MIT License
 * @author	stensi
 * @link	http://stensi.com
 */
var Menu = new Class({

	// implements
	Implements: [Options],

	// options
	options: {
		basepath: '',
		pagespath: '',
		last: false
	},

	menu: [
		[
			{ url: "../index", name: "User Guide Home" },
			{ url: "toc", name: "Table of Contents Page" },
			"Basic Info",
			{ url: "license", name: "License Agreement" },
			{ url: "changelog", name: "Change Log" },
			{ url: "roadmap", name: "Road Map" },
			{ url: "credits", name: "Credits" },
			{ url: "manual", name: "Using This Manual" },
			"Installation",
			{ url: "requirements", name: "Server Requirements" },
			{ url: "download", name: "Downloading Datamapper ORM" },
			{ url: "installation", name: "Installation Instructions" },
			{ url: "upgrade", name: "Upgrading Instructions" },
			{ url: "troubleshooting", name: "Troubleshooting / FAQ" },
			"Glossary",
			{ url: "glossary", name: "Glossary" }
		],
		[
			"General Topics",
			{ url: "gettingstarted", name: "Getting Started" },
			{ url: "config", name: "Configuration" },
			{ url: "reservednames", name: "Reserved Names" },
			{ url: "database", name: "Database Tables" },
			{ url: "models", name: "DataMapper Models" },
			{ url: "prefix", name: "Setting up Table Prefixes" },
			{ url: "relationtypes", name: "Relationship Types" },
			{ url: "settingrelations", name: "Setting up Relationships" },
			{ url: "advancedrelations", name: "Advanced Relationships" },
			{ url: "controllers", name: "DataMapper in Controllers" },
			{ url: "timestamp", name: "Automated Timestamps" },
			{ url: "localize", name: "Localization" },
			{ url: "validation", name: "Validation" },
			{ url: "getrules", name: "Get Rules" },
			{ url: "transactions", name: "Transactions" },
			{ url: "prodcache", name: "Production Cache"}
		],
		[
			"Functions",
			{ url: "get", name: "Get" },
			{ url: "getadvanced", name: "Get (Advanced)" },
			{ url: "getby", name: "Get By" },
			{ url: "getalt", name: "Get (Alternatives)" },
			{ url: "functions", name: "SQL Functions" },
			{ url: "subqueries", name: "Subqueries" },
			{ url: "clonecopy", name: "Cloning and Copies" },
			{ url: "save", name: "Save" },
			{ url: "update", name: "Update" },
			{ url: "delete", name: "Delete" },
			{ url: "deleteall", name: "Delete All" },
			{ url: "refreshall", name: "Refresh All" },
			{ url: "validate", name: "Validate" },
			{ url: "count", name: "Counting" },
			{ url: "utility", name: "Utility Methods" }
		],
		[
			"Advanced Usage",
			{ url: "advancedusage", name: "Advanced Usage" },
			"Relationships",
			{ url: "accessingrelations", name: "Accessing Relationships" },
			{ url: "savingrelations", name: "Saving Relationships" },
			{ url: "deletingrelations", name: "Deleting Relationships" },
			{ url: "joinfields", name: "Working with Join Fields" },
			"Extending DataMapper",
			{ url: "extensions", name: "Using Extensions"},
			{ url: "extwrite", name: "Writing an Extension"},
			{ url: "extlist", name: "Included Extensions"},
			"Examples",
			{ url: "examples", name: "Example Application" }
		]
	],

	makeMenu: function() {
		var m = '<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>';
		var listStarted, item;
		for(var i=0; i<this.menu.length; i++) {
			m += '<td valign="top">';

			listStarted = false;
			for(var j=0; j<this.menu[i].length; j++) {
				item = this.menu[i][j];
				if(typeof item == "string") {
					if(listStarted) {
						m += '</ul>'
						listStarted = false;
					}
					m += '<h3>'+item+'</h3>';
				} else {
					if(!listStarted) {
						listStarted = true;
						m += '<ul>';
					}
					m += '<li><a href="' + this.options.pagespath + item.url + '.html">'+item.name +'</a></li>';
				}
			}
			if(listStarted) {
				m += '</ul>';
			}

			m += '</td>';
		}
		m += '</table>';
		return m;
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

		var menu = this.makeMenu();
		$('nav_inner').set('html', menu );

		var el = $('toc_placeholder');
		if(el) {
			el.set('html', menu);
		}

		var url = document.location.pathname.replace(/^.*\/([\w]+)\.html$/, "$1");

		var prev = $('footer_previous');
		if(url && prev) {
			var next = $('footer_next');
			var lastItem = null;
			var found = false;
			var nextItem = null;
			for (var i = 0; i < this.menu.length; i++) {
				if(nextItem !== null) {
					break;
				}
				for (var j = 0; j < this.menu[i].length; j++) {
					var item = this.menu[i][j];
					if(typeof item == "string" || item.url == "../index" || item.url == "toc") {
						continue;
					}
					if(found) {
						nextItem = item;
						break;
					}
					if(item.url == url) {
						found = true;
					} else {
						lastItem = item;
					}
				}
			}
			if(this.options.last) {
				found = false;
				for (var i = 0; i < this.menu.length; i++) {
					for (var j = 0; j < this.menu[i].length; j++) {
						var item = this.menu[i][j];
						if(typeof item !== "string" && item.url == this.options.last ) {
							found = true;
							lastItem = item;
							break;
						}
					}
					if(found) {
						break;
					}
				}
			}
			if(found && lastItem !== null) {
				prev = prev.getFirst();
				prev.set('html', lastItem.name);
				prev.set('href', this.options.pagespath + lastItem.url + ".html");
			} else {
				prev.setStyle('display', 'none');
			}
			if(nextItem !== null) {
				next = next.getFirst();
				next.set('html', nextItem.name);
				next.set('href', this.options.pagespath + nextItem.url + ".html");
			} else {
				next.setStyle('display', 'none');
			}
		}

		// add search box
		var bc = $('breadcrumb');
		var td = document.createElement('td');
		td.id = "searchbox";
		td.innerHTML = '<form action="http://www.google.com/cse" id="cse-search-box"><div><input type="hidden" name="cx" value="009945192369939474941:ne90ecfhzhm" /><input type="hidden" name="ie" value="UTF-8" />Search User Guide: <input class="input search" id="googleSearchBox" type="text" name="q" size="31" /> <input class="submit" type="submit" name="sa" value="Go" /></div></form>';
		bc.parentNode.appendChild(td);
	}
});
