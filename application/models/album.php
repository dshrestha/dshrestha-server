<?php
class Album extends CI_Model {

    var $id             = null;
    var $coverPhoto     = null;
    var $description    = null;
    var $name           = null;
    var $uploadDate     = null;

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function save() {
        $this->db->trans_start();
        $this->db->insert('album', $this);
        $this->id = $this->db->insert_id();        
        $this->db->trans_complete();        
    }

    function update() {
        $data = array(
               'coverPhoto' => $this->coverPhoto,
               'description' => $this->description,
               'name' => $this->name,
               'uploadDate' => $this->uploadDate
            );

        $this->db->where('id', $this->id);
        $this->db->update('album', $data);
    }

    function load($where){        
        $this->db->select('album.id, album.description, album.name, album.uploadDate, albumPhotoCount.photoCount, photo.name as coverPhotoName');
        $this->db->from('album');
        $this->db->join('photo', 'album.coverPhoto = photo.id', 'left');
        $this->db->join('(SELECT album, count(*) as photoCount FROM photo GROUP BY album) albumPhotoCount', 'album.id = albumPhotoCount.album', 'left');

        if(count($where)>0){
            foreach($where as $column=>$value){
                $this->db->where($column, $value); 
            }
        }

        $query = $this->db->get();
        return $query->result();
    }

}
?>