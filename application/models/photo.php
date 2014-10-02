<?php
class Photo extends CI_Model {

    var $id             = null;
    var $album          = null;
    var $name           = null;
    var $description    = null;
    var $meta    = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    function load($where){        
        $this->db->select('photo.id, photo.album, photo.name, photo.description, photo.meta');
        $this->db->from('photo');
        
        if(count($where)>0){
            foreach($where as $column=>$value){
                $this->db->where($column, $value); 
            }
        }

        $query = $this->db->get();
        return $query->result();
    }

    function save() {
        $this->db->trans_start();
        $this->db->insert('photo', $this);
        $this->id=$this->db->insert_id();    
        $this->db->trans_complete();
            
    }

    function update() {
        $this->db->update('photo', $this, array('id' => $this->id));
    }

}
?>