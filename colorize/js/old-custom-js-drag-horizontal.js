(function($){
	$(document).ready(function(e) {
		var info_popup_div = "";		
		jQuery("#adminmenu").sortable(
		{
			start:function( event, ui )
			{
				$(ui.helper).addClass('dragmenu-class');
			},
			over: function()
			{
				sortableIn = 1;
			},
			out: function()
			{
				sortableIn = 0;
			},
			receive: function(event, ui)
			{
				// save code here
				whizz_call_back_seperator_save();
				$("#seperator_to_select").html('<li class="seperator_to_select_li"></li>');
				received = 1;
			},
			beforeStop: function(event, ui) 
			{
				if (typeof sortableIn === 'undefined') 
				{ 
				}
				else
				{
					if(sortableIn == 0)
					{
						if($(ui.item).hasClass('seperator_to_select_li_after_drag'))
						{
							ui.item.remove();
						}
					}
				}
			},
			stop:function(event, ui)
			{
				var menu_color_existing = $(ui.item).css('background-color');
				whizz_call_back_drop();
				whizz_call_back_seperator_save();	
				var menu_id = $(ui.item).attr('id');
				var menu_color ="."+menu_id+"s {background-color: "+ menu_color_existing+" !important; }";
				whizz_save_id_color(menu_id, menu_color);
			},
			connectWith: "#adminbarnew_bar",
			helper: "clone",
			revert: true
		});	
    });
	
	function whizz_call_back_drop()
	{
		var new_menu_order = [];
		jQuery(".menu-top > a").each(function(index, element) {
			new_menu_order.push($(element).attr('href'))
        });
		if(new_menu_order)
		{
			$.ajax({
				url: main_js_obj_horizontal.admin_ajax_url,
				type: "POST",
				data:	{
					action: 'whizz_save_new_menu_order',
					pages: new_menu_order,
				},
				dataType: "json"
			}).done(function( r ) {	
				if( r.success )
				{
					console.log('Successful');
				}
				else 
				{
					console.log('Not Successful.');
				}
			}).fail(function( jqXHR, textStatus ) {
				console.log('Not Successful.');
			});
		}
	}
	function whizz_call_back_seperator_save()
	{
		var separator = [];
		var i=0;
		jQuery("#adminmenu > li").each(function(index, element) {
			if(jQuery(this).hasClass('seperator_to_select_li_after_drag'))
			{
				separator.push(i);
				jQuery(this).attr('id','sep_'+i);
			}
			else
			{
				i++;
			}
        });
		if(separator)
		{
			if(separator == "")
				separator="reset";
			jQuery.ajax({
				url: main_js_obj_horizontal.admin_ajax_url,
				type: "POST",
				data:	{
					action: 'whizz_save_separators',
					sep_loc: separator,
				},
				dataType: "json"
			}).done(function( r ) {	
				if( r.success )
				{
					console.log('Successful');
				}
				else 
				{
					console.log('Not Successful.');
				}
			}).fail(function( jqXHR, textStatus ) {
				console.log('Not Successful.');
			});
		}
	}
})(jQuery)