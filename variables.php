<?php

class variables{

private $round = 11;
private $year = 2018;
private $yearrowid = 2;
private $numberOfGamesThisRound = 8; //this is normally set to 8, but does change to a lower number when origin falls on the current week and when it gets to the finals (either 1, 2, 4 or 7 rounds).
private $ODBC_name = "tipping"; //name of ODBC connection set in ODBCAD32.exe (must be set to SQL auth)
private $user="pxsupport"; //SSMS user login (Under Security > Logins)
private $pw="pxsupport"; //SSMS users associated password (set under security > logins > user's properties).

//	ODBC connection string details
//	SERVER: D-113067874\SQL2008_R2L1
//	DB:		footytiping


public function getUser(){
return $this->user;
}

public function getPass(){
return $this->pw;
}

public function getODBC(){
return $this->ODBC_name;
}

public function getRound(){
return $this->round;
}

public function getYear(){
return $this->year;
}

public function getYearRowid(){
return $this->yearrowid;
}

public function getNumberOfGames(){
return $this->numberOfGamesThisRound;
}

}

?>