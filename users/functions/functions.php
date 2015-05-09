<?php

function user_role_count_assoc_array()

{

	$user_list = get_users( $args );

	$roles_and_count['administrator']= 0;

	foreach($user_list as $user)

	{

		if(array_key_exists($user->roles[0],$roles_and_count))

		{

			$roles_and_count[$user->roles[0]] = $roles_and_count[$user->roles[0]] + 1;

		}

		else

		{

			$roles_and_count[$user->roles[0]] = 1;

		}

	}

	return $roles_and_count;

}

function user_list_view_all()

{

	$list = "<ul id='list_view_user' class='list-view-class'>";

	$user_list_temp = get_users();	

	$user_order = get_option(get_current_user_id().'_user_order_whizz_all_users');

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

	$page_size = get_option(get_current_user_id().'_item_per_page');

	if(empty($page_size))

	{

		$page_size = 5;

	}

	ob_start();

	$i=1;

	foreach($ordered_appended_array as $user)

	{

		if($i > $page_size)

		{

			break;

		}

		else

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

			$i++;

		}

	}

	$list .= ob_get_clean();

	$list .="</ul>";

	return $list;

}

function user_grid_view_all()

{

	$list = "<ul id='list_view_user' class='grid-view-class'>";

	$user_list_temp = get_users();	

	$user_order = get_option(get_current_user_id().'_user_order_whizz_all_users');

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

	$page_size = get_option(get_current_user_id().'_item_per_page');

	if(empty($page_size))

	{

		$page_size = 5;

	}

	ob_start();

	$i=1;

	foreach($ordered_appended_array as $user)

	{

		if($i > $page_size)

		{

			break;

		}

		else

		{

			?>

			<li class="li_inside_ul_list" id="user<?php echo $user->data->ID; ?>">	

			<input class="list-view-check-button-new" type="checkbox" name="chk_user[]" value="<?php echo $user->data->ID; ?>" />

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

			</li>

			<?php

			$i++;

		}

	}

	$list .= ob_get_clean();

	$list .="</ul>";

	return $list;

}

function user_grid_view_role_wise($user_role)

{

	$list = "<ul id='list_view_user' class='grid-view-class'>";

	$args = array(

		'role'         => $user_role,

	 );

	$user_list_temp = get_users( $args );	

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

	$page_size = get_option(get_current_user_id().'_item_per_page');

	if(empty($page_size))

	{

		$page_size = 5;

	}

	ob_start();

	$i=1;

	foreach($ordered_appended_array as $user)

	{

		if($i > $page_size)

		{

			break;

		}

		else

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

		<div class="clear"></div>	</li>

			<?php

			$i++;

		}

	}

	$list .= ob_get_clean();

	$list .="</ul>";

	return $list;

}

function user_list_view_role_wise($user_role)

{

	$list = "<ul id='list_view_user' class='list-view-class'>";

	$args = array(

		'role'         => $user_role,

	 );

	$user_list_temp = get_users( $args );

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

	$page_size = get_option(get_current_user_id().'_item_per_page');

	if(empty($page_size))

	{

		$page_size = 5;

	}

	ob_start();

	$i=1;

	foreach($ordered_appended_array as $user)

	{

		if($i > $page_size)

		{

			break;

		}

		else

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

			<div class="clear"></div>  </li>

			<?php

			$i++;

		}

	}

	$list .= ob_get_clean();

	$list .="</ul>";

	return $list;

}

function whizz_check_user_authenticattion_status_admin($user_id)

{

	$status = get_user_meta($user_id, 'user_authenticattion_status', true); 

	if(empty($status))

	{

		return 'not_exists';

	}

	else

	{

		return $status;

	}

}

?>