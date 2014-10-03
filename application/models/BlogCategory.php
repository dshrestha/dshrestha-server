<?php
class BlogCategory extends CI_Model {

    var $id             = null;
    var $name           = null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    function load($where){        
        $this->db->select('blog_category.id, blog_category.name, IFNULL(blogs.blogCount,0) as blogCount', FALSE);
        $this->db->from('blog_category');
        $this->db->join('(SELECT category_id, count(*) as blogCount FROM blog GROUP BY category_id) blogs', 'blog_category.id = blogs.category_id', 'left');

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
        $this->db->insert('blogCategory', $this);
        $this->id=$this->db->insert_id();    
        $this->db->trans_complete();
            
    }

    function update() {
        $this->db->update('blogCategory', $this, array('id' => $this->id));
    }

}
?>