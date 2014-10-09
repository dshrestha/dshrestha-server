<?php
	
class Newapassa extends CI_Controller{
	
	public function __construct()
	{
	    parent::__construct();
	    
	    /* Load the libraries and helpers */
	    $this->load->library("session");
	    
	}

	public function albums(){
		$this->load->model('Album');
		$album_id = $this->uri->segment(3, 0);
		$album_id = $album_id?$album_id:$this->input->get_post('album_id');
		$filter = array();
		$albums = array();
		
		if(!empty($album_id)){
			$filter["album.id"] = $album_id;
		}

		foreach($this->Album->load($filter) as $album){
			array_push ($albums,
				array(
					"id"=>$album->id,
					"name"=>$album->name,
					"description"=>$album->description, 
					"uploadDate"=>$album->uploadDate, 
					"photoCount"=>$album->photoCount,
					"coverPhoto"=>$album->coverPhotoName
					)
				);
		}
		
		header('Content-type: application/json');		
		echo (json_encode(array("albums"=>$albums)));		
	}

	public function albumPhotos(){
		$this->load->model('Photo');
		$album_id = $this->input->get_post('album');
		$filter = array();
		$photos = array();

		if(!empty($album_id)){
			$filter["album"] = $album_id;
		}

		foreach($this->Photo->load($filter) as $photo){
			array_push ($photos,
				array(
					"id"=>$photo->id,
					"album"=>$photo->album,
					"source"=>$photo->name, 
					"description"=>$photo->description, 
					"meta"=>$photo->meta
					)
				);
		}

		header('Content-type: application/json');
		echo (json_encode(array("album-photos"=>$photos)));
	}

	public function blogCategories(){
		$this->load->model('BlogCategory');
		$category_id = $this->uri->segment(3, 0);
		$category_id = $category_id?$category_id:$this->input->get_post('category_id');
		$filter = array();
		$categories = array();
		
		if(!empty($category_id)){
			$filter["blogCategory.id"] = $category_id;
		}

		foreach($this->BlogCategory->load($filter) as $category){
			array_push ($categories,
				array(
					"id"=>$category->id,
					"name"=>$category->name,
					"blogCount"=>$category->blogCount
					)
				);
		}
		
		header('Content-type: application/json');		
		echo (json_encode(array("blog-categories"=>$categories)));				
	}

	public function blogs(){
		$this->load->model('Blog');
		$category_id = $this->uri->segment(3, 0);
		$category_id = $category_id?$category_id:$this->input->get_post('category');
		$filter = array();
		$blogs = array();
		
		if(!empty($category_id)){
			$filter["blog.category_id"] = $category_id;
		}

		foreach($this->Blog->load($filter) as $blog){
			$date = new DateTime($blog->post_dt);
			$abstract = preg_match('/<abstract>(.*)<\/abstract>/', $blog->content, $matches);
			if(count($matches)>0){
				$abstract = $matches[1];
			}else{
				$abstract = substr(strip_tags($blog->content), 0, 500);
				$abstract = substr($abstract, 0, strrpos($abstract, " ")); 
			}

			array_push ($blogs,
				array(
					"id"=>$blog->id,
					"title"=>$blog->title,
					"category"=>$blog->category_id,
					"createdOn"=>$date->format('Y-m-d'),
					"abstract"=>$abstract."...."
					)
				);
		}
		
		header('Content-type: application/json');		
		echo (json_encode(array("blogs"=>$blogs)));		
	}

	public function blogContent(){
		$this->load->model('Blog');
		$blog_id = $this->uri->segment(3, 0);
		$filter = array();
		$blogs = array();

		$blog = $this->Blog->load($blog_id);
		echo $blog[0]->content;		
		
	}

	public function companies(){
		header('Content-type: application/json');
		echo '{
			"companies":[
				{
					"id":1,
					"name":"Affinnova",
					"website":"http://www.affinnova.com/",
					"logoPath":"assets/images/logo/affinnova.jpg",
					"description":"Affinnova is the technology platform of choice for companies seeking to dramatically improve their innovation and marketing success rates. Powered by Affinnova’s optimization algorithms and predictive analytics and insights, marketers can explore a substantially wider creative space of product, advertising and design ideas, quickly identifying which will perform best in the market.",
					"address_1":"265 Winter Street",
					"address_2":"",
					"city":"Waltham",
					"state":"MA",
					"zip":"02451",
					"country":"USA"
					
				},
				{
					"id":2,
					"name":"Innovate! Inc. ",
					"website":"http://innovateteam.com/",
					"logoPath":"assets/images/logo/innovate.gif",
					"description":"Innovate! is an 8(a) certified, “green” business providing geospatial solutions, software engineering, IT security services, management consulting and transformation consulting services. We are passionate about and live consistent with taking care of the planet. Our primary clients are EPA, USGS, USDA, and many state, tribal and US territory environmental departments. Our focus is to drive efficiencies and business results through innovative consulting techniques IT solutions.",
					"address_1":"5835 Valley View Drive",
					"address_2":"",
					"city":"Alexandria",
					"state":"VA",
					"zip":"22310-1626",
					"country":"USA"					
				},
				{
					"id":3,
					"name":"Wayfair, LLC.",
					"website":"http://wayfair.com/",
					"logoPath":"assets/images/logo/wayfair.jpg",
					"description":"Wayfair is a leader in the ecommerce space for things for the home (couches, end tables, lamps, and literally 3.1 million other items) and #51 overall in the ecommerce rankings(2011).",
					"address_1":"4 Copley Place",
					"address_2":"Floor 7",
					"city":"Boston",
					"state":"MA",
					"zip":"02116",
					"country":"USA"					
				},
				{
					"id":4,
					"name":"Worldlink Technologies Pvt. Ltd.",
					"website":"http://wlinktech.com/",
					"logoPath":"assets/images/logo/worldlink.gif",
					"description":"WorldLink is an experienced software solutions provider with extensive experience in consulting, development and implementation of enterprise applications in areas of ERP, CRM, HRM, Payroll and Pensions. Our experience covers a wide range of industry and market sectors that include government, telecom, BFSI and private businesses. Our services include consulting, application development, product management and maintenance, enterprise application implementation, integration, quality assurance and testing. ",
					"address_1":"",
					"address_2":"",
					"city":"Pulchowk Lalitpur-5",
					"state":"Bangmati",
					"zip":"",
					"country":"Nepal"					
				}
			]}';
	}

	public function experiences(){
		header('Content-type: application/json');
		echo '{
			"experiences":[
				{
					"id":1,
					"company":1,
					"title":"Software Engineer",
					"startDate":"08/05/2013",
					"endDate":null,
					"projects":[{
						"id":1,
						"name":"Affinnova Studio",
						"displayName":"Affinnova Studio",
						"description":"",
						"web":"",
						"responsibilities":[
							"Design and architect new single-page web applications.",
							"Develop and maintain key system features .",
							"Evaluate new technologies to advance our SaaS platform and grow our business.",
							"Be part of a collaborative team that will lead projects from beginning to end– from working with product management to gather requirements to brainstorming solutions, building the final applications and testing."
						],
						"languages":[
							"groovy",
							"grails",
							"mssql",
							"javascript",
							"emberjs",
							"ember-data",
							"css",
							"less",
							"git",
							"nodejs"
						]
					}]
				},
				{
					"id":2,
					"company":2,
					"title":"Software Engineer",
					"startDate":"03/26/2012",
					"endDate":"07/26/2013",
					"projects":[
						{
							"id":2,
							"name":"Recycle City Game",
							"displayName":"Recycle City Game",
							"description":"This project seeks to add a new interactive game to the Recycle City site to increase visitor engagement and expand the site\'s themes into more complex issues of sustainable materials management and energy use.",
							"web":"http://it.innovateteam.com/RecycleCity/",
							"responsibilities":[
								"Prototyping an interactive recycle city game targeting both mobile devices and browsers for young audience.",
								"Abstracting javascript core logic so the game can be played in mobile devices using phonegap API and also in browser with minimal code changes.",
								"Collaborating with the client to design roles and game play strategies."
							],
							"languages":[
								"phonegap",
								"html",
								"css",
								"javascript",
								"jquery"
							]
						},
						{
							"id":3,
							"name":"Innovate Portal",
							"displayName":"Innovate Portal",
							"description":"Intranet portal was built to connect and inform all the employees of innovate team.",
							"web":"",
							"responsibilities":[
								"Installing, configuring and building intranet portal with Drupal.",
								"Wrote custom modules to add features to portal such as implementing cloud drive search using apache solr, portal usage report/charts, employee of the month etc.",
								"Modifying existing modules to accommodate custom requirements that are not provided my module out of the box.",
								"Built interface in drupal to do solr based based search in our local file servers."
							],
							"languages":[
								"drupal 7",
								"php",
								"html",
								"css",
								"solr",
								"javascript",
								"jquery",
								"google charts"
							]
						},
						{
							"id":4,
							"name":"EDG",
							"displayName":"EDG (Environmental Dataset Gateway)",
							"description":"Environmental Dataset Gateway(EDG) is used for searching and viewing EPA\'s environmental resources as well as to manage metadata records.",
							"web":"http://wiz.innovateteam.com:8080/gptlv10/catalog/main/home.page",
							"responsibilities":[
								"Understanding the underlying geoportal framework and adding/modifying administrative functionalities. Eg. added concept of collection and created additional pages to create and manage collection and collection members.",
								"added a visualization mechanism to show the relationship between collection and members metadata resources in a tree like structure using \"JavaScript InfoVis Toolkit\"",
								"modified lucene index and geoportal search API so that it shows parent/child relationship between the metadata resources in the search result",
								"Parsing ontology based xml files and moving the data into database maintaining proper inter-data relationship using python. Also re-writing the ontology web service to use this data in order to improve performance on the response compared to the older version of ontology service."
							],
							"languages":[
								"geoportal framework",
								"jsf",
								"jsp",
								"servlets",
								"lucene",
								"javascript",
								"json",
								"jquery",
								"ajax",
								"python",
								"html",
								"css"
							]
						},
						{
							"id":5,
							"name":"DFE",
							"displayName":"DFE (Design For The Environment)",
							"description":"Web tool that allows visitors to see and filter out products that are labeled chemically safe for usage from the government.",
							"web":"http://wiz.innovateteam.com:8080/dfe/pubs/projects/formulat/formpart.htm",
							"responsibilities":[
								"Initially we had used SIMILE EXHIBIT for this application but it had poor support and issues in ie7 for larger datasets. We could work with only around 800 records without getting the \"slow script\" error so I worked on my own version of exhibit that mimicked the simile exhibit functionalities within short period of 3 days which overcame these issues.",
								"Customized functionalities of the application to enhance search experience for the end users. "
							],
							"languages":[
								"javascript",
								"json",
								"jquery",
								"ajax",
								"xml",
								"html",
								"css"
							]
						},
						{
							"id":6,
							"name":"ROE",
							"displayName":"Mapping infrastructure for ROE",
							"description":"Web tool that allows visitors to see various indicators informing on national conditions, air, water, land and ecological systems using bing and arcgis maps.",
							"web":"http://wiz.innovateteam.com:8080/ROE/",
							"responsibilities":[
								"Create web service that extracts information from ArcGis server and render interactive maps/layers using OpenLayers API.",
								"build javascript functionality that hides inner detail of OpenLayers API to provide simple and standard interface to end users to manipulate maps.",
								"added custom controls to OpenLayers and also extra functionalities to make the maps interactive and display information in user friendly manner",
								"logics to circumvent various browser incompatibility issues"
							],
							"languages":[
								"openlaters api",
								"javascript",
								"json",
								"jquery",
								"ajax",
								"xml",
								"html",
								"css"
							]
						}
					]	
				},
				{
					"id":3,
					"company":3,
					"title":"Software Engineer",
					"startDate":"12/05/2011",
					"endDate":"03/22/2012",
					"projects":[{
						"id":10,
						"name":"Wayfair",
						"displayName":"Wayfair",
						"description":"",
						"web":"",
						"responsibilities":[
							"Building and maintaining web-based ecommerce applications, working with several large and complex SQL databases.",
							"Assisting with the development of software algorithms to streamline back end business workflow.",
							"Producing robust, elegant and scalable software that performs well under high load, being creative and resourceful to solve complex business challenges.",
							"Understanding business needs, risks and making appropriate technology decisions."
						],
						"languages":[
							"php",
							"asp",
							"mssql",
							"javascript",
							"json",
							"yui",
							"ajax",
							"xml",
							"css",
							"svn"
						]
					}]
				},
				{
					"id":4,
					"company":2,
					"title":"Software Engineer",
					"startDate":"10/18/2010",
					"endDate":"11/15/2011",
					"projects":[						
						{
							"id":7,
							"name":"Metrics",
							"displayName":"Metrics",
							"description":"Metrics is web based interactive reporting tool that was developed for EPA for tracking information regarding various resource usage.",
							"web":"http://wiz.innovateteam.com:8080/metrics/",
							"responsibilities":[
								"Built tools to read information from external web pages and apply various regular expression pattern matches to extract out relevant information and store in database.",
								"Parsed complex metadata in XML format to create XPATH and then extract its values.",
								"Built web application using exhibit to provide and filter out information regarding various resources and its based on location and various other criteria. Eg: nature of resource, user of the resource etc.",
								"Hand coded and designed the pages and implemented google chart to increase the information content.",
								"Wrote trigger on postgres db using python language"
							],
							"languages":[
								"openlaters api",
								"jsp",
								"postgres",
								"python",
								"simile exhibit",
								"google charts",
								"javascript",
								"json",
								"jquery",
								"ajax",
								"xml",
								"html",
								"css"
							]
						},
						{
							"id":8,
							"name":"EME",
							"displayName":"EPA Metadata Editor",
							"description":"The EME is a simple geospatial metadata editor that allows users to create and edit records that meet the EPA Geospatial Metadata Technical Specification and Federal Geographic Data Committee (FGDC) Content Standard for Digital Geospatial Metadata (CSDGM) requirements.",
							"web":"https://edg.epa.gov/EME/",
							"responsibilities":[
								"Customized the EME web site and added new tools to the web application.",
								"Used Openlayer API to highlight and convey useful information regarding the EME application usage in US as well as world wide.",
								"Used exhibit application to provide highly flexible and informative interface to increase visibility of information regarding usage of EME. Eg: pin point the user\'s location in Map, filter information out via various filter criteria etc.",
								"Modified exhibit library to support and add various exporters as well as other functionalities."
							],
							"languages":[
								"openlaters api",
								"jsp",
								"msaccess",
								"python",
								"simile exhibit",
								"javascript",
								"json",
								"jquery",
								"ajax",
								"xml",
								"html",
								"css"
							]
						},
						{
							"id":9,
							"name":"MQRS",
							"displayName":"Moratorium Qualification Review System",
							"description":"The Moratorium Qualification Review System (MQRS) developed for NOAA(http://www.noaa.gov/) provides the ability to follow individual applications through the moratorium qualification review process, which determines whether an applicant is qualified to participate in a Limited Access fishery. An applicant first qualifies based on catch history and is then permitted to transfer the right to a replacement vessel or to another vessel through a sale. The system also permits rights to be merged. A moratorium authorization on a fishery is implemented through the issuance of a moratorium right to a vessel based on a review of qualification material. This review process includes reviews, hearings and appeals for moratorium qualification, moratorium rights transfer, vessel upgrade, history retention and confirmation of permit history (CPH). These processes conclude in the approval or denial of an authorization for the moratorium fishery and subsequent issuance of an authorization or letter of denial. The MQRS allows the issuance of permits through the Vessel Permit System (VPS) only to vessels meeting the qualification criteria. These functions are essential to the implementation of regulations under the moratorium management scheme.",
							"web":"",
							"responsibilities":[
								"Identified the data issues associated with existing system, devised and implemented solutions to fix those data.",
								"Manipulated data from old model and processed/formatted them to be represented in new model and migrated them taking into account and maintaining the integrity of data.",
								"Developed an interim web application to give end users the view of data represented in new model, and provided tools to identify the data which goes against business rules.",
								"Implemented and started “codeigniter” framework, modified it according to the client’s need.",
								"Developed an exhibit application to provide graphical representation of the data to better understand/represent the nature of it at various time frame.",
								"Participating in model/business rule refinement discussion and implementing them"
							],
							"languages":[
								"php",
								"oracle 11g",
								"sql developer",
								"data modeler",
								"simile exhibit",
								"javascript",
								"json",
								"codeigniter",
								"jquery",
								"prototype",
								"ajax",
								"xml",
								"html",
								"css"
							]
						}
					]	
				},
				
				{
					"id":5,
					"company":4,
					"title":"Software Engineer",
					"startDate":"10/01/2006",
					"endDate":"01/20/2010",
					"projects":[
						{
							"id":11,
							"name":"Endeavor",
							"displayName":"Endeavor",
							"description":"“Endeavor” is WorldLink’s flagship product which is a business application that allows company of any size to comprehensively manage the various aspects of their business. It offers complete coverage of all basic bookkeeping functions of a company including, financial, asset, inventory, payroll and human resource. It consists of the various modules (Personnel Information, Attendance Management, Post Management, Recruitment, Training, and Payroll) that work independently or in an integrated fashion.",
							"web":"",
							"responsibilities":[
								"Designed and developed Personnel Information Module for Nepal Investment Bank Ltd. to track and maintain all HR employee information including education and service, as well as generate payroll.",
								"Improved Nepal Investment Bank’s ability to profile optimal candidates for specific jobs based on unique requirement criteria, developing comprehensive Recruitment Management System. ",
								"Analyzed, designed, and forwarded for development Training Module for Nepal Investment Bank.",
								"Integrated all modules under single framework while maintaining 100% adherence to standards.",
								"Overcame obstacle of ajax calls blocking each other due to sharing of same object, developing new Ajax framework.",
								"converted the static menu to dynamic menu system for intranet portal ",
								"created functions/procedures/packages and other plSql related tasks in oracle to optimize the code by transferring the DML task/logics from PHP to oracle",
								"Wrote front-end (PHP, HTML) and backend (Oracle) logics."
							],
							"languages":[
								"php",
								"oracle 9i",
								"plsql",
								"javascript",
								"ajax",
								"xml",
								"css",
								"html"
							]
						},
						{
							"id":12,
							"name":"Pension Information System ",
							"displayName":"Personal and Pension Information System ",
							"description":"Personal and Pension Information System is an intranet web based application for automating the task of keeping track of government official’s full information including their educational details, service details, leave, awards, and punishments. Using this information the system computes pension or gratuity that the employee is entitled with after his/her retirement. The system also generates dynamic letters and reports associated with the daily process of office activity.",
							"web":"",
							"responsibilities":[
								"Devised and implemented innovative solution to simplify organization of vast amount of HR Personnel Information for Ministry of General Administration",
								"Reduced load time, creating collapsible hierarchy similar to Windows File Explorer, and incorporated Ajax with search option.",
								"Cut development time, and simplified work for development team, creating form validation class able to generate equivalent client-side validation codes for corresponding server-side validations calls.",
								"Instrumental in generating additional business, co-developing and leading modification of system in accordance with client’s request and development requirements; modified in minimal time of 3 months. ",
								"Handpicked subsequently to solely develop variant of project in Department of Police Personnel Records; completed in short 3-month duration.",
								"developed an enhanced hierarchal version of menu system that could be coupled with the security system to more easily handle the security ",
								"wrote the front end logics, GUI using PHP, HTML and CSS ",
								"wrote back end logics in oracle 10g",
								"developed a nifty JavaScript tool for searching and selecting element from select box with large number of options easily"
							],
							"languages":[
								"php",
								"oracle 10g",
								"plsql",
								"javascript",
								"ajax",
								"xml",
								"css",
								"html"
							]
						},
						{
							"id":13,
							"name":"Credit Information System",
							"displayName":"Credit Information System",
							"description":"CIS is a system that maintains information of Individual and Firm/Company as different entities. These entities may be Borrower, Shareholder/Director, Proprietor/Partner, Guarantor, Guarantor Shareholder/Director, Valuator, and Valuator Shareholder/Director etc. The system helps better inform all the banks and financial institutions of Nepal about the credibility of the borrower by tracking the past credit history of the borrower. ",
							"web":"",
							"responsibilities":[
								"Maximized ability to determine credibility of potential borrowers, developing Credit Information System to compile credit history, and make data available to other entities on demand. ",
								"Built forms to capture and validate user input, created templates with smarty, developed reports, and implemented menu-based security module.",
								"researched and implemented new paging system",
								"wrote the front end and back end logics for the software"								
							],
							"languages":[
								"php",
								"oracle 10g",
								"plsql",
								"smarty",
								"javascript",
								"ajax",
								"xml",
								"css",
								"html"
							]
						}
					]
				}
			]}';
	}

	public function sendFeedback(){
		$validCaptcha = $this->session->userdata('valid_captcha_word');
		if($this->session->userdata('valid_captcha_word') === $this->input->post('captcha')){
			$to      = 'quaint.stranger@gmail.com';
			$from    = $this->input->post('email');
			$subject = 'feedback from newapassa';
			$message = $this->input->post('message');
			$headers = 'From: '.$from. "\r\n" .
			'Reply-To: '.$from. "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);	
		} else {
			http_response_code(422);
			header('Content-type: application/json');
			echo (json_encode(array("errors"=>array(array('field'=>'captcha','message'=>'Captcha value entered did not match.')))));				
		}
		
	}
}	

?>