<?php
	$delete_flag='none';
	$error = "";
	if(isset($_POST['bulk_action_submit']) && isset($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "3")
	{
		if(count($_POST['selected_checkboxes']) >0 )
		{
			?>
			 <form class="deletation-form-class" name="frm_delete_final" method="post" action="">
				<h1>Delete Plugins</h1>
                <p>You are about to remove the following plugin:</p>
                <?php
				foreach($_POST['selected_checkboxes'] as $singlechk)
				{
echo "<div class='name-of-plugin'><ul><li>".$_POST['selected_'.str_replace('.','_',$singlechk)]."</li></ul></div>";
				}
				?>
				<p>Are you sure you want to delete these/this Plugin/s?</p>
				<input class="option-for-select" type="submit" name="final_delete_bulk" value="Yes Delete Plugin/s" />
				<input class="option-for-select" type="submit" name="return_back" value="No Return me to the plugins list" />
				<input type="hidden" name="plugin_ids" value="<?php echo implode(",", $_POST['selected_checkboxes']); ?>" />
			</form>
			<?php
		}
		else
		{
			?>
			 <form name="frm_delete_final" method="post" action="">
				<h1>Delete Plugins</h1>
				<p>No any plugin selected.</p>
				<input type="submit" name="return_back" id="return_back" value="Back" />
			</form>
			<?php
		}
	}
	else
	{
		if(isset($_GET['deletec']) && $_GET['deletec'] == "yes")
		{
			if( strstr($_GET['ppath'], 'whizz-plugin') == false)
			{
			  $result = delete_plugins( array($_GET['ppath']));
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
		
		if($_GET['action'] == "activatep" && isset($_GET['ppath']))
		{
			$result = activate_plugin( $_GET['ppath'] );
			if ( is_wp_error( $result ) ) 
			{
				echo "Activation Failed.";
			}
		}
	
		if($_GET['action'] == "deactivatep" && isset($_GET['ppath']))
		{
			$result = deactivate_plugins( $_GET['ppath'] );
			if ( is_wp_error( $result ) ) 
			{
				echo "Deactivation Failed.";
			}
		}
		
		if(isset($_POST['bulk_action_submit']))
		{
			if(isset($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "1")
			{
				if(count($_POST['selected_checkboxes']) >0 )
				{
					$chk_ids = $_POST['selected_checkboxes'];
					foreach($chk_ids as $id)
					{
						$result = activate_plugin( $id );	
					}
					if ( is_wp_error( $result ) ) 
					{
						$error = "Activation Failed.";
					}
					else
					{
						$error = "Activated Successfully.";
					}
				} 
			}
			else if(isset($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "2")
			{
				if(count($_POST['selected_checkboxes']) >0 )
				{
					$chk_ids = $_POST['selected_checkboxes'];
					foreach($chk_ids as $id)
					{
						$result = deactivate_plugins( $id );
					}
					if ( is_wp_error( $result ) ) 
					{
						$error = "Deactivation Failed.";
					}
					else
					{
						$error = "Deactivated Successfully.";
					}
				}
			}
			else if(isset($_POST['bulk_action_ddl']) && $_POST['bulk_action_ddl'] == "1")
			{
				$error = "Please select action.";
			}
			else
			{
				$error = "Please select minimum 1 plugin to proceed.";
			}
		}
		if(isset($_POST['final_delete_bulk']))
		{
			$plugin_ids = explode(",", $_POST['plugin_ids']);
			$ids = array();
			foreach($plugin_ids as $plugin_id)
			{				
				if( strstr($plugin_id, 'whizz-plugin') )
				{
				}
				else
				{
					$ids[] = $plugin_id;	
				}
			}
			$result = delete_plugins($ids);
			if ( is_wp_error( $result ) ) 
			{
				$error = "Plugin/s deletion error! Please try again.";
			}
			else
			{
				$error = "Plugin/s deleted successfully.";
			}
		}
		
		if(isset($_POST['reset_order_current']))
		{
			$selected_filter = $_GET['list_of'];
			if(!empty($selected_filter))
			{
				update_option(get_current_user_id().'_plugin_order_whizz_'.$selected_filter,"");
			}
			else
			{
				update_option(get_current_user_id().'_plugin_order_whizz_all_plugins',"");
			}
		}
		
		if(isset($_POST['reset_order_all']))
		{
			update_option(get_current_user_id().'_plugin_order_whizz_active_plugins',"");
			update_option(get_current_user_id().'_plugin_order_whizz_inactive_plugins',"");
			update_option(get_current_user_id().'_plugin_order_whizz_active_update_available',"");
			update_option(get_current_user_id().'_plugin_order_whizz_inactive_update_available',"");
			update_option(get_current_user_id().'_plugin_order_whizz_my_preferences',"");
			update_option(get_current_user_id().'_plugin_order_whizz_all_plugins',"");
		}
	
		include_once('functions/functions.php');
		$total_plugins = 0;
		$active_plugins = 0;
		$active_update_available = 0;
		$inactive_update_available = 0;
		$my_list = 0;
		$plugin_list_count = get_plugins();
		foreach($plugin_list_count as $plugin_key => $plugin_value)
		{
			$total_plugins++;	
			$current = get_site_transient( 'update_plugins' );
			if ( is_plugin_active( $plugin_key ) )
			{
				$active_plugins++;
				foreach($current->response as $upkey => $upval)
				{
					if($upkey == $plugin_key)
					{
						$active_update_available++;
					}
				}
			}
			else
			{
				foreach($current->response as $upkey => $upval)
				{
					if($upkey == $plugin_key)
					{
						$inactive_update_available++;
					}
				}
			}
		}
		
		$base_url="";
		$base_url = WHIZZ_PLUGINS_LIST_PLUGIN_URL."&view=list_view";		
		if($_GET['action'] == "deletep")
		{	
			include_once('delete-plugin.php');		
		}
		else
		{
			?>
			<script type="text/javascript">
				(function($){
					$(document).ready(function(e) {
						$("#list_view_underline").addClass('head_text_li');
						$("#grid_view_underline").removeClass('head_text_li');
					});
				})(jQuery)
		</script>
		<form name="plugin_custom_form" action="" method="post">
<div class="color-bar-plugin">
			<h1 class="plugin-new-title">WHIZZ Plugins</h1>
			<div class="addnew-button-new">
				<a href="plugin-install.php" class="button"> Add New </a>
			</div>
            <div class="clear"></div>
            </div>
           	<div style="clear:both;"></div>
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
				if($error!="") 
				{
					echo '<div class="errorshow-container"><p id="errorshow" class="errorshow-class-new">'.$error.'</p></div>';
				}
			?>                    
			<div class="bulk-action-container" style="float:left;">
            	<input type="checkbox" name="select_all" id="select_all" />
				<select id="bulk_action_ddl" name="bulk_action_ddl">
					<option value="0">Bulk Actions</option>               
					<option value="1">Activate</option>
					<option value="2">Deactivate</option>
					<option value="3">Delete</option>
				</select>
				<input type="submit" name="bulk_action_submit"  id="bulk_action_submit" value="Apply" class="button" />
                 <input type="submit" class="button" name="reset_order_current" value="Reset Order (Current)" />
                        <input type="submit" class="button" name="reset_order_all" value="Reset Order (All)" />
			</div>
			<div style="float:right;">
				<input type="text" name="search_term_plugin" id="search_term_plugin" maxlength="100" size="20" />
				<input type="button" name="search_submit"  id="search_submit" value="Search Plugin" class="button" />
			</div>
			<div class="main_div_head_text">
			  <ul class="heading_filters">
				<?php
					if(isset($_GET['list_of']) && $_GET['list_of'] != "")
					{
						if($_GET['list_of'] == "all_plugins")
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
				<a href="<?php echo $base_url; ?>&list_of=all_plugins"> All Plugins (<b><?php echo $total_plugins; ?></b>) </a>
				</li>
				<?php
					  if(isset($_GET['list_of']) && $_GET['list_of'] == "active_plugins")
					  {
						  echo "<li id='heading_filters_all_active' class='head_text_li'>";
					  }
					  else
					  {
						  echo "<li id='heading_filters_all_active'>";
					  }
				?>
				<a href="<?php echo $base_url; ?>&list_of=active_plugins"> Active Plugins (<b><?php echo $active_plugins; ?></b>) </a>            
				</li>            
				<?php
					  if(isset($_GET['list_of']) && $_GET['list_of'] == "inactive_plugins")
					  {
						  echo "<li id='heading_filters_all_inactive' class='head_text_li'>";
					  }
					  else
					  {
						  echo "<li id='heading_filters_all_inactive'>";
					  }
				?>
					<a href="<?php echo $base_url; ?>&list_of=inactive_plugins"> Inactive Plugins (<b><?php echo $total_plugins - $active_plugins; ?></b>) </a>
					</li>
					<?php
						if(isset($_GET['list_of']) && $_GET['list_of'] == "active_update_available")
						{
							echo "<li id='heading_filters_active_update_available' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_active_update_available'>";
						}
					?>
					<a href="<?php echo $base_url; ?>&list_of=active_update_available"> Active & Updates Available (<b><?php echo $active_update_available; ?></b>) </a>
					</li>
					<?php
						if(isset($_GET['list_of']) && $_GET['list_of'] == "inactive_update_available")
						{
							echo "<li id='heading_filters_inactive_update_available' class='head_text_li'>";
						}
						else
						{
							echo "<li id='heading_filters_inactive_update_available'>";
						}
					?>
					<a href="<?php echo $base_url; ?>&list_of=inactive_update_available"> Inactive & Require Update (<b><?php echo $inactive_update_available; ?></b>) </a>
					</li>
				  </ul>
				  
				  <ul class="heading_text">
					<li id="list_view_underline">
						<a href="<?php echo WHIZZ_PLUGINS_LIST_PLUGIN_URL; ?>&view=list_view&list_of=<?php echo $_GET['list_of']; ?>"> List View </a>
					</li>					
				  </ul>
				</div>
				<div id="ajax_loader"> 
                	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/ajax-loader.gif" />
                </div>
				<table border="0" cellpadding="5" width="100%">
					<tr>
					<td id="plugin_list_td">
					<?php
						  if($_GET['list_of'] && $_GET['list_of'] == "active_plugins")
						  {
							  echo whizz_plugin_list_view_active_plugins();
						  }
						  else if($_GET['list_of'] && $_GET['list_of'] == "inactive_plugins")
						  {
							  echo whizz_plugin_list_view_inactive_plugins();
						  }
						  else if($_GET['list_of'] && $_GET['list_of'] == "active_update_available")
						  {
							  echo whizz_plugin_list_view_active_update_available_plugins();
						  }
						  else if($_GET['list_of'] && $_GET['list_of'] == "inactive_update_available")
						  {
							  echo whizz_plugin_list_view_inactive_update_available_plugins();
						  }						  
						  else
						  {
							  echo whizz_plugin_list_view();
						  }					  
					?>
					</td>
					</tr>
				</table>
			</form>       
		<?php
		}
	}
?>