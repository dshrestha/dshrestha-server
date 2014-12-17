<?php
class Users extends CI_Controller{
	
	public function __construct()
	{
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->library(array("session","form_validation"));
	    $this->load->helper(array('url','date'));
	    $this->load->database();	    
        $this->load->model('User');
	}

	public function authenticate(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() === FALSE) {
			$errors = array();
			$this->form_validation->set_error_delimiters('','');
			if($this->form_validation->error('username')!="") {
				array_push($errors,array('field'=>'username','message'=>array($this->form_validation->error('username'))));
			}
			if($this->form_validation->error('password')!=""){
				array_push($errors,array('field'=>'password','message'=>array($this->form_validation->error('password'))));
			}

			http_response_code(422);
			header('Content-type: application/json');
			echo (json_encode(array("errors"=>$errors)));				
		}
		else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$user = $this->User->load(array('email'=>$username)); 
			$salt = $user->salt;

			if(hash('sha512',$password.$salt) === $user->password){
				echo "success";
			} else {
				http_response_code(422);
				header('Content-type: application/json');
				echo (json_encode(array("errors"=>array('field'=>'username','message'=>array("Invalid username or password")))));
			}
			
		}
	}
}
?>