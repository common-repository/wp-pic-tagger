<?php
    
    include 'notes.class.php';
    include '../../../../wp-load.php';
    if ( file_exists( ABSPATH . 'wp-config.php') ) {
    	require_once( ABSPATH . 'wp-config.php' );
    } else {
    	$error = '1';
    }
    
   global $wpdb;
   global $wp_query;
   
   $current_user = wp_get_current_user();
   
   if ($current_user->user_level != 10) {
    	$error = '1';
   }
    

    
    if (isset($_POST['image']) && !empty($_POST['image']) && !empty($_GET['type']))
	$oNote = new note((string) strip_tags($_POST['image']), strip_tags($_GET['type']), strip_tags($_GET['postid']));
    
    if (isset($_POST['id']) && !empty($_POST['id']))
	$id = (int) strip_tags($_POST['id']);
    
    if (isset($_POST['position']) && !empty($_POST['position']))
	$position = $_POST['position'];
    
    if (isset($_POST['note']) && !empty($_POST['note']))
	$note = (string) strip_tags($_POST['note']);
    
    if (isset($_POST['author']) && !empty($_POST['author']))
	$link = (string) strip_tags($_POST['author']);

    if (isset($_POST['link']) && !empty($_POST['link']))
	$link = (string) strip_tags($_POST['link']);
    
    if (isset($_POST['author']) && !empty($_POST['author']))
	$author = (string) strip_tags($_POST['author']);
    
    if (isset($_POST['get']) && !empty($_POST['get']))
	echo json_encode($oNote->getNotes());
    
    if (!$error) {
    	
    if (isset($_POST['add']) && !empty($_POST['add']))
	echo json_encode($oNote->addNote($position, $note, $author, $link));
    
    if (isset($_POST['delete']) && !empty($_POST['delete']))
	echo json_encode($oNote->deleteNote($id));
    
    if (isset($_POST['edit']) && !empty($_POST['edit']))
	echo json_encode($oNote->editNote($id, $position, $note, $author, $link));

      }
?>