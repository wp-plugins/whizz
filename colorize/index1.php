<?php
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_color',0 );
function whizz_enqueue_and_register_my_scripts_color()
{
	wp_register_style( 'reg_custom_css_h', plugin_dir_url(__FILE__).'css/color-change-style.css');
	wp_register_script( 'reg_custom_colorize_js_h', plugin_dir_url(__FILE__).'js/custom-js-colorize.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable','wp-color-picker') , false, true );
	$main_js_obj_props = array(
							'home_url' => home_url(),
							'admin_url' => admin_url(),
							'admin_ajax_url' => admin_url().'admin-ajax.php',
							'for_plugin_url' => plugin_dir_url( __FILE__ ),
							);
	wp_localize_script( 'reg_custom_colorize_js_h', 'main_js_obj_color_picker', $main_js_obj_props );
   	wp_enqueue_script( 'reg_custom_colorize_js_h' );
   	wp_enqueue_style( 'reg_custom_css_h' );
	wp_enqueue_style( 'wp-color-picker' );
}
/* Register, localize, enqueue script and style END */ 
/* create element in wp admin dashboard START */
function whizz_example_add_dashboard_widgets_color()
{
	wp_add_dashboard_widget(
                 'example_dashboard_widget',         // Widget slug.
                 'WHIZZ Colorize',         // Title.
                 'whizz_example_dashboard_widget_function_color' // Display function.
        );
}
add_action( 'wp_dashboard_setup', 'whizz_example_add_dashboard_widgets_color' );
function whizz_example_dashboard_widget_function_color() 
{
	ob_start();
	?>
   	<div>
    <h1>Color Palette to change menus color</h1>
    <div class="choice_radio">
    	<input type="radio" name="radio_choice_color" id="choice_background_color" />
        <label for="choice_background_color">Background color for menus</label><br />
    	<input type="radio" name="radio_choice_color" id="choice_hover_color" />
        <label for="choice_hover_color">Hover color for menus</label>
    </div>
    <div style='width:100%; display:none;' id='bgcolor_browserw'><strong> Background color for menus : </strong>
      <input type='text' id='colorpallete' name='colorpallete'>
    </div>
    <br />
    <div style='width:100%; display:none;' id='hovercolor_browserw'><strong> Hover color for menus : </strong>
      <input type='text' id='colorpallete_hover' name='colorpallete_hover'>
    </div>
    <div id='reset_all'>
    <strong>Reset Bucket</strong>
    </div>
     <div id='reset_menus_color' style=''>
    	<strong>Reset Menus Color</strong>
    </div>
    <div style="clear:both;"></div>
    <div id='DivToShow'></div>
    </div>
    <?php
	$content = ob_get_clean();
	echo $content;
}
/* create element in wp admin dashboard END */ 
/* For Background color START */
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_save_color_of_menu', 'whizz_save_color_of_menu_func' );
if(!function_exists('whizz_save_color_of_menu_func'))
{
	function whizz_save_color_of_menu_func()
	{
		if($_POST['menu_id'] && $_POST['menu_color'])
		{
			$menu_id = $_POST['menu_id'];
			$menu_color = $_POST['menu_color'];
			$data = get_option(get_current_user_id().'selected_menus_colors');
			$data[$menu_id] = $menu_color;
			update_option(get_current_user_id().'selected_menus_colors', $data);
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* save menu ID and color of the menu via ajax call END */
/* get id and color of the menu via ajax call START */
add_action('wp_ajax_whizz_get_color_of_menu', 'whizz_get_color_of_menu_func' );
if(!function_exists('whizz_get_color_of_menu_func'))
{
	function whizz_get_color_of_menu_func()
	{
		wp_send_json_success(get_option(get_current_user_id().'selected_menus_colors'));
	}
} 
/* get id and color of the menu via ajax call  END */
/* For Background color END */
/* For Hover color START */
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_save_color_of_menu_hover', 'whizz_save_color_of_menu_hover_func' );
if(!function_exists('whizz_save_color_of_menu_hover_func'))
{
	function whizz_save_color_of_menu_hover_func()
	{
		if($_POST['menu_id'] && $_POST['menu_color'])
		{
			$menu_id = $_POST['menu_id'];
			$menu_color = $_POST['menu_color'];
			$data = get_option(get_current_user_id().'selected_menus_colors_hover');
			$data[$menu_id] = $menu_color;
			update_option(get_current_user_id().'selected_menus_colors_hover', $data);
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* save menu ID and color of the menu via ajax call END */
/* get id and color of the menu via ajax call START */
add_action('wp_ajax_whizz_get_color_of_menu_hover', 'whizz_get_color_of_menu_hover_func' );
if(!function_exists('whizz_get_color_of_menu_hover_func'))
{
	function whizz_get_color_of_menu_hover_func()
	{
		wp_send_json_success(get_option(get_current_user_id().'selected_menus_colors_hover'));
	}
} 
/* get id and color of the menu via ajax call  END */
/* For Hover color END */
function whizz_check_main_menu_whizz_plugin_colorize($menu_slug_check)
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
/* save menu ID and color of the menu via ajax call START */
add_action('wp_ajax_whizz_reset_menus_color', 'whizz_reset_menus_color_func' );
if(!function_exists('whizz_reset_menus_color_func'))
{
	function whizz_reset_menus_color_func()
	{
		if($_POST['task'] && $_POST['task'] == "reset")
		{
			update_option(get_current_user_id().'selected_menus_colors', '');
			update_option(get_current_user_id().'selected_menus_colors_hover', '');
			update_option(get_current_user_id().'selected_top_menus_colors', '');
			update_option(get_current_user_id().'selected_top_menus_colors_hover', '');
			wp_send_json_success('Success');
		}
		else
		{
			wp_send_json_error();
		}
	}
} 
/* save menu ID and color of the menu via ajax call END */