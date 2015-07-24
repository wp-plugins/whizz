<?php
	if(isset($_POST['bulk_action_submit']) && $_POST['bulk_action'] > 0)
	{
		?>
        <form name="frm_delete_final" method="post" action="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&view=<?php echo $_GET['view']; ?>&deletec=yes&list_of=<?php echo $_GET['list_of']; ?>">
            <h1>Delete Users</h1>
            <p>Are you sure you want to delete these/this user?</p>
            <input type="submit" name="final_delete_bulk" id="final_delete_bulk" value="Yes Delete User/s" />
            <input type="submit" name="return_back" id="return_back" value="No Return me to the users list" />
            <input type="hidden" name="user_ids" value="<?php echo implode(",", $_POST['chk_user']); ?>" />
        </form>
        <?php
	}
	else
	{
		$delete_flag='none';
		if(isset($_GET['deletec']) && $_GET['deletec'] == "yes")
		{
			if($_POST['final_delete_bulk'] && !empty($_POST['user_ids']))
			{
				$user_ids = explode(",", $_POST['user_ids']);
				foreach($user_ids as $user)
				{
					$user_info = get_userdata( $user );
					if($user_info)
					{
						if($user_info->roles[0] != "administrator")
						{
							wp_delete_user( $user);
						}						
					}
				}
				$delete_flag = true;
			}
			else
			{	
				if(isset($_GET['uid']))
				{
					$user_info = get_userdata($_GET['uid']);
					if($user_info)
					{
						if($user_info->roles[0] != "administrator")
						{
							$result = wp_delete_user( $_GET['uid'] );
							if ( is_wp_error( $result ) ) 
							{
								$delete_flag = false;
							}
							else
							{
								$delete_flag = true;
							}
						}
					}
				}
			}
		}
		include_once('functions/functions.php');
		$base_url="";
		if(isset($_GET['action']) && $_GET['action'] !="" && isset($_GET['uid']) && $_GET['uid'] > 0 )
		{
			if($_GET['action'] == "Activate")
			{
				update_user_meta($_GET['uid'], 'user_authenticattion_status', 'yes'); 
			}
			else if($_GET['action'] == "Deactivate")
			{
				update_user_meta($_GET['uid'], 'user_authenticattion_status', 'no'); 
			}
		}
		$base_url = WHIZZ_USERS_LIST_PLUGIN_URL."&view=list_view";
		
		if($_GET['action'] == "deleteu")
		{	
			include_once('delete-user.php');		
		}
		else
		{			
			?>
			<script type="text/javascript">		
				(function($){		
					$(document).ready(function(e){
						$("#list_view_underline").addClass('head_text_li');		
						$("#grid_view_underline").removeClass('head_text_li');		
					});
				})(jQuery)		
			</script>		
			<h1 class="plugin-new-user-title">WHIZZ Users</h1>
			<span id="errorshow">
			<?php
				global $delete_flag;
				if(isset($delete_flag) )
				{
					if($delete_flag == true)
					{
						echo "Deleted Successfully.";
					}
					else if($delete_flag == false)
					{
						echo "Deletion Error.";
					}
				}
			?>
			</span>
			<div class="main_div_head_text">
				<ul class="heading_filters">
					<?php		
						$count_all_users = user_role_count_assoc_array();		
						$total_users = 0;		
						foreach($count_all_users as $key => $value)
						{
							$total_users = $total_users + $value;
						}
						if(isset($_GET['list_of']) && $_GET['list_of'] != "")
						{
							if($_GET['list_of'] == "all_users")
							{
								echo "<li id='heading_filters_all' class='head_text_li'>";
							}
							else
							{
								echo "<li id='heading_filters_all'>";
							}
						}
						else
						{
							echo "<li id='heading_filters_all' class='head_text_li'>";
						}
					?>
						<a href="<?php echo $base_url; ?>&list_of=all_users">
							All Users (<b><?php echo $total_users; ?></b>)
						</a>
					</li>
					 <?php
						global $wp_roles;
						$roles_and_count = user_role_count_assoc_array();
						foreach($roles_and_count as $key => $value)
						{
							if($_GET['list_of'] == $key)
							{
								echo "<li id='heading_filters_all' class='head_text_li'>";
							}
							else
							{
								echo "<li id='heading_filters_all'>";
							}
							$t_role_name = $wp_roles->roles[$key]['name'];
							echo "<a href='".$base_url."&list_of=".$key."'>".$t_role_name." (".$value.") </a>";
							echo "</li>";
						}
						if(isset($_GET['list_of']) && $_GET['list_of'] == "my_list")
						{
							echo "<li id='heading_filters_all' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_all'>";
						}
						$count_my_list_users = 0;
						$saved_my_list = get_option(get_current_user_id().'_user_order_whizz_my_list');
						if(!empty($saved_my_list))
						{
							$count_my_list_users =count($saved_my_list);	
						}
					?>
					</li>
				</ul>
				<ul class="heading_text">
					<li id="list_view_underline">
						<a href="<?php echo WHIZZ_USERS_LIST_PLUGIN_URL; ?>&view=list_view&list_of=<?php echo $_GET['list_of']; ?>">
							List View
						</a>
					</li>
				</ul>
			</div>
            <form action="" method="post">
			<table border="0" cellpadding="5" width="100%">
				<tr>
					<td>
						<table border="0" cellpadding="5" width="100%" class="tb-new-enw">                            <tr>
                            	<td id="loader_top">
                                <img src="<?php echo plugin_dir_url(__FILE__); ?>img/ajax-loader.gif"  />
                                </td>
                                <?php /*?><td align="right">
                                	<input type="text" name="search_users" id="search_users" maxlength="50" />
                                    <input type="button" name="search_users_submit" id="search_users_submit" value="Search Users" class="button" />
                                </td><?php */?>
                            </tr>
                            <tr>
                            	<td>
                                <input type="checkbox" name="select_all" id="select_all" />
                                <?php
								$item_per_page = get_option(get_current_user_id().'_item_per_page');
								?>
                                <select name="bulk_action" id="bulk_action">
                                    <option value="0">Bulk Action</option>
                                    <option value="1">Delete</option>
                                </select>
                                <input class="button" type="submit" name="bulk_action_submit" id="bulk_action_submit" value="Apply" />
                                &nbsp; &nbsp; 
<div class="clear-res"></div>
                                <strong class="make-me-small-fontr-zie">Item per page</strong> : 
                                <select name="item_per_page" id="item_per_page">
                                    <option value="5" <?php if($item_per_page == 5){ echo "selected='selected'";} ?>>5</option>
                                    <option value="10" <?php if($item_per_page == 10){ echo "selected='selected'";} ?>>10</option>
                                    <option value="20" <?php if($item_per_page == 20){ echo "selected='selected'";} ?>>20</option>
                                    <option value="30" <?php if($item_per_page == 30){ echo "selected='selected'";} ?>>30</option>
                                    <option value="50" <?php if($item_per_page == 50){ echo "selected='selected'";} ?>>50</option>
                                </select>
                                	<input class="button" type="button" name="reset_to_default_order" id="reset_to_default_order" value="Reset to default order" />
                                </td>
                                <td class="new-new-new-one-two" align="right">
                                	<input type="text" name="search_users" id="search_users" maxlength="50" />
                                    <input type="button" name="search_users_submit" id="search_users_submit" value="Search Users" class="button" />
                                </td>
                                <td class="atoz-cls" align="right">
                                	<span id="ascending_order" style="cursor:pointer;" title="click to sort in ascending order"> <strong>A - Z</strong> </span>
                                    <span id="descending_order" style="cursor:pointer; display:none;" title="click to sort in descending order"> <strong>Z - A</strong> </span>
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
				<tr>
					<td id="users_list_admin">
				   <?php
						if($_GET['list_of'] && $_GET['list_of'] == "all_users")
						{
							echo user_list_view_all();
						}
						if($_GET['list_of'] && $_GET['list_of'] == "my_list")
						{
							echo user_list_view_my_list();
						}
						else if($_GET['list_of'])
						{
							echo user_list_view_role_wise($_GET['list_of']);
						}
						else
						{
							echo user_list_view_all();
						}					
				   ?>
					</td>
				</tr>
                <tr>
                	<td>
                    	<span style="cursor:pointer;" id="previous_links"><<< Previous </span> &nbsp; &nbsp; &nbsp;
                        <span style="cursor:pointer;" id="next_links">Next >>></span>
                    </td>
                </tr>
                <tr>
                	<td id="no_more_records">
                    </td>
                </tr>
                <tr>
                	<td id="paging_loader">
                    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/ajax-loader.gif"  />
                    </td>
                </tr>
			</table>
            </form>
			<?php
		}
	}
?>