<?php
function nflc_menu()
{
    global $wpdb;
    include 'nflc_admin.php';
}
 
function nflc_admin_actions()
{
	//$icon_path = get_option('siteurl').'/wp-content/plugins/NF-shortcodes/images';
    add_menu_page("NF Livecounter", "NF Livecounter", 2,
"NF-Livecounter", "nflc_menu"/*, $icon_path.'/menu_icon.png'*/);
}
 
add_action('admin_menu', 'nflc_admin_actions');
?>