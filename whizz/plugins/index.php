<?php
include('functions/functions.php');
function whizz_user_modification_plugins_func()
{
	?>
    	<h1>
        	Plugin details here
        </h1>
   <?php
}
define('WHIZZ_PLUGINS_LIST_PLUGIN_URL','?page=plugin-list');
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_plugin',0 );
function whizz_enqueue_and_register_my_scripts_plugin()
{
	wp_register_style( 'reg_custom_plugin_css_h', plugin_dir_url(__FILE__).'css/plugin_style-change-style.css');
	wp_register_script( 'reg_custom_plugin_js_h', plugin_dir_url(__FILE__).'js/custom-js-plugin-view.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , false, true );	
	$main_js_obj_props = array(
							'home_url' => home_url(),
							'admin_url' => admin_url(),
							'admin_ajax_url' => admin_url().'admin-ajax.php',
							'for_plugin_url' => plugin_dir_url( __FILE__ ),
							);
	wp_localize_script( 'reg_custom_plugin_js_h', 'main_js_obj_plugin', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_plugin_js_h' );
   	wp_enqueue_style( 'reg_custom_plugin_css_h' );
}
/* Register, localize, enqueue script and style END */ 
add_action('wp_ajax_whizz_save_new_plugin_order_custom', 'whizz_save_new_plugin_order_custom_func' );
function whizz_save_new_plugin_order_custom_func()
{
	if($_POST['plugin_order'])
	{
		$list_of = $_POST['list_of_p'];
		$plugin_order = stripslashes($_POST['plugin_order']);
		$plugin_order = json_decode( $plugin_order );
		$plugin_order = whizz_dv_plugin_order_object_to_array($plugin_order);
		update_option(get_current_user_id().'_plugin_order_whizz_'.$list_of,$plugin_order);
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_dv_plugin_order_object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = whizz_dv_plugin_order_object_to_array($value);
        }
        return $result;
    }
    return $data;
}
function whizz_check_main_menu_whizz_plugins($menu_slug_check)
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
add_action('wp_ajax_whizz_search_plugin_wp_admin', 'whizz_search_plugin_wp_admin_func' );
function whizz_search_plugin_wp_admin_func()
{
	if(isset($_POST['search_term']) && $_POST['search_term'] != "")
	{
		$search_string = $_POST['search_term'];
		$plugin_list_temp = get_plugins();
		$ordered_appended_array = array();
		if(!empty($plugin_list_temp))
		{
			foreach($plugin_list_temp as $order_key => $order_value)
			{
				if(strstr(strtolower($order_value['Name']), strtolower($search_string)) != false || strstr(strtolower($order_value['Description']), strtolower($search_string)) != false) 
				{
					$ordered_appended_array[$order_key] = $order_value;
				}
			}
		}		
		$list = "<ul id='list_view_plugin'>";
		  ob_start();
		  foreach($ordered_appended_array as $plugin_key => $plugin_value)
		  {
			  ?>
			  <li class="li_inside_ul_list" id="<?php echo $plugin_key; ?>">
				  <div class="main_div_inside_li_list">
					  <div class="span6 col-md-6">
					  <div class="plugin_name_list">
					  <input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key; ?>" />
				   <input type="hidden" name="selected_<?php echo str_replace('.','_', $plugin_key); ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
					  <?php 
						  echo $plugin_value['Name'];
					  ?>
					  </div>
					  <ul>						 
						  <li>
							  <?php
								  echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
							  ?>
						  </li>
						  <li>
							  <?php
							  if( strstr($plugin_key, 'whizz-plugin') == false)
							  {
								  echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
							  }
							  ?> 
						  </li>						 
					  </ul>
					  </div>
						  <div class="span6 col-md-6">
					  <div class="plugin_desc_list">
					  <?php 
						  echo $plugin_value['Description'];
					  ?>
					  </div>
					  <ul>
					  <li>
					  <div class="plugin_version_list">Version 
					  <?php
						  echo $plugin_value['Version'];
					  ?>
					  </div>
					  </li> | <li>
					  <div class="plugin_author_name_list">
					  <?php 
						  echo $plugin_value['AuthorName'];
					  ?>
					  </div>
					  </li>| <li>
					  <div class="plugin_uri_list">
					  <?php 
						  echo "<a href='".$plugin_value['PluginURI']."'>More Details</a>";
					  ?>
					  </div>
					  </li>
					  </ul>
					  </div>
				  </div>
				  <div class="clear"></div>
			  </li>
			  <?php
		  }
		  $list .= ob_get_clean();
		  $list .="</ul>";
		wp_send_json_success( $list );
	}
	wp_send_json_error();
}