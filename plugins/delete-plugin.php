<h1>Delete Plugin</h1>


<p>Are you sure you want to delete this plugin?</p>


<strong><?php echo $_GET['plugin']; ?></strong>


<br /><br />


<?php


echo "<a class='button' href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&ppath=".$_GET['ppath']."&view=".$_GET['view']."&deletec=yes&list_of=".$_GET['list_of']."'>Yes Delete the plugin</a> &nbsp; &nbsp;&nbsp;";


echo "<a class='button' href='".WHIZZ_PLUGINS_LIST_PLUGIN_URL."&view=".$_GET['view']."&list_of=".$_GET['list_of']."'>No Return me to the plugin list</a>";


?>