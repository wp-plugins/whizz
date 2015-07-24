<?php
function whizz_user_modification_horizontal_menu_func()
{
	?>
    	<h1>
        	Plugin details here
        </h1>
    <?php
}
function whizz_plugin_func()
{
	?>
    <h1 class="plugin-title-main">WHIZZ Menus</h1>
    <?php
}
function whizz_horizontal_menu_style_func()
{	
	?>
 	Write something here   
    <?php
}
function whizz_horizontal_menu_func()
{
	?>
    <div class="main-container-menus-new-plugins">
    <h1 class="main-plugin-title">WHIZZ Menus</h1>
    Write something here
	</div>
    <?php
}
function whizz_dv_object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = whizz_dv_object_to_array($value);
        }
        return $result;
    }
    return $data;
}
function whizz_check_main_menu_whizz_plugin_horizontal($menu_slug_check)
{
	global $menu;
	$flag=0;
	foreach($menu as $single_menu)
	{
		 if($single_menu[2] == $menu_slug_check)
		 {
			 $flag=1;
		 }
	}
	return $flag;
}
/* Save new order in options, START */
add_action('wp_ajax_whizz_save_new_menu_order', 'whizz_save_new_menu_order_func' );
if(!function_exists('whizz_save_new_menu_order_func'))
{
	function whizz_save_new_menu_order_func()
	{
		if($_POST['pages'])
		{
			$pages = $_POST['pages'];
			$t_pages = array();
			foreach($pages as $page ) {
				$t_page = $page;
				if( strstr($t_page, "page=") ) {
					$t_page = explode("page=", $t_page);
					$t_page = $t_page[1];
				}
				$t_pages[] = $t_page;
			}
			$pages = $t_pages;
			update_option( get_current_user_id().'new_wp_custom_menu_order', $pages );
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* Save new order in options, END */
/* Filter to arrange the wp menus START*/
function whizz_custom_menu_order($menu_order) {
	$pages = get_option(get_current_user_id().'new_wp_custom_menu_order');
	if(!empty($pages))
	{
		return $pages;
	}
	return $menu_order;
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'whizz_custom_menu_order');
/* Filter to arrange the wp menus END*/
