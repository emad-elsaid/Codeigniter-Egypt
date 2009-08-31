var fluid = {
Toggle : function(){
		$('.toggle>h2').click(
			function(){
				if( $(this).next().is(':hidden')==true )
					$(this).next().fadeIn();
				else
					$(this).next().fadeOut();
			}
		);
},
Kwicks : function(){
	var animating = false;
    $("#kwick .kwick")
        .bind("mouseenter", function(e) {
            if (animating) return false;
            animating == true;
            $("#kwick .kwick").not(this).animate({ "width": 125 }, 200);
            $(this).animate({ "width": 485 }, 200, function() {
                animating = false;
            });
        });
    $("#kwick").bind("mouseleave", function(e) {
        $(".kwick", this).animate({ "width": 215 }, 200);
    });
},
SectionMenu : function(){
	$("#section-menu")
        .accordion({
            "header": "a.menuitem"
        })
        .bind("accordionchangestart", function(e, data) {
            data.newHeader.next().andSelf().addClass("current");
            data.oldHeader.next().andSelf().removeClass("current");
        })
        .find("a.menuitem:first").addClass("current")
        .next().addClass("current");
},
Accordion: function(){
	$("#accordion").accordion({
        'header': "h3.atStart"
    }).bind("accordionchangestart", function(e, data) {
        data.newHeader.css({
            "font-weight": "bold",
            "background": "#fff"
        });

        data.oldHeader.css({
            "font-weight": "normal",
            "background": "#eee"
        });
    }).find("h3.atStart:first").css({
        "font-weight": "bold",
        "background": "#fff"
    });
}
}
jQuery(function ($) {
	if($("#accordion").length){fluid.Accordion();}
	if($(".toggle").length){fluid.Toggle();}
	if($("#kwick .kwick").length){fluid.Kwicks();}
	if($("#section-menu").length){fluid.SectionMenu();}
});
