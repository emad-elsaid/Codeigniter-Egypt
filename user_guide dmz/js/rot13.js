/**
 * ROT13
 * 
 * ROT13 encryption.
 *
 * @licence	MIT License
 * @author	stensi
 * @link	http://stensi.com
 */
var ROT13 = new Class({

	// implements
	Implements: [Options],

	// options
	options: {
		map: null
	},

	/**
	 * Constructur - Class.initialize
	 * 
	 * @param	object
	 * @return	void
	 */
	initialize: function(options) {

		this.setOptions(options);

	        if (this.options.map != null)
		{
			return;
		}
              
		var map = new Array();
		var s   = "abcdefghijklmnopqrstuvwxyz";
	
		for (i = 0; i < s.length; i++)
		{
			map[s.charAt(i)] = s.charAt((i + 13) % 26);
		}

		for (i = 0; i < s.length; i++)
		{
			map[s.charAt(i).toUpperCase()] = s.charAt((i + 13) % 26).toUpperCase();
		}

		this.options.map = map;
	},

	convert: function(a) {
		var s = "";

		for (i = 0; i < a.length; i++)
		{
			var b = a.charAt(i);
			s += ((b>='A' && b<='Z') || (b>='a' && b<='z') ? this.options.map[b] : b);
		}

		return s;
	}
});
