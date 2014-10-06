<?php

class Albums extends CI_Controller{

	var $dropOffPath   = '';

	public function __construct()
	{
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->library(array("session", "imageresize"));
	    $this->load->helper(array('url','date'));
        $this->load->model('Album');
        $this->load->model('Photo');
	}

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

	public function createAlbum($params) {
        $newAlbum = new Album();
        $newAlbum->name = $params['name'];
        $newAlbum->description = $params['description'];
        $newAlbum->uploadDate = $params['uploadDate'];
        $newAlbum->save();
        return $newAlbum;
    }

    public function createPhoto($params) {
        $newPhoto = new Photo();
        $newPhoto->album = $params['album'];
        $newPhoto->name = $params['name'];
        $newPhoto->description = $params['description'];
        $newPhoto->meta = $params['meta'];
        $newPhoto->save();
        return $newPhoto;        
    }

	/**
	* This function reads images in the drof off path.
	*
	* @method 'importImageFromDropoff'
	*/
	public function importImageFromDropoff() {

        ini_set('memory_limit', '-1');
        ini_set("max_execution_time", 0);
        
		$assetFolder = FCPATH.'assets'.DIRECTORY_SEPARATOR;
        $dropOffFolder = $assetFolder . 'dropoff'.DIRECTORY_SEPARATOR;
        $validImageTypes = array("image/jpeg", "image/png", "image/gif");
        $first_photo_id = null;

        foreach(array_diff(scandir($dropOffFolder), array('..', '.')) as $albumName){
            $album = $dropOffFolder.$albumName;
            if (is_dir($album)){
                $description = file_get_contents($album.DIRECTORY_SEPARATOR.'description.txt');
                //$albumFolder = FCPATH.'..'. DIRECTORY_SEPARATOR .'dshrestha'. DIRECTORY_SEPARATOR .'assets'. DIRECTORY_SEPARATOR . 'albums' . DIRECTORY_SEPARATOR . trim(strtoupper($albumName));
                $albumFolder = $assetFolder. 'albums' . DIRECTORY_SEPARATOR . trim(strtoupper($albumName));

                //CREATE ALBUM ENTRY IN DATABASE
                $newAlbum = $this->createAlbum(array(
                    'name'=>strtoupper(trim($albumName)),
                    'description'=>$description?$description:'',
                    'uploadDate'=>date("Y-m-d H:i:s",now())
                ));

                //remove folder if it alrady exits
                $this->rrmdir($albumFolder);
                mkdir($albumFolder);
                mkdir($albumFolder . DIRECTORY_SEPARATOR . 'THUMBS');

                foreach(array_diff(scandir($album), array('..', '.')) as $photoName){
                    $photo = $album.DIRECTORY_SEPARATOR.$photoName;
                    if (!is_dir($photo)){
                        
                        $fileMetaData = @exif_read_data($photo);
                        
                        if ($fileMetaData!==FALSE && in_array($fileMetaData['MimeType'], $validImageTypes)){

                            $this->imageresize->load($photo);

                            $this->imageresize->putWaterMark($assetFolder.'images'.DIRECTORY_SEPARATOR.'copyright.png');
                            $this->imageresize->save($albumFolder . DIRECTORY_SEPARATOR. $photoName  , $this->imageresize->getImageType(), 100);

                            //CREATE AND SAVE THUMBNAIL
                            $this->imageresize->resizeToWidth(250);
                            if($this->imageresize->getHeight()>250){
                                $this->imageresize->resizeToHeight(250);    
                            }                                       
                            $this->imageresize->save($albumFolder.DIRECTORY_SEPARATOR.'THUMBS'.DIRECTORY_SEPARATOR. $photoName  , $this->imageresize->getImageType(), 100);

                            $newPhoto = $this->createPhoto(array(
                                'album'=>$newAlbum->id,
                                'name'=>$photoName,
                                'description'=>'',
                                'meta'=>json_encode($fileMetaData)   
                            ));
                               
                            if($first_photo_id===null){
                                $first_photo_id = $newPhoto->id;
                                $newAlbum->coverPhoto = $first_photo_id;
                                $newAlbum->uploadDate = date("Y-m-d H:i:s",strtotime($fileMetaData['DateTime']));
                                $newAlbum->update();                                
                            }                                                    
                        }

                    }
                }
            }
        }        
    }
}
?>