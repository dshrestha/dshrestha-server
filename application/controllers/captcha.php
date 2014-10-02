<?php

class Captcha extends CI_Controller{

	public function __construct()
	{
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->library("session");
	    $this->load->helper(array('url', 'captcha'));
	}

	public function index()	{
		$vals = array(
	    'img_path'	=> 'assets/images/captcha/',
	    'img_url'	=> base_url().'assets/images/captcha/',
	    'img_width'	=> '150',
	    'img_height' => 30,
	    'expiration' => 7200
	    );

		$cap = create_captcha($vals);
		$img = imagecreatefromjpeg($vals['img_url'].$cap['time'].'.jpg');
		
		$this->session->set_userdata('valid_captcha_word', $cap['word']);//use this to validate user
		
		header('Content-type: image/jpeg');
		imagejpeg($img);
	}
}
?>