<?php

class Albums extends CI_Controller{

	var $dropOffPath   = '';

	private function rrmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	}

	public function __construct()
	{
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->library("session");
	    $this->load->helper(array('url','date'));
	}

	public function createAlbum($params) {
        $this->load->model('Album');
        $this->Album->name = $params['name'];
        $this->Album->description = $params['description'];
        $this->Album->uploadDate = $params['uploadDate'];
        $album_id = $this->Album->save();
    }

	/**
	* This function reads images in the drof off path.
	*
	* @method 'importImageFromDropoff'
	*/
	public function importImageFromDropoff() {
		$this->load->library('imageresize');

        ini_set('memory_limit', '-1');
        ini_set("max_execution_time", 0);

        $assetFolder = FCPATH.'assets'.DIRECTORY_SEPARATOR;
        $dropOffFolder = $assetFolder . 'dropoff';
        $validTypes = array("image/jpeg", "image/png", "image/gif");
        
        if ($dfhandle = opendir($dropOffFolder)) {
            while (false !== ($dfentry = readdir($dfhandle))) {
                if ($dfentry != "." && $dfentry != "..") {
                    $dir = $dropOffFolder . DIRECTORY_SEPARATOR . $dfentry;
                    if (is_dir($dir) && $handle = opendir($dir)) {
                    	
                    	/*	
                        //create an album by the directory name
						$album_id = $this->createAlbum(
                        	array(
                        		'name'=>strtoupper(substr($dir, strrpos($dir, DIRECTORY_SEPARATOR) + 1))
                        	)
                        );
                        */

                        //make album directory
						$album_id = 1;
                        $albumDir = $assetFolder . 'albums' . DIRECTORY_SEPARATOR . trim(strtoupper($dfentry));

                        if(is_dir($albumDir)){
                        	$this->rrmdir($albumDir);
                        }
                        mkdir($albumDir);
                        mkdir($albumDir . DIRECTORY_SEPARATOR . 'THUMBS');

                        if (!($album_id === false)) {
                            while (false !== ($entry = readdir($handle))) {
                                if ($entry != "." && $entry != "..") {
                                    $filename = $dir . DIRECTORY_SEPARATOR . $entry;


                                    $imgExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                                    //check for valid image type
                                    if (in_array($imgExt, array('jpg', 'gif', 'png')) && ($fileMetaData = exif_read_data($filename)) && in_array($fileMetaData['MimeType'], $validTypes)) {
                                    	$this->imageresize->load($filename);

                                    	$this->imageresize->resizeToWidth(250);
                                        if($this->imageresize->getHeight()>250){
                                            $this->imageresize->resizeToHeight(250);    
                                        }                                    	
										$this->imageresize->save($albumDir . DIRECTORY_SEPARATOR . 'THUMBS'.DIRECTORY_SEPARATOR. $entry  , $this->imageresize->getImageType(), 100);
                                    	                                    	
                                    	//header('Content-Type: image/jpg');
                                    	//echo $this->imageresize->getImage();
                                    	
                                    	/*
                                        $imageResizer->resizeToHeight(600);
                                        $imageResizer->putWaterMark(CONST_IMAGE_BASE_DIR . 'copyright.png');
                                        $imageResizer->save($albumDir . DIRECTORY_SEPARATOR . $guid . '.' . $imgExt, $imageResizer->getImageType(), 100);

                                        $imageResizer->putWaterMark(null);
                                        $imageResizer->resizeToHeight(80);
                                        $imageResizer->save($albumDir . DIRECTORY_SEPARATOR . 'THUMB' . DIRECTORY_SEPARATOR . $guid . '.' . $imgExt, $imageResizer->getImageType(), 100);

                                        $this->setProperty('pic_id', $guid);
                                        $this->setProperty('album_id', $album_id);
                                        $this->setProperty('filename', $guid . '.' . $imgExt);
                                        $this->setProperty('taken_dt', $fileMetaData['DateTimeOriginal']);
                                        //echo $fileMetaData['DateTime']." ".$filename."<br>";
                                        $this->addPicture();
                                        */

                                    }
                                    
                                }
                            }
                        }
                        closedir($handle);                        
                    }
                }
            }
            closedir($dfhandle);
        }
    }
}
?>