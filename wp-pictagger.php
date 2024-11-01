<?php
/*
Plugin Name: WP Pic Tagger
Plugin URI: http://www.alleba.com/blog/
Description: Tag and annotate pictures and images on your Wordpress blog.
Version: 0.1
Author: Andrew dela Serna
Author URI: http://www.alleba.com/blog/
License: GPL

Copyright 2010 Andrew dela Serna (email andrew@alleba.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

INSTALLATION
------------
1. Unzip the archive 'wp-pic-tagger.zip' to a local folder on your computer.
2. Upload the folder 'wp-pic-tagger' and its contents to your blog's plugin folder (root/wp-content/plugins) using FTP.
3. Login to your Wordpress admin panel and browse to the Plugins section.
4. Activate the WP Pic Tagger plugin.

*/

add_action('init', 'wpp_install');

$table_name = $wpdb->prefix . "pictagger";

function wpp_install () {
   global $wpdb;
   global $table_name;
   
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
  `pic_ID` int(11) NOT NULL auto_increment,
  `pic_img_ID` tinyint(3) NOT NULL default '0',
  `pic_md5` varchar(100) NOT NULL default '',
  `pic_guid` varchar(100) NOT NULL default '',
  `pic_author_ID` tinyint(3) NOT NULL default '0',
  `pic_post_ID` bigint(20) NOT NULL default '0',
  `pic_date` date NOT NULL default '0000-00-00',
  `pic_left` varchar(25) NOT NULL,
  `pic_top` varchar(25) NOT NULL,
  `pic_width` varchar(25) NOT NULL,
  `pic_height` varchar(25) NOT NULL,
  `pic_tag` varchar(255) default NULL,
  `pic_link` varchar(100) NOT NULL default '',
  `pic_face` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pic_ID`)
    )
	;";
	
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      dbDelta($sql);
    }
  }
  
function wp_pictagger_init() {
	$url = get_bloginfo('wpurl')."/wp-content/plugins/wp-pic-tagger";
?>
<link rel="stylesheet" href="<?php echo $url; ?>/css/style.css" type="text/css" media="all" />

<!--[if lte IE 8]>
<link rel="stylesheet" href="<?php echo $url; ?>/css/lteIE8.css" type="text/css" media="all" />
<![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo $url; ?>/js/jquery-ui-1.8.4.custom.min.js"></script> 
<script type="text/javascript" src="<?php echo $url; ?>/js/jquery-notes_1.0.8.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/js/jquery.autocomplete.js"></script>

<?php
global $current_user; 
global $wp_query;


$post_id = (is_single()) ? $wp_query->post->ID : "0";

get_currentuserinfo();

if (($current_user->user_level == 10 ) && (is_single())) {
  $allowadd = "allowAdd: true,";
  $allowedit = "allowEdit: true,";
  $allowdelete = "allowDelete: true,";
  $allowhide = "allowHide: true,";
  $allowreload = "allowReload: true,";
} else { 
  $allowadd = "allowAdd: false,";
  $allowedit = "allowEdit: false,";
  $allowdelete = "allowDelete: false,";
  $allowhide = "allowHide: false,";
  $allowreload = "allowReload: false,";
}


$jqo .= "
  $allowadd
  $allowedit
	$allowdelete
	$allowhide
	$allowreload
	minWidth: 50,
	minHeight: 50,
	operator: '$url/php/notes.php?type=o&postid=$post_id'
      ";

$jqp .= "
  $allowadd
  $allowedit
	$allowdelete
	$allowhide
	$allowreload
	allowNote: false,
	minWidth: 50,
	minHeight: 50,
	operator: '$url/php/notes.php?type=p&postid=$post_id'
      ";
  
  
?>

<script type="text/javascript"> 
var $j = jQuery.noConflict();
jQuery(document).ready(function($j) {
			$j("img").each(function() {
				var cname = $j(this).attr("class")
				if(cname.substring(0,15) == "wp-tag-objects-") {
					$j('.' + cname).jQueryNotes({
        	allowLink: false,
	        allowAuthor: false,
        	<?php echo $jqo; ?>
          });
				} else if(cname.substring(0,14) == "wp-tag-people-") {
					$j('.' + cname).jQueryNotes({
					<?php echo $jqp; ?>
					});
				}
			});
		});
  
<?php if (($current_user->user_level == 10 ) && (is_single())) { ?>
	
function shiftAuthor() {
	
 	var $j = jQuery.noConflict();
 	
 	$j('.acResults').remove();
 	
  $j("input#query").autocomplete({
  							minChars: '2',
                url: '<?php echo $url; ?>/php/search.php',
                sortFunction: function(a, b) {
                        a = String(a.data[0]).toLowerCase();
                        b = String(b.data[0]).toLowerCase();
                        if (a > b) {
                                return 1;
                        }
                        if (a < b) {
                                return -1;
                        }
                        return 0;
                },
                showValue: function(value, data) {
                        return '<span style="color:red">' + value + '</span>';
                }
        });
}
<?php } ?>
</script>
	
<?php } 

add_action('wp_head', 'wp_pictagger_init');

function wp_pictagger_add_quicktag() {
	if (strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') || strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'page.php')) {
?>
<script type="text/javascript">//<![CDATA[
	var toolbar = document.getElementById("ed_toolbar");
<?php
	edit_insert_button("tag people", "wp_people_handler", "Tag People");
	edit_insert_button("tag objects", "wp_objects_handler", "Tag Objects");
?>
	var state_my_button = true;


function wp_people_handler() {
	if (state_my_button) {
		var myCaption = prompt('Enter a unique identifier for this image (ex. j605 or party01)');
		if (myCaption) {
			myValue = ' class="wp-tag-people-'+myCaption+'" ';
			edInsertContent(edCanvas, myValue); 
		} else {
			myValue = ' class="wp-tag-people-"';
			edInsertContent(edCanvas, myValue);
		}
	}
}

function wp_objects_handler() {
	if (state_my_button) {
		var myCaption = prompt('Enter a unique identifier for this image (ex. j605 or party01)');
		if (myCaption) {
			myValue = ' class="wp-tag-objects-'+myCaption+'" ';
			edInsertContent(edCanvas, myValue); 
		} else {
			myValue = ' class="wp-tag-objects-"';
			edInsertContent(edCanvas, myValue);
		}
	}
}

//]]></script>

<?php } }

if (!function_exists('edit_insert_button')) {
	//edit_insert_button: Inserts a button into the editor
	function edit_insert_button($caption, $js_onclick, $title = '')	{
	?>
	if (toolbar) {
		var theButton = document.createElement('input');
		theButton.type = 'button';
		theButton.value = '<?php echo $caption; ?>';
		theButton.onclick = <?php echo $js_onclick; ?>;
		theButton.className = 'ed_button';
		theButton.title = "<?php echo $title; ?>";
		theButton.id = "<?php echo "ed_{$caption}"; ?>";
		toolbar.appendChild(theButton);
	}
	
<?php } }

add_filter('admin_footer', 'wp_pictagger_add_quicktag');

?>
