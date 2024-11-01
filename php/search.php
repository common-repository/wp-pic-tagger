<?php
include '../../../../wp-load.php';
if ( file_exists( ABSPATH . 'wp-config.php') ) {
    	require_once( ABSPATH . 'wp-config.php' );
    }
    
global $wpdb;

$table_name = $wpdb->prefix . "pictagger";

$q = strtolower($_GET["q"]);
if (!$q) return;

  global $wpdb;
  $table_name = $wpdb->prefix . "pictagger";
  
  $auth_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE pic_face LIKE '%$q%';");
  
  if ($auth_count) {
  $authors = $wpdb->get_results("SELECT * FROM $table_name WHERE pic_face LIKE '%$q%' GROUP BY pic_face");

	$items = array();
	
	foreach ($authors as $author) {
	$face = stripslashes($author->pic_face);
	echo "$face|$face\n";
	}
	}

?>