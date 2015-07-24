<?php
	function whizz_plugin_list_view()
	{
		$list = "<ul id='list_view_plugin'>";
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_all_plugins');
		$ordered_appended_array = array();
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
				}
			}
		}
		if(!empty($plugin_list_temp))
		{
			foreach($plugin_list_temp as $remaining_key => $remaining_value)
			{
				$ordered_appended_array[$remaining_key] = $remaining_value;
			}
		}
		ob_start();
		foreach($ordered_appended_array as $plugin_key => $plugin_value)
		{
			?>
			<li class="li_inside_ul_list" id="<?php echo $plugin_key; ?>">
				<div class="main_div_inside_li_list">
					<div class="span6 col-md-6">
					<div class="plugin_name_list">
                    <input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key; ?>" />
                     <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
					<?php 
						echo $plugin_value['Name'];
					?>
					</div>
					<ul>
                    <li>
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>						
						<li>
							<?php
								echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
							?>
						</li>
						<li>                        	
							<?php
							if( strstr($plugin_key, 'whizz-plugin') == false)
							{
								echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
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
		return $list;
	}
	function whizz_plugin_list_view_active_plugins()
	{
		$list = "<ul id='list_view_plugin'>";
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_active_plugins');
		$ordered_appended_array = array();
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
				}
			}
		}
		if(!empty($plugin_list_temp))
		{
			foreach($plugin_list_temp as $remaining_key => $remaining_value)
			{
				$ordered_appended_array[$remaining_key] = $remaining_value;
			}
		}
		ob_start();
		foreach($ordered_appended_array as $plugin_key => $plugin_value)
		{
			if ( is_plugin_active( $plugin_key ) )
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
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>							
							<li>
								<?php
									echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
								?>
							</li>
							<li>
								<?php
								if( strstr($plugin_key, 'whizz-plugin') == false)
								{
									echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
								}
								?>
							</li>                            
						</ul>
						</div>
							<div class="span6 col-md-6"	>
						<div class="plugin_desc_list">
						<?php 
							echo $plugin_value['Description'];
						?>
						</div>
						<ul>
                        <li>
						
                        <div class="plugin_version_list">
						Version 
						<?php 
						echo $plugin_value['Version'];
						?>
						</div>
						</li>|<li>
						<div class="plugin_author_name_list">
						<?php 
							echo $plugin_value['AuthorName'];
						?>
						</div>
						</li>|
						<li>
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
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}	
	function whizz_plugin_list_view_inactive_plugins()
	{
		$list = "<ul id='list_view_plugin'>";
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_inactive_plugins');
		$ordered_appended_array = array();
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
				}
			}
		}
		if(!empty($plugin_list_temp))
		{
			foreach($plugin_list_temp as $remaining_key => $remaining_value)
			{
				$ordered_appended_array[$remaining_key] = $remaining_value;
			}
		}
		ob_start();
		foreach($ordered_appended_array as $plugin_key => $plugin_value)
		{
			if ( is_plugin_active( $plugin_key ) )
			{ 
			}
			else
			{
				?>
				<li class="li_inside_ul_list" id="<?php echo $plugin_key; ?>">
					<div class="main_div_inside_li_list">
						<div class="span6 col-md-6">
						<div class="plugin_name_list">
                        <input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key; ?>" />
                         <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
						<?php 
							echo $plugin_value['Name'];
						?>
						</div>
						<ul>		
                        <li>
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>					
							<li>
								<?php
									echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
								?>
							</li>
							<li>
								<?php
								if( strstr($plugin_key, 'whizz-plugin') == false)
								{
									echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
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
						<div class="plugin_version_list">
						Version 
						<?php 
							echo $plugin_value['Version'];
						?>
						</div>
						</li>|
						<li>
						<div class="plugin_author_name_list">
						<?php 
							echo $plugin_value['AuthorName'];
						?>
						</div>
						</li>|
						<li>
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
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
	function whizz_plugin_list_view_active_update_available_plugins()
	{
		$current = get_site_transient( 'update_plugins' );
		$list = "<ul id='list_view_plugin'>";
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_active_update_available');		
		$ordered_appended_array = array();		
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
				}
			}
		}
		if(!empty($plugin_list_temp) )
		{
			foreach($plugin_list_temp as $remaining_key => $remaining_value)
			{
				/* To check, is update available then add else don't*/
				foreach($current->response as $upkey => $upval)
				{
					if($upkey == $remaining_key)
					{
						$ordered_appended_array[$remaining_key] = $remaining_value;
					}
				}
			}
		}
		ob_start();
		foreach($ordered_appended_array as $plugin_key => $plugin_value)
		{
			if ( is_plugin_active( $plugin_key ) )
			{ 
				?>
				<li class="li_inside_ul_list" id="<?php echo $plugin_key; ?>">
					<div class="main_div_inside_li_list">
						<div class="span6 col-md-6">
						<div class="plugin_name_list">
                        <input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key; ?>" />
                         <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
						<?php 
							echo $plugin_value['Name'];
						?>
						</div>
						<ul>
                        <li>
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>					
							<li>
								<?php
									echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
								?>
							</li>
							<li>
							<?php
								if( strstr($plugin_key, 'whizz-plugin') == false)
								{
									echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
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
						<div class="plugin_version_list">
						Version 
						<?php 
							echo $plugin_value['Version'];
						?>
						</div>
						</li>|
						<li>
                        <div class="plugin_author_name_list">
						<?php 
							echo $plugin_value['AuthorName'];
						?>
						</div>
						</li>|
						<li>
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
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}	
	function whizz_plugin_list_view_inactive_update_available_plugins()
	{
		$current = get_site_transient( 'update_plugins' );
		$list = "<ul id='list_view_plugin'>";
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_inactive_update_available');		
		$ordered_appended_array = array();
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
				}
			}
		}
		if(!empty($plugin_list_temp) )
		{
			foreach($plugin_list_temp as $remaining_key => $remaining_value)
			{
				/* To check, is update available then add else don't*/
				foreach($current->response as $upkey => $upval)
				{
					if($upkey == $remaining_key)
					{
						$ordered_appended_array[$remaining_key] = $remaining_value;
					}
				}
			}
		}
		ob_start();
		foreach($ordered_appended_array as $plugin_key => $plugin_value)
		{
			if ( is_plugin_active( $plugin_key ) )
			{ 
			}
			else
			{
				?>
				<li class="li_inside_ul_list" id="<?php echo $plugin_key; ?>">
					<div class="main_div_inside_li_list">
						<div class="span6 col-md-6">
						<div class="plugin_name_list">
                        <input type="checkbox" name="selected_checkboxes[]" value="<?php echo $plugin_key; ?>" />
                         <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
						<?php 
							echo $plugin_value['Name'];
						?>
                    </div>
						<ul>
                        <li>
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>               	
							<li>
								<?php
									echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
								?>                    	
							</li>
							<li>
								<?php
								if( strstr($plugin_key, 'whizz-plugin') == false)
								{
									echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
								}
								?> 
							</li>
                             <?php 
					   			//echo whizz_save_remove_my_list_func($plugin_key);
					   		?>
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
						<div class="plugin_version_list">
						Version 
						<?php 
							echo $plugin_value['Version'];
						?>
						</div>
						</li>|
						<li>
						<div class="plugin_author_name_list">
						<?php 
							echo $plugin_value['AuthorName'];
						?>
					</div>
					</li>|
						<li>
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
		}
		$list .= ob_get_clean();
		$list .="</ul>";
		return $list;
	}
	function whizz_plugin_list_view_my_list()
	{
		$plugin_list_temp = get_plugins();
		$plugin_order = get_option(get_current_user_id().'_plugin_order_whizz_my_preferences');
		$ordered_appended_array = array();
		if(!empty($plugin_order))
		{
			foreach($plugin_order as $order_key => $order_value)
			{
				if(array_key_exists($order_key,$plugin_list_temp))
				{
					$ordered_appended_array[$order_key] = $order_value;
					unset($plugin_list_temp[$order_key]);
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
                     <input type="hidden" name="selected_<?php echo $plugin_key; ?>" value="<?php echo trim($plugin_value['Name']); ?>" />
					<?php 
						echo $plugin_value['Name'];
					?>
					</div>
					<ul>
                    <li>
							<div class="plugin_activate_list">
								<?php 
                                if ( is_plugin_active( $plugin_key ) ) 
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deactivatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Deactivate </a>";
                                }
                                else
                                {
                                    echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=activatep&ppath=$plugin_key&view=list_view&list_of=".$_GET['list_of']."'> Activate </a>";
                                }
                                ?>
							</div>
						</li>				
						<li>
							<?php
								echo "<a href='plugin-editor.php?file=$plugin_key'>Edit</a>";
							?>
						</li>
						<li>
							<?php
							if( strstr($plugin_key, 'whizz-plugin') == false)
							{
								echo "<a href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&action=deletep&ppath=$plugin_key&plugin=".$plugin_value['Name']."&view=list_view&list_of=".$_GET['list_of']."'> Delete </a>";
							}
							?> 
						</li>
                       	<?php
					   		//echo whizz_save_remove_my_list_func($plugin_key);
					   	?>
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
		return $list;
	}	
?>