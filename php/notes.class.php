<?php
include '../../../../wp-load.php';
    if ( file_exists( ABSPATH . 'wp-config.php') ) {
    	require_once( ABSPATH . 'wp-config.php' );
    }
   
    class note {
    
    
    
	  
    
	/**
	 * path to the notes of the image
	 * @var string
	 * @access private
	 */	
	private $path;
	
	/**
	 * @description
	 * class constructor
	 *
	 * @param required	: string $folder // folder for notes
	 * @param required	: string $prefix // prefix
	 * @param required	: string $image // image
	 * @param required	: string $extension // extension for notes file
	 *
	 * @return		: none
	 *
	 * @access		: public
	 */
	public function __construct($image, $type, $postid) {
	    
	    $this->picid = md5($image);
	    $this->image = $image;
	    $this->type = $type;
	    $this->postid = $postid;
	    
	}
	
	/**
	 * @description
	 * gets the note of the choosen image
	 *
	 * @param required	: none
	 *
	 * @return		: array
	 *
	 * @access		: public
	 */
	public function getNotes() {
		
	    global $wpdb;
      $table_name = $wpdb->prefix . "pictagger";
	    
	    $note_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE pic_md5 = '".$this->picid."';"));
	    if ($note_count) {
		
		  $notes = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE pic_md5='".$this->picid."'"));
		
		
		foreach ($notes as $note) {
			$newNotes[] = array(
		    'ID' => $note->pic_img_ID,
		    'MD5' => $this->picid,
		    'LEFT' => $note->pic_left,
		    'TOP' => $note->pic_top,
		    'WIDTH' => $note->pic_width,
		    'HEIGHT' => $note->pic_height,
		    'DATE' => $note->pic_date,
		    'NOTE' => stripslashes($note->pic_tag),
		    'AUTHOR' => stripslashes($note->pic_face),
		    'LINK' => stripslashes($note->pic_link)
		 );
		}
		
		    $notes = $this->sortNotes($newNotes);
		
	    } else {
		  $notes = array();
	  }
	    return $notes;
	    
	}
	
	/**
	 * @description
	 * sort notes
	 *
	 * @param required	: array $notes // notes
	 *
	 * @return		: array
	 *
	 * @access		: private
	 */
	private function sortNotes($notes) {
	    
	    foreach ($notes as $key => $row)
		$x[$key] = sqrt($row['TOP']+$row['LEFT']);
	    
	    array_multisort($x, SORT_ASC, $notes);
	    
	    return $notes;
	    
	}
	
	/**
	 * @description
	 * grabs and transforms notes
	 *
	 * @param required	: array $notes // notes
	 *
	 * @return		: array
	 *
	 * @access		: private
	 */
	private function grabNotes($notes) {
	    
	    foreach ($notes as $note) {
		
		$newNotes[] = array(
		    'ID' => $note->ID,
		    'LEFT' => $note->LEFT,
		    'TOP' => $note->TOP,
		    'WIDTH' => $note->WIDTH,
		    'HEIGHT' => $note->HEIGHT,
		    'DATE' => $note->DATE,
		    'NOTE' => $note->NOTE,
		    'AUTHOR' => $note->AUTHOR,
		    'LINK' => $note->LINK
		);
		
	    }
	    
	    return $newNotes;
	    
	}
	
	/**
	 * @description
	 * adds a note to the choosen image
	 *
	 * @param required	: string $position // position of the note
	 * @param required	: string $note // value of the note
	 * @param required	: string $author // author of the note
	 * @param required	: string $link // link of the note
	 *
	 * @return		: none
	 *
	 * @access		: public
	 */
	public function addNote($position, $note, $author, $link) {
	    
	    $properties = $this->setNote($position, $note, $author, $link);
	    
	    if (!$properties)
		return false;
	    
	    $notes = $this->getNotes();
	    
	    $id = 0;
	    
	    foreach ($notes as $note) {
		
		if ($note['ID'] > $id)
		    $id = $note['ID'];
		   
		    }
	    
	    $id++;
	    
	    global $wpdb;

			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;

	    $table_name = $wpdb->prefix . "pictagger";
	    $wpdb->query($wpdb->prepare("INSERT INTO `$table_name`
										(
											`pic_img_ID`,
											`pic_md5`,
											`pic_guid`,
											`pic_author_ID`,
											`pic_post_ID`,
											`pic_left`,
											`pic_top`,
											`pic_width`,
											`pic_height`,
											`pic_date`,
											`pic_tag`,
											`pic_face`,
											`pic_link`
										)
										VALUES (
										'".addslashes($id)."',
										'".addslashes($this->picid)."',
										'".addslashes($this->image)."',
										'".addslashes($user_id)."',
										'".addslashes($this->postid)."',
										'".addslashes($properties['LEFT'])."',
										'".addslashes($properties['TOP'])."',
										'".addslashes($properties['WIDTH'])."',
										'".addslashes($properties['HEIGHT'])."',
										'".addslashes($properties['DATE'])."',
										'".addslashes($properties['NOTE'])."',
										'".addslashes($properties['AUTHOR'])."',
										'".addslashes($properties['LINK'])."'
										)"));
	    
	    return true;
	
	}
	
	/**
	 * @description
	 * saves all notes to the *.note data
	 *
	 * @param required	: array $notes // notes
	 *
	 * @return		: none
	 *
	 * @access		: private
	 */
	private function saveNotes($notes) {
	    global $wpdb;
	    
	    $handle = fopen($this->path, 'w+');
	    
	    fwrite($handle, json_encode($notes));
	    
	    fclose($handle);
	    
	}
	
	/**
	 * @description
	 * deletes a note from the list of notes of the choosen image
	 *
	 * @param required	: int $id // id of the note
	 *
	 * @return		: none
	 *
	 * @access		: public
	 */
	public function deleteNote($id) {
	    
	    global $wpdb;
	    $table_name = $wpdb->prefix . "pictagger";
	    $notes = $this->getNotes();
	    
	    for ($i = 0; $i < count($notes); $i++) {
		
		if ($notes[$i]['ID'] == $id)
		    unset($notes[$i]);
		    
		   $wpdb->query($wpdb->prepare(" DELETE FROM $table_name WHERE pic_img_ID='".$id."' AND pic_md5='".$this->picid."' AND pic_post_ID='".$this->postid."'"));
		
	    }
	    
	    
	    return true;
	    
	}
	
	/**
	 * @description
	 * edits the position and the value of a choosen note
	 *
	 * @param required	: int $id // id of the note
	 * @param required	: string $position // position of the note
	 * @param required	: string $note // value of the note
	 * @param required	: string $author // author of the note
	 * @param required	: string $link // link of the note
	 *
	 * @return		: none
	 *
	 * @access		: public
	 */
	public function editNote($id, $position, $note, $author, $link) {
	    
	    $properties = $this->setNote($position, $note, $author, $link);
	    
	    if (!$properties)
		  return false;
	    
	    $notes = $this->getNotes();
	    
	    for ($i = 0; $i < count($notes); $i++) {
		
		if ($notes[$i]['ID'] == $id) {
		    
		    $notes[$i] = array(
			'ID' => $id,
			'LEFT' => $properties['LEFT'],
			'TOP' => $properties['TOP'],
			'WIDTH' => $properties['WIDTH'],
			'HEIGHT' => $properties['HEIGHT'],
			'DATE' => $properties['DATE'],
			'NOTE' => $properties['NOTE'],
			'AUTHOR' => $properties['AUTHOR'],
			'LINK' => $properties['LINK']
		    );
		    
		    break;
		    
		     }
		
	    }
	    
	    
	    $picid = $this->picid;
	    
	    global $wpdb;
	    $table_name = $wpdb->prefix . "pictagger";
	    
	    $wpdb->query($wpdb->prepare("UPDATE $table_name SET pic_img_ID = '".$id."', pic_md5 = '".$this->picid."', pic_left = '".$properties['LEFT']."', pic_top = '".$properties['TOP']."', pic_width = '".$properties['WIDTH']."', pic_height = '".$properties['HEIGHT']."', pic_date = '".$properties['DATE']."', pic_tag = '".$properties['NOTE']."', pic_face = '".$properties['AUTHOR']."', pic_link = '".$properties['LINK']."' WHERE pic_img_ID='".$id."' AND pic_md5='".$this->picid."' AND pic_post_ID='".$this->postid."'"));

	    
	    return true;
	}
	
	/**
	 * @description
	 * sets the position and the value of a new or edited note
	 *
	 * @param required	: string $position // position of the note
	 * @param required	: string $note // value of the note
	 * @param required	: string $author // value of the author
	 * @param required	: string $link // link of the note
	 *
	 * @return		: array
	 *
	 * @access		: private
	 */
	private function setNote($position, $note, $author, $link) {
	    
	    $position = explode(',', $position);
	    
	    if (count($position) != 4)
		return false;
	    
	    if (empty($note))
		$note = '';
	    
	    $note = str_replace("\n", ' ', $note);
	    
	    while(strstr($note, '  '))
		$note = str_replace('  ', ' ', $note);
	    
	    $note = trim($note, ' ');
	    
	    if (empty($author))
		$author = '';
	    
	    if (empty($link) || !$this->isValidLink($link))
		$link = '';
	    
	    if (empty($note) && ($this->type == 'o'))
		return false;
	    
	    $date = date('Y-m-d H:i:s');
	    
	    return array(
		'LEFT' => $position[0],
		'TOP' => $position[1],
		'WIDTH' => $position[2],
		'HEIGHT' => $position[3],
		'DATE' => $date,
		'NOTE' => $note,
		'AUTHOR' => $author,
		'LINK' => $link
	    );
	
	}
	
	/**
	 * @description
	 * check valid uri
	 *
	 * @param required	: string $link // link of note
	 *
	 * @return		: boolean
	 *
	 * @access		: private
	 */
	private function isValidLink($link) {
	    return preg_match('/^(https?:\/\/){1}+[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,6})(.*?)$/i', $link);
	}
	
    }

?>