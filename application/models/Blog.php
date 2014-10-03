<?php
class Blog extends CI_Model {

    var $id             = null;
    var $category_id    = null;
    var $title          = null;
    var $content        = null;
    var $post_dt        = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    function load($where){        
        $this->db->select('blog.id, blog.category_id, blog.title, blog.content, blog.post_dt', FALSE);
        $this->db->from('blog');
        
        if(isset($where)){
            if(is_array($where) && count($where)>0){
                foreach($where as $column=>$value){
                    $this->db->where($column, $value); 
                }
            } else{
                $this->db->where('blog.id', $where); 
            }    
        }
        

        $query = $this->db->get();
        return $query->result();
    }

    function save() {
        $this->db->trans_start();
        $this->db->insert('blog', $this);
        $this->id=$this->db->insert_id();    
        $this->db->trans_complete();
            
    }

    function update() {
        $this->db->update('blog', $this, array('id' => $this->id));
    }

}
?>