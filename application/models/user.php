<?php
class User extends CI_Model {

    var $id             = null;
    var $username     	= null;
    var $password     	= null;
    var $role    		= null;
    var $salt    		= null;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    function load($where){        
        $this->db->select('user.id, user.email, user.password, user.salt');
        $this->db->from('user');
        
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
