<h1>Delete User</h1>
<p>Are you sure you want to delete this user?</p>
<?php
echo "<a class='button' href='".WHIZZ_USERS_LIST_PLUGIN_URL."&uid=".$_GET['uid']."&view=".$_GET['view']."&deletec=yes&list_of=".$_GET['list_of']."'>Yes Delete this user</a> &nbsp; &nbsp;&nbsp;";
echo "<a class='button' href='".WHIZZ_USERS_LIST_PLUGIN_URL."&view=".$_GET['view']."&list_of=".$_GET['list_of']."'>No Return me to the users list</a>";
?>