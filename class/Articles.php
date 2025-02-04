<?php
class Articles {	
   
	private $postTable = 'cms_posts';
	private $categoryTable = 'cms_category';
	private $userTable = 'cms_user';
	private $conn;
	public $id;
	
	public function __construct($db){
        $this->conn = $db;
    }	    
	
	public function getArticles(){

		$query = '';
		if($this->id) {		
			$query = " AND p.id ='".$this->id."'";
		}
		$sqlQuery = "
			SELECT p.id, p.title, p.message, p.category_id, u.first_name, u.last_name, p.status, p.created, p.updated, c.name as category
			FROM ".$this->postTable." p
			LEFT JOIN ".$this->categoryTable." c ON c.id = p.category_id
			LEFT JOIN ".$this->userTable." u ON u.id = p.userid
			WHERE p.status ='published' $query ORDER BY p.id DESC";
			
		$stmt = $this->conn->prepare($sqlQuery);		
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	
	function formatMessage($string, $wordsreturned) {
		$retval = $string;  //  Just in case of a problem
		$array = explode(" ", $string);
		/*  Already short enough, return the whole thing*/
		if (count($array)<=$wordsreturned)
		{
		$retval = $string;
		}
		/*  Need to chop of some words*/
		else
		{
		array_splice($array, $wordsreturned);
		$retval = implode(" ", $array)." ...";
		}
		return $retval;
	}
	
	public function totalPost(){		
		$sqlQuery = "SELECT * FROM ".$this->postTable;			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	
}
?>