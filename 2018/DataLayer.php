<?php

include_once 'variables.php';

class DataLayer{

public $conn;
private $var1;

public function connect(){
$var = new variables();
$odbc = $var->getODBC();
$user = $var->getUser();
$pass = $var->getPass();

/*
echo $odbc;
echo $user;
echo $pass;
*/

$this->conn = odbc_connect($odbc,$user,$pass);
return $this->conn;
}

public function disconnect(){
return odbc_close($this->conn);
}

public function getRound(){
$var1 = new variables();
return $var1->getRound();
}

public function getYear(){
$var1 = new variables();
return $var1->getYear();
}

public function getYearRowid(){
$var1 = new variables();
return $var1->getYearRowid();
}

public function getUsers(){

	$Query = "exec sp_getUsers";

	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $Query);
	  
		echo '<select id="user" style="width:150px;">';
		echo '<option value="username">&ltSelect User&gt</option>';
		while(odbc_fetch_row($result))
		{
			echo '<option value="' . odbc_result($result,1) .'">'.odbc_result($result,2).'</option>';
		}
		echo '</select>';
	}
	else echo "odbc not connected";
}

public function getTeamsByRound($round, $year){

	$concatenatedQuery = "exec sp_GetTeamsByRound " . $round . ',' . $year;

	//this function should ideally just return the $result to the calling function as an array and handling the code below should be put in a business layer file
	
	if ($this->conn){
	$result = odbc_exec($this->conn, $concatenatedQuery);
	  
	echo '<tr><th colspan="4"><div class="rnd">ROUND '.$round;
	if ($round == 27) {echo ' QUALIFYING';} ELSEIF ($round == 28) {echo ' SEMI FINAL';} ELSEIF ($round == 29) {echo ' PRELIM FINAL';} ELSEIF ($round == 30) {echo ' GRAND FINAL';}
	echo ' SELECTIONS</div></th></tr>';
	//LOOP THROUGH REQUESTED TABLE/RESULT SET
	
	if (odbc_num_rows($result) < 1)
	{
	  echo '<tr><td>No round selections available yet.</td></tr>';
	}
	else
	{
	  while(odbc_fetch_row($result))
	  {
	  
		//str_replace used because the img names stored on NRL website doesnt match the team names stored in tipping database (tipping database contains spaces in the names, while NRL website does not contain spaces).	    
		echo '<tr><td valign="middle"><img height="100px" width="100px" src="https://www.nrl.com/client/dist/logos/'.str_replace(' ', '-', odbc_result($result,3)).'-badge.svg"/></td><td><img height="50px" width="100px" alt="versus" src="marketVS.png"></td><td><img height="100px" width="100px" src="https://www.nrl.com/client/dist/logos/'.str_replace(' ', '-', odbc_result($result,5)).'-badge.svg"/></td><td><select ';
		
		//disable selection for any games that have already had results posted
		if (odbc_result($result,6) <> NULL) { echo 'disabled'; }
		
		//finish string concat
		echo ' style="width:100;" id="'.str_replace(' ', '', odbc_result($result,1)).'-"><option value="'.odbc_result($result,2).'">'.odbc_result($result,3).'</option><option value="'.odbc_result($result,4).'">'.odbc_result($result,5).'</option></select></td></TR>';
		
		//FALLBACK FOR WHEN IMAGES WHICH MAY HAVE HAD THEIR PATHS CHANGED ON THE NRL SITE.
		//'<tr><td valign="middle"><img height="100px" width="100px" src="img/'.str_replace(' ', '', odbc_result($result,3)).'.svg"/></td><td><img height="50px" width="100px" alt="versus" src="marketVS.png"></td><td><img height="100px" width="100px" src="img/'.str_replace(' ', '', odbc_result($result,5)).'.svg"/></td><td><select style="width:100;" id="'.str_replace(' ', '', odbc_result($result,1)).'-"><option value="'.odbc_result($result,2).'">'.odbc_result($result,3).'</option><option value="'.odbc_result($result,4).'">'.odbc_result($result,5).'</option></select></td></TR>';
	  }
	}
  }
  else echo "odbc not connected";
}




public function getTeams(){

$Query = "exec sp_getTeams";

	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $Query);
		$teams = array();
	 
		while(odbc_fetch_row($result))
		{
			array_push($teams,odbc_result($result,1),odbc_result($result,2));
		}
		
		return $teams;
	}
	else echo "odbc not connected";

	}
	
public function InsertTeamsByRound($rowid, $team1, $team2, $round, $year ){

	if ($this->conn)
		{
			$bool = @odbc_exec($this->conn, "INSERT INTO [footytipping].[dbo].[round]
           ([r_rowid]
           ,[r_teamrowid1]
           ,[r_teamrowid2]
           ,[r_round]
           ,[r_yearrowid]
		   )
     VALUES
           ($rowid
           ,$team1 
           ,$team2 
           ,$round 
           ,$year
		   )"
		   );
		   
		   if ($bool)
		   echo 'Selection submitted successfully! </br>';
		   else
		   echo 'something went wrong </br>';
		   
		   echo odbc_errormsg($this->conn) . '</br>';
		}
			else echo "[InsertTeamsByRound] odbc not connected";
	}	
	

//RETURNS SINGLE INT
public function getLatestRoundRowID(){
	$Query = "exec sp_GetLatestRoundRowID";
	
	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $Query);
		odbc_fetch_row($result);
		return odbc_result($result,1);	
	}
		else echo "[getLatestRoundRowID] odbc not connected";	
}



//RETURNS INT ARRAY
public function getRowIdsByRound($round, $year){

	$Query = "exec sp_getRowIdsByRound " . $round . "," . $year;
	//echo $query;
	
	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $Query);
		$rowids = array();
	 
		while(odbc_fetch_row($result))
		{
			array_push($rowids,odbc_result($result,1),odbc_result($result,2),odbc_result($result,3));
		}
		
		return $rowids;
	}
	else 
	echo "[getRowIdsByRound] odbc not connected";

}


//RETURNS SINGLE INT
public function getMinRowIdWhereWinnerIsNULL($round, $year){

	$query = "exec sp_getMinRowIdWhereWinnerIsNULL " . $round . ',' . $year;
	//echo $query;
	
	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $query);
		return odbc_result($result,1) ;
	}
	else 
	echo "[getMinRowIdWhereWinnerIsNULL] odbc not connected";
	
}

//RETURNS SINGLE INT
public function getMaxRowIdWhereWinnerIsNULL($round, $year){

	$query = "exec sp_getMaxRowIdWhereWinnerIsNULL " . $round . ',' . $year;
	//echo $query;
	
	if ($this->conn)
	{
		$result = odbc_exec($this->conn, $query);
		return odbc_result($result,1) ;
	}
	else 
	echo "[getMinRowIdWhereWinnerIsNULL] odbc not connected";
	
}


public function insertIntoResults($resRowid,$resRoundRowid,$resWinner){

	if ($this->conn)
			{
				$bool = @odbc_exec($this->conn, "INSERT INTO [footytipping].[dbo].[results]
			   ([res_rowid]
			   ,[res_roundrowid]
			   ,[res_winner]
			   )
		 VALUES
			   ($resRowid
			   ,$resRoundRowid 
			   ,$resWinner 
			   )"
			   );
			   
			   if ($bool)
			   echo 'Selection submitted successfully! </br>';
			   else
			   echo 'something went wrong </br>';
			   
			   echo odbc_errormsg($this->conn) . '</br>';
			}
				else echo "[insertIntoResults] odbc not connected";

}


}



?>