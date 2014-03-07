/*
Plugin for resizing height.
package Wp Inline Edit
Author : Ayush Singh <contact@webgarb.com>
URL : WebGarb.com
*/
(function() {

	tinymce.create('tinymce.plugins.AutohResize', {
		
		init : function(ed, url) {
		resize();
		ed.onKeyDown.add(function(ed) {
		resize();
		});
		ed.onChange.add(function(ed) {
		resize();
		});
		ed.onClick.add(function(ed) {
        resize();
      	});
		ed.onInit.add(function(ed, e) {
        resize();
     	});
		jQuery("#"+ed.id+"-wrap").live("click",function() {
		 resize();
		});
		jQuery("#"+ed.id).live("keypress",function() {
		 resize();
		});
		jQuery("#"+ed.id).live("keydown",function() {
		 resize();
		});
		jQuery("#wp-"+ed.id+"-wrap").click(function(){
		 resize();
		});
		function resize() {
			try {
			jQuery("#"+ed.id+"_ifr").css("height","");
			jQuery("#"+ed.id+"_ifr").contents().find('body').attr("style","overflow:hidden;max-height:auto;min-height:0;height:auto");
			height = jQuery("#"+ed.id+"_ifr").contents().find('body').get(0).scrollHeight;
			
			jQuery("#"+ed.id+"_ifr").css("height",height+20+"px");
			
			jQuery("#"+ed.id).css("height","").css("overflow","hidden").css("min-height","0");
			
			jQuery("#"+ed.id).css("height","2px");
			height_textarea = jQuery("#"+ed.id).get(0).scrollHeight;
			jQuery("#"+ed.id).css("height",height_textarea+5+"px");
			} catch(e) { /*console.log("resize_e");*/ }
		}
			
		},
		

	
	});

	// Register plugin
	tinymce.PluginManager.add('autohresize', tinymce.plugins.AutohResize);
})();