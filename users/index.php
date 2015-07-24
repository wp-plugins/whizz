<?php
include_once('functions/functions.php');
function whizz_user_modification_users_func()
{
	?>
    	<h1>
        	Plugin details here
        </h1>
    <?php
}
/* Register, localize, enqueue script and style START */
add_action( 'admin_enqueue_scripts', 'whizz_enqueue_and_register_my_scripts_users',0 );
function whizz_enqueue_and_register_my_scripts_users()
{
	wp_register_style( 'reg_custom_users_css_h', plugin_dir_url(__FILE__).'css/user_style-change-style.css');
	wp_register_script( 'reg_custom_users_js_h', plugin_dir_url(__FILE__).'js/custom-js-user-view.js',array('jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , false, true );
	$main_js_obj_props = array(
							'home_url' => home_url(),
							'admin_url' => admin_url(),
							'admin_ajax_url' => admin_url().'admin-ajax.php',
							'for_plugin_url' => plugin_dir_url( __FILE__ ),
							);
	wp_localize_script( 'reg_custom_users_js_h', 'main_js_obj_user', $main_js_obj_props );	
   	wp_enqueue_script( 'reg_custom_users_js_h' );
   	wp_enqueue_style( 'reg_custom_users_css_h' );
}
/* Register, localize, enqueue script and style END */ 
/* Define Constants START */
define('WHIZZ_USERS_LIST_PLUGIN_URL','?page=users-list');
/* Define Constants START */
add_action('wp_ajax_whizz_save_new_user_order_custom', 'whizz_save_new_user_order_custom_func' );
function whizz_save_new_user_order_custom_func()
{
	if($_POST['user_order'])
	{
		$list_of = $_POST['list_of_u'];
		$user_order = stripslashes($_POST['user_order']);
		$user_order = json_decode( $user_order );
		$user_order = whizz_dv_plugin_order_object_to_array_users($user_order);
		update_option(get_current_user_id().'_user_order_whizz_'.$list_of,$user_order);
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_save_item_per_page', 'whizz_save_item_per_page_func' );
function whizz_save_item_per_page_func()
{
	if($_POST['item_per_page'] && $_POST['item_per_page'] > 0)
	{
		update_option(get_current_user_id().'_item_per_page',$_POST['item_per_page']);
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_order_by_users', 'whizz_order_by_users_func' );
function whizz_order_by_users_func()
{
	if($_POST['orderby'] && $_POST['orderby'] != "")
	{
		$order = "ASC";
		if($_POST['orderby'] == "descending")
		{
			$order = "DESC";
		}
		$role = "";
		$args = "";
		$page_size = get_option(get_current_user_id().'_item_per_page');
		if($_POST['list_of'] == "all_users")
		{
			if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
			{
				$args = array(
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'number'       		=> $page_size,
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'orderby'      => 'display_name',
					'order'        => $order,
					'number'       => $page_size
				 );
			}
		}
		else
		{
			if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
			{
				$args = array(				
					'role'         		=> $_POST['list_of'],
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'number'       		=> $page_size,
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'role'         => $_POST['list_of'],
					'orderby'      => 'display_name',
					'order'        => $order,
					'number'       => $page_size
				 );
			}
		}
		$user_list_temp = get_users( $args );
		if($_POST['view'] == "list_view")
		{
			$list = "<ul id='list_view_user' class='list-view-class'>";
			ob_start();
			foreach($user_list_temp as $user)
			{
				?>
				<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">
					<input type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />
					<div class="user_avtar"><?php echo get_avatar($user->data->ID); ?></div>
					<div class="user_name">
						<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">
							<?php echo $user->data->display_name; ?>
						</a>
					</div>
					<div class="user_email"><?php echo $user->data->user_email; ?></div>
					<div class="user_description-new">
						<?php echo get_the_author_meta( 'description', $user->data->ID); ?>
					</div>
					<div class="user_delete-user-button">
						<b>
						<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">Edit </a> &nbsp;
                          <?php
							if($user->roles[0] != "administrator")
							{
						?>
						<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&action=deleteu&list_of=<?php echo $_GET['list_of']; ?>&view=<?php echo $_GET['view']; ?>&uid=<?php echo $user->data->ID; ?>">Delete</a> &nbsp;
                        <?php } ?>
                        
					</b>
					</div>
					<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user->data->ID; ?>" />
					<div class="clear"></div>
                </li>
				<?php
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			wp_send_json_success($list);
		}		
		else
		{
			wp_send_json_success('No record found.');
		}
	}
	else
	{
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_order_by_users_my_list', 'whizz_order_by_users_my_list_func' );
function whizz_order_by_users_my_list_func()
{
	if($_POST['orderby'] && $_POST['orderby'] != "")
	{
		$order = "ASC";
		if($_POST['orderby'] == "descending")
		{
			$order = "DESC";
		}
		$role = "";
		$args = "";
		/* retrieve saved users and pass their ids to arguments*/
		$user_id_array = array();
		$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
		foreach($saved_users as $user)
		{
			$user_id_array[] = $user['id'];
		}
		
		$page_size = get_option(get_current_user_id().'_item_per_page');
		
		if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
		{
			$args = array(				
				'include'      		=> $user_id_array,
				'orderby'      		=> 'display_name',
				'order'        		=> $order,
				'number'       		=> $page_size,
				'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
				'search_columns' 	=> array(
										'user_login',
										'user_nicename',
										'user_email',
									),
			 	);
		}
		else
		{
			$args = array(
				'include'      => $user_id_array,
				'orderby'      => 'display_name',
				'order'        => $order,
				'number'       => $page_size
			 );
		}
	
		$user_list_temp = get_users( $args );
		if($_POST['view'] == "list_view")
		{
			$list = "<ul id='list_view_user' class='list-view-class'>";
			ob_start();
			foreach($user_list_temp as $user)
			{
				?>
				<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">
					<input type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />
					<div class="user_avtar"><?php echo get_avatar($user->data->ID); ?></div>
					<div class="user_name">		
						<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">		
							<?php echo $user->data->display_name; ?>		
						</a>		
					</div>		
					<div class="user_email"><?php echo $user->data->user_email; ?></div>		
					<div class="user_description-new">		
						<?php echo get_the_author_meta( 'description', $user->data->ID); ?>		
					</div>		
					<div class="user_delete-user-button">
						<b>
						<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">Edit </a> &nbsp; 
                         <?php
							if($user->roles[0] != "administrator")
							{
						?>
						<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&action=deleteu&list_of=<?php echo $_GET['list_of']; ?>&view=<?php echo $_GET['view']; ?>&uid=<?php echo $user->data->ID; ?>">Delete</a> &nbsp;                          <?php
							}
						?>
						</b>
					</div>
					<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user->data->ID; ?>" />
					<div class="clear"></div>
                </li>
				<?php				
			}
			$list .= ob_get_clean();
			$list .="</ul>";
			wp_send_json_success($list);
		}		
		else
		{
			wp_send_json_success('No record found.');
		}
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_dv_plugin_order_object_to_array_users($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = whizz_dv_plugin_order_object_to_array_users($value);
        }
        return $result;
    }
    return $data;
}
function whizz_check_main_menu_whizz_plugin_users($menu_slug_check)
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
add_action('wp_ajax_whizz_paging_users', 'whizz_paging_users_func', 999 );
function whizz_paging_users_func()
{	
	if($_POST['page_no'] && $_POST['page_no'] > 0 )
	{
		$page_no = $_POST['page_no'];
		$page_size = get_option(get_current_user_id().'_item_per_page');
		$view = $_POST['view'];
		$role = "";		
		$order = "ASC";
		$orderd = $_POST['orderd'];
		$position = $_POST['position'];
		if($_POST['orderby'] == "descending")
		{
			$order = "DESC";
		}
		if($_POST['list_of'] == "all_users")
		{
			$role="";
		}
		else
		{
			$role=$_POST['list_of'];
		}
		$args ="";
		if($role && $role !="")
		{
			if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
			{
				$args = array(				
					'role'         		=> $role,
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'role'         => $role,
					'orderby'      => 'display_name',
					'order'        => $order,
				 );
			}
		}
		else
		{
			if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
			{
				$args = array(				
					'orderby'      		=> 'display_name',
					'order'        		=> $order,
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' 	=> array(
											'user_login',
											'user_nicename',
											'user_email',
										),
				 	);
			}
			else
			{
				$args = array(
					'orderby'      => 'display_name',
					'order'        => $order,
				 );
			}
		}
		$user_role = $_POST['list_of'];
		$ordered_appended_array = whizz_list_user_paged($args, $page_no, $page_size, $orderd, $user_role, $position);
		if($ordered_appended_array)
		{
			if($view == "list_view")
			{
				$list = "<ul id='list_view_user' class='list-view-class'>";									
				ob_start();
				foreach($ordered_appended_array as $user)
				{
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />		
						<div class="user_avtar"><?php echo get_avatar($user->data->ID); ?></div>		
						<div class="user_name">		
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">		
								<?php echo $user->data->display_name; ?>		
							</a>		
						</div>		
						<div class="user_email"><?php echo $user->data->user_email; ?></div>		
						<div class="user_description-new">		
							<?php echo get_the_author_meta( 'description', $user->data->ID); ?>		
						</div>		
						<div class="user_delete-user-button">
							<b>
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">Edit </a> &nbsp; 
                             <?php
								if($user->roles[0] != "administrator")
								{
							?>
							<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&action=deleteu&list_of=<?php echo $_GET['list_of']; ?>&view=<?php echo $_GET['view']; ?>&uid=<?php echo $user->data->ID; ?>">Delete</a> &nbsp; 
                             <?php
								}
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user->data->ID; ?>" />
					<div class="clear"></div></li>
					<?php
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}
		}
	}
	wp_send_json_error();
}
function whizz_list_user_paged($args, $page_no, $page_size, $orderd, $user_role, $position)
{
	$user_list_temp = get_users( $args );
	if($orderd == "yes") /*  currently orderd so no need to get the modified order */
	{
		if($position == "next")
		{
			$ordered_appended_array = array();
			$record_bypass = $page_no * $page_size;		
			$i = 1;
			foreach($user_list_temp as $ult_key => $user)
			{
				if($i > $record_bypass)
				{
					if($i <= $record_bypass + $page_size)
					{
 						$ordered_appended_array[] = $user;
					}
					else
					{
						break;
					}
					$i++;
				}
				else
				{
					$i++;
				}
			}
			return $ordered_appended_array;
		}
		else if($position == "previous")
		{
			if($page_no > 1)
			{
				$page_no = $page_no -2;
				$ordered_appended_array = array();
				$record_bypass = $page_no * $page_size;		
				$i = 1;
				foreach($user_list_temp as $ult_key => $user)
				{
					if($i > $record_bypass)
					{
						if($i <= $record_bypass + $page_size)
						{
							$ordered_appended_array[] = $user;
						}
						else
						{
							break;
						}
						$i++;
					}
					else
					{
						$i++;
					}
				}
				return $ordered_appended_array;
			}
			else
			{
				return false;
			}
		}
	}
	else
	{
		$user_order = get_option(get_current_user_id().'_user_order_whizz_'.$user_role);
		
		$ordered_appended_array = array();
		if(!empty($user_order))
		{
			foreach($user_order as $order_key => $order_value)
			{
				foreach($user_list_temp as $ult_key => $user)
				{
					if($order_key == $user->data->ID."ID")
					{
						$ordered_appended_array[] = $user;
						unset($user_list_temp[$ult_key]);
					}
				}
			}
		}
		if(!empty($user_list_temp))
		{
			foreach($user_list_temp as $user)
			{
				$ordered_appended_array[] = $user;
			}
		}		
		if($position == "next")
		{
			$ordered_array = array();
			$record_bypass = $page_no * $page_size;		
			$i = 1;
			foreach($ordered_appended_array as $ult_key => $user)
			{
				if($i > $record_bypass)
				{
					if($i <= $record_bypass + $page_size)
					{
 						$ordered_array[] = $user;
					}
					else
					{
						break;
					}
					$i++;
				}
				else
				{
					$i++;
				}
			}
			return $ordered_array;
		}
		else if($position == "previous")
		{
			if($page_no > 1)
			{
				$page_no = $page_no -2;
				$ordered_array = array();
				$record_bypass = $page_no * $page_size;		
				$i = 1;
				foreach($ordered_appended_array as $ult_key => $user)
				{
					if($i > $record_bypass)
					{
						if($i <= $record_bypass + $page_size)
						{
							$ordered_array[] = $user;
						}
						else
						{
							break;
						}
						$i++;
					}
					else
					{
						$i++;
					}
				}
				return $ordered_array;
			}
			else
			{
				return false;
			}
		}		
	}
	return false;
}
add_action('wp_ajax_whizz_paging_users_my_list', 'whizz_paging_users_my_list_func', 999 );
function whizz_paging_users_my_list_func()
{	
	if($_POST['page_no'] && $_POST['page_no'] > 0 )
	{
		$page_no = $_POST['page_no'];
		$page_size = get_option(get_current_user_id().'_item_per_page');
		$view = $_POST['view'];
		$role = "";		
		$order = "ASC";
		$orderd = $_POST['orderd'];
		$position = $_POST['position'];
		if($_POST['orderby'] == "descending")
		{
			$order = "DESC";
		}
		
		$user_id_array = array();
		$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
		foreach($saved_users as $user)
		{
			$user_id_array[] = $user['id'];
		}
		
		$args ="";		
		if($_POST['search_set'] =="yes" && $_POST['search_term'] != "")
		{
			$args = array(				
				'include'      		=> $user_id_array,
				'orderby'      		=> 'display_name',
				'order'        		=> $order,
				'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
				'search_columns' 	=> array(
										'user_login',
										'user_nicename',
										'user_email',
									),
			 	);
		}
		else
		{
			$args = array(
				'include'      => $user_id_array,
				'orderby'      => 'display_name',
				'order'        => $order,
			 );
		}
	
		$user_role = $_POST['list_of'];
		$ordered_appended_array = list_user_paged($args, $page_no, $page_size, $orderd, $user_role, $position);
		if($ordered_appended_array)
		{
			if($view == "list_view")
			{
				$list = "<ul id='list_view_user' class='list-view-class'>";
				ob_start();
				foreach($ordered_appended_array as $user)
				{
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />
						<div class="user_avtar"><?php echo get_avatar($user->data->ID); ?></div>
						<div class="user_name">
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">
								<?php echo $user->data->display_name; ?>
							</a>
						</div>
						<div class="user_email"><?php echo $user->data->user_email; ?></div>
						<div class="user_description-new">
							<?php echo get_the_author_meta( 'description', $user->data->ID); ?>
						</div>
						<div class="user_delete-user-button">
							<b>
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">Edit </a> &nbsp; 
                             <?php
								if($user->roles[0] != "administrator")
								{
							?>
							<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&action=deleteu&list_of=<?php echo $_GET['list_of']; ?>&view=<?php echo $_GET['view']; ?>&uid=<?php echo $user->data->ID; ?>">Delete</a> &nbsp;
                             <?php
								}							
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user->data->ID; ?>" />
					<div class="clear"></div></li>
					<?php
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}			
		}
	}
	wp_send_json_error();
}
add_action('wp_ajax_whizz_search_users_admin', 'whizz_search_users_admin_func', 999 );
function whizz_search_users_admin_func()
{
	if(isset($_POST['search_term']))
	{
		$page_size = get_option(get_current_user_id().'_item_per_page');
		global $wpdb;
		$users ="";
		if(isset($_POST['list_of']))
		{
			if($_POST['list_of'] == "all_users")
			{
				$users = new WP_User_Query( array(
					'search'         => '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'       => $page_size
				));
			}
			else if($_POST['list_of'] == "my_list")
			{
				
				$user_id_array = array();
				$saved_users = get_option(get_current_user_id().'_user_order_whizz_my_list');
				foreach($saved_users as $user)
				{
					$user_id_array[] = $user['id'];
				}
				
				$users = new WP_User_Query( array(
					'include'      		=> $user_id_array,
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns' 	=> array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'       		=> $page_size
				));
			}
			else
			{
				$users = new WP_User_Query( array(
					'search'         	=> '*'.esc_attr( $_POST['search_term'] ).'*',
					'search_columns'	=> array(
						'user_login',
						'user_nicename',
						'user_email',
						'user_url',
					),
					'number'			=> $page_size,
					'role'				=> $_POST['list_of']
				));
			}
		}
		else
		{
			$users = new WP_User_Query( array(
				'search'         => '*'.esc_attr( $_POST['search_term'] ).'*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
				'number'       => $page_size
			));
		}		
		$users_found = $users->get_results();		
		if($users_found)
		{
			if($_POST['view'] == "list_view")
			{
				$list = "<ul id='list_view_user' class='list-view-class'>";
				ob_start();
				foreach($users_found as $user)
				{
					?>
					<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">
						<input type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />		
						<div class="user_avtar"><?php echo get_avatar($user->data->ID); ?></div>		
						<div class="user_name">		
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">		
								<?php echo $user->data->display_name; ?>		
							</a>		
						</div>		
						<div class="user_email"><?php echo $user->data->user_email; ?></div>		
						<div class="user_description-new">		
							<?php echo get_the_author_meta( 'description', $user->data->ID); ?>		
						</div>		
						<div class="user_delete-user-button">
							<b>
							<a href="user-edit.php?user_id=<?php echo $user->data->ID; ?>">Edit </a> &nbsp;
                             <?php
								if($user->roles[0] != "administrator")
								{
							?>
							<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&action=deleteu&list_of=<?php echo $_GET['list_of']; ?>&view=<?php echo $_GET['view']; ?>&uid=<?php echo $user->data->ID; ?>">Delete</a> &nbsp; 
                             <?php
								}
							?>
							</b>
						</div>
						<input type="hidden" name="user_id_h" class="user_id_h" value="<?php echo $user->data->ID; ?>" />
						<div class="clear"></div>
					</li>
					<?php				
				} 
				$list .= ob_get_clean();
				$list .="</ul>";
				wp_send_json_success($list);
			}
		}
		else
		{
			wp_send_json_success("No record found.");
		}
		wp_send_json_error();
	}
}
add_action('wp_ajax_whizz_reset_to_default_order', 'whizz_reset_to_default_order_func' );
function whizz_reset_to_default_order_func()
{
	if(isset($_POST['list_of']) && $_POST['list_of'] != "")
	{
		update_option(get_current_user_id().'_user_order_whizz_'.$_POST['list_of'],'');
		wp_send_json_success( 'Success' );
	}
	else
	{
		wp_send_json_error();
	}
}
function whizz_validate_user_activation($user, $password)
{
	if(!is_wp_error( $user ))
	{
		$res = whizz_check_user_authenticattion_status_admin($user->data->ID);
		if($res == "not_exists")
		{
		}
		else if($res == "yes")
		{
			// do nothing
		}
		else
		{
			$user = new WP_Error( 'denied', __("<strong>ERROR</strong>: User deactivated.") );
		}
	}
    return $user;
}
add_filter( 'wp_authenticate_user', 'whizz_validate_user_activation', 10, 3 );