<?php
/*
 * Plugin Name: WHIZZ
 * Plugin URI: https://whizz.us.com
 * Description: WHIZZ helps you quickly organize, manage and add a color-coded interface to your WordPress Admin. Why accept bland? WHIZZ for WordPress is guaranteed to brighten up your day, and your WP-Admin! 
 * Version: 1.0.4
 * Author: Browserweb Inc
 * Author URI: https://whizz.us.com
*/
add_action('admin_menu','whizz_add_menu_colorize_menu_func');
function whizz_add_menu_colorize_menu_func()
{
	add_menu_page( 'WHIZZ', 'WHIZZ', 'manage_options', 'whizz-plugin', 'whizz_plugin_colorize_func',  plugin_dir_url( __FILE__ )."images/logo_s.png" );
	add_submenu_page( 'whizz-plugin', 'WHIZZ Features', 'WHIZZ Features', 'manage_options', 'whizz-plugin', 'whizz_plugin_colorize_func');	
	add_submenu_page( 'whizz-plugin', 'Colorize', 'Colorize', 'manage_options', 'colorize_menus', 'whizz_colorize_menus_func');	
	add_submenu_page( 'whizz-plugin', 'Menus', 'Menus', 'manage_options', 'whizz_menus', 'whizz_menus_func');	
	add_submenu_page( 'whizz-plugin', 'Plugins', 'Plugins', 'manage_options', 'plugin-list', 'whizz_plugin_modification_func');
	add_submenu_page( 'whizz-plugin', 'Users', 'Users', 'manage_options', 'users-list', 'whizz_user_modification_func');
	add_submenu_page( 'whizz-plugin', 'WHIZZ Support', 'WHIZZ Support', 'manage_options', 'whizz-support', 'whizz_support_f_func');
	
	$user_menu_choice = get_option(get_current_user_id().'user_whizz_plugin_choice');
	if(!empty($user_menu_choice))
	{
		include_once('colorize/index_bar_menu.php');
	}
	else
	{
		include_once('colorize/index_bar_menu.php');
	}
}
function whizz_plugin_colorize_func()
{
	include_once(plugin_dir_path(__FILE__)."center-body.php");
}
function whizz_support_f_func()
{
	include_once(plugin_dir_path(__FILE__)."center-content-support.php");
}
function whizz_menus_func()
{
	?> 
    <div class="main-container-menus-new-plugins">
    <h1 class="main-plugin-title-menu">WHIZZ Menus</h1>
    <div class="main-container-menus-new-plugins dashboard_widget">
      <div class="contaner-second-block-new" style="text-align:center;">
        <h1>Select & Drag Separator</h1>
        <ul id="seperator_to_select">
          <li class="seperator_to_select_li"></li>
          <div class="clear"></div>
        </ul>
        <br />
        <div id="reset_separators" class="reset_separators_btn">Reset Separators</div>
      </div>
      <div class="clear"></div>
    </div>
        </div>
    <?php
}
function whizz_colorize_menus_func()
{
	ob_start();
	?>
    <h1 class="whizz-h1">WHIZZ Colorize</h1>
    <div class="wpbody-content-box">
   	<div class="whizz-left-align">
    <strong class="w-font">Color Palette to change menus color</strong>
	<br />
    <div class="choice_radio">
    	<input type="radio" name="radio_choice_color" id="choice_background_color" />
        <label for="choice_background_color">Background color for menus</label> &nbsp; &nbsp; 
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
    <br />
    <div id='reset_all' class='reset-button'>
    	<strong>Reset Bucket</strong>
    </div>
     <div id='reset_menus_color' class='reset-button'>
    	<strong>Reset Menus Color</strong>
    </div>
    <div style="clear:both;"></div>
    <div id='DivToShow'></div>
    </div></div>
    <?php
	$content = ob_get_clean();
	echo $content;
}
function whizz_plugin_modification_func()
{
	include_once('plugins/plugin-list.php');
}
function whizz_user_modification_func()
{	
	include_once('users/user-list.php');	
}
$page_size = get_option(get_current_user_id().'_item_per_page');
if(!$page_size)
{
	update_option(get_current_user_id().'_item_per_page', 10);
}
include_once('colorize/index.php');
include_once('colorize/index-rearrange.php');
include_once('plugins/index.php');	
include_once('users/index.php');
function whizz_add_dashboard_widgets_seperator()
{
	wp_add_dashboard_widget(
                 'whizz_seperator',         // Widget slug.
                 'WHIZZ Separator',         // Title.
                 'whizz_add_dashboard_widgets_seperator_func' // Display function.
        );
}
add_action( 'wp_dashboard_setup', 'whizz_add_dashboard_widgets_seperator' );
function whizz_add_dashboard_widgets_seperator_func()
{
	?>
    <div class="main-container-menus-new-plugins dashboard_widget">
      <div class="contaner-second-block-new" style="text-align:center;">
        <h1>Select & Drag Separator</h1>
        <ul id="seperator_to_select">
          <li class="seperator_to_select_li"></li>
          <div class="clear"></div>
        </ul>
        <br />
        <div id="reset_separators" class="reset_separators_btn">Reset Separators</div>
      </div>
      <div class="clear"></div>
    </div>
	<?php
}
/* Save separators, START */
add_action('wp_ajax_whizz_save_separators', 'whizz_save_separators_func' );
if(!function_exists('whizz_save_separators_func'))
{
	function whizz_save_separators_func()
	{
		if(isset($_POST['sep_loc']))
		{
			if($_POST['sep_loc'] == "reset")
			{
				update_option(get_current_user_id().'_separator_locations', '');
				wp_send_json_success($_POST['sep_loc']);
			}
			else
			{
				update_option(get_current_user_id().'_separator_locations', $_POST['sep_loc']);
				wp_send_json_success($_POST['sep_loc']);
			}
		}
		else
		{
			wp_send_json_error();
		}
	}
}
/* Save separators, END */
/* Get separators, START */
add_action('wp_ajax_whizz_get_separators', 'whizz_get_separators_func' );
if(!function_exists('whizz_get_separators_func'))
{
	function whizz_get_separators_func()
	{
		$separators = get_option(get_current_user_id().'_separator_locations');
		if(!empty($separators))
		{
			wp_send_json_success($separators);
		}
		else
		{
			wp_send_json_success(false);
		}
	}
}
/* Get separators, END */
/* Get separators, START */
add_action('wp_ajax_whizz_reset_separators', 'whizz_reset_separators_func' );
if(!function_exists('whizz_reset_separators_func'))
{
	function whizz_reset_separators_func()
	{
		update_option(get_current_user_id().'_separator_locations', '');
		wp_send_json_success($separators);
	}
}
/* Get separators, END */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_sep' );
function whizz_enqueue_and_register_my_scripts_sep()
{
	wp_register_script( 'reg_custom_sep_js_h', plugin_dir_url(__FILE__).'js/common.js',array('jquery'), false, true );
	$main_js_obj_props = array(
							'home_url' => home_url(),
							'admin_url' => admin_url(),
							'admin_ajax_url' => admin_url().'admin-ajax.php',
							);
	wp_localize_script( 'reg_custom_sep_js_h', 'main_js_obj_common', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_sep_js_h' );
	
	wp_register_style( 'reg_custom_sep_css_h', plugin_dir_url(__FILE__).'css/sep_style_custom.css');
	wp_enqueue_style( 'reg_custom_sep_css_h' );
}