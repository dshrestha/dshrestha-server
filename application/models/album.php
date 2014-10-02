<?php
class Album extends CI_Model {

    var $id   = null;
    var $coverPhoto = null;
    var $description    = null;
    var $name    = null;
    var $uploadDate    = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    function save() {
        $this->db->trans_start();
        $this->db->insert('album', $this);
        $this->db->trans_complete();
        return $this->db->insert_id();        
    }

    function update() {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }

}
?>