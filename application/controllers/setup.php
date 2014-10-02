<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {

	public function __construct() {
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->database();
	}

	private function doesConstraintExist($constraintName){
		if(!empty ($constraintName)){
			$sql = "SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE ".
                   "CONSTRAINT_SCHEMA = DATABASE() AND ".
                   "CONSTRAINT_NAME   = ?";
                   
            $query = $this->db->query($sql, array($constraintName));  
            return $query->num_rows()>0;
		}
		return false;
	}

	/**
	 * Sets up DB.
	 *
	 */
	public function index()	{
		$tables = array();
		$constraints = array();
$table = <<<'EOD'
CREATE TABLE IF NOT EXISTS `album` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`description` text,
`uploadDate` date NOT NULL,
`coverPhoto` int(11) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
EOD;
		array_push($tables, array('name'=>'album', 'script'=>$table));		

$table = <<<'EOD'
CREATE TABLE IF NOT EXISTS `photo` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`album` int(11) NOT NULL,
`name` varchar(255) NOT NULL,
`description` int(11) NOT NULL,
`meta` text,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
EOD;
		array_push($tables, array('name'=>'photo', 'script'=>$table));	

$constraint = <<<'EOD'
ALTER TABLE `photo` ADD CONSTRAINT `FK1_PHOTO` FOREIGN KEY (`album`) REFERENCES `album` (`id`) ON DELETE CASCADE
EOD;

		array_push($constraints, array('name'=>'FK1_PHOTO', 'script'=>$constraint));

		foreach($tables as $table){
			$query = $this->db->query($table['script']);	
			echo '<div>TABLE '.$table['name'].' CREATED.</div>';
		}
		foreach($constraints as $constraint){
			if(!$this->doesConstraintExist($constraint['name'])){
				$this->db->query($constraint['script']);	
				echo '<div>CONSTRAINT '.$constraint['name'].' ADDED.</div>';
			}
		}

		echo '<div>SETUP COMPLETED.</div>';
	}
}

?>