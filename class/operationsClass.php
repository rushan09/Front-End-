<?php

class Operations
{
	
	public $session_id='';
	public $session_menu='';
	public $session_pg=0;
	public $session_tel='';
	public $session_others='';
	public $HoltName='';
	
	
	public function setSessions($sessions){

		$sql_sessions="INSERT INTO `sessions` (`sessionsid`, `tel`, `menu`, `pg`, `created_at`,`others`) VALUES 
			('".$sessions['sessionid']."', '".$sessions['tel']."', '".$sessions['menu']."', '".$sessions['pg']."', 'CURRENT_TIMESTAMP','".$sessions['others']."')";

		$quy_sessions=mysql_query($sql_sessions);
	}
	public function getSession($sessionid){	

		$sql_session="SELECT *  FROM  `sessions` WHERE  sessionsid='". $sessionid."'";
		$quy_sessions=mysql_query($sql_session);
		$fet_sessions=mysql_fetch_array($quy_sessions);
		$this->session_others=$fet_sessions['others'];
		return $fet_sessions;	
	}
	public function saveSesssion()
	{
		$sql_session="UPDATE  `sessions` SET 
									`menu` =  '".$this->session_menu."',
									`pg` =  '".$this->session_pg."',
									`others` =  '".$this->session_others."'
									WHERE `sessionsid` =  '".$this->session_id."'";
		$quy_sessions=mysql_query($sql_session);
	}
	
	public function getDirection($routeno)
	{
		$sql_session="SELECT *  FROM  `direction` WHERE  RouteNo='". $routeno."'";
		$quy_directions=mysql_query($sql_session);
		$fet_directions=mysql_fetch_array($quy_directions);
		$this->From=$fet_directions['From'];
		$this->To=$fet_directions['To'];
		return $fet_directions;
	}
	
	public function getBusStops($tablename)
	{
		$sql_session="SELECT HoltName FROM  `". $tablename."`";
		$quy_directions=mysql_query($sql_session);
		
		while($row=mysql_fetch_array($quy_directions)) {
			$return[] = $row;
			}	
		return $return;
	}
	
	public function getCurrentBusDetails($Direction)
	{
		$sql_session="SELECT HoltName FROM  `". $tablename."`";
		$quy_directions=mysql_query($sql_session);
		
		while($row=mysql_fetch_array($quy_directions)) {
			$return[] = $row;
			}	
		return $return;
	}
	
	
	
	public function getArrayBusStops($tablename)
	{
		$dataArr = $this->getBusStops($tablename);
		for($y = 0; $y < count($dataArr); $y++) {
					//echo "<td>".$dataArr[$y]."</td>";
					}
		$responseMsg01 = array(
			"busholt" =>  
				"Select your current Bus Stop
				3. ". $dataArr[0]['HoltName']."
				4. ". $dataArr[1]['HoltName']."
				5. ". $dataArr[2]['HoltName']."
				
				10. ". $dataArr[count($dataArr)-1]['HoltName']."
				
								
				99. Exit"
											);
		return $responseMsg01['busholt'];
}


	public function getArrayDirection($routeno)
	{
		$directiondetails = $this->getDirection($routeno);
		$responseMsg01 = array(
			"direction" =>  
				"Select your direction
				1. ".$directiondetails['From']."
				10. ".$directiondetails['To']."
				
				99. Exit"
											);
		return $responseMsg01['direction'];
}
	
}

?>















