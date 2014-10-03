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
		$records = array();

//TABLE SCRIPTS		
$table = <<<'EOD'
CREATE TABLE IF NOT EXISTS `album` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`description` text,
`uploadDate` date NOT NULL,
`coverPhoto` int(11) DEFAULT NULL,
UNIQUE KEY `UNQ1_ALBUM` (`name`),
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

$table = <<<'EOD'
CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQ1_BLOG_CATEGORY` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 
EOD;
		array_push($tables, array('name'=>'blog_category', 'script'=>$table));	

$table = <<<'EOD'
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `content` text,
  `post_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attachment` longblob,
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 
EOD;
		array_push($tables, array('name'=>'blog', 'script'=>$table));

//CONSTRAINTS SCRIPTS
$constraint = <<<'EOD'
ALTER TABLE `photo` ADD CONSTRAINT `FK1_PHOTO` FOREIGN KEY (`album`) REFERENCES `album` (`id`) ON DELETE CASCADE
EOD;

		array_push($constraints, array('name'=>'FK1_PHOTO', 'script'=>$constraint));

$constraint = <<<'EOD'
ALTER TABLE `blog` ADD CONSTRAINT `FK1_BLOG` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`id`) ON DELETE CASCADE
EOD;

		array_push($constraints, array('name'=>'FK1_BLOG', 'script'=>$constraint));

$record = <<<'EOD'
TRUNCATE TABLE `blog_category`
EOD;
	array_push($records, $record);

$record = <<<'EOD'
INSERT INTO `blog_category` (`id`, `name`) VALUES
(2, 'MOBILE'),
(3, 'MYSQL'),
(1, 'PHP')
EOD;
	array_push($records, $record);

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
		foreach($records as $record){
			$query = $this->db->query($record);	
			echo '<div>INSERT COMPLETED.</div>';
		}

		echo '<div>SETUP COMPLETED.</div>';
	}
}

?>