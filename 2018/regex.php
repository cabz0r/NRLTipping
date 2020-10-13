<?php

include_once 'DataLayer.php';

//get page contents from NRL site (www.nrl.com) - needs to be updated manually by getting the HTML from the DRAWs page and saving it to draw.html on local web server (C:\Apache2_2\htdocs\FootyTipping\2018\draw.html)
$url = "draw.html";
$input = @file_get_contents($url) or die ("Could not access file: $url");
$regexTeams = '&quot;nickName&quot;:&quot;(.*)&quot;,&quot;odds&quot;';

$regexRound = ':&quot;/draw/nrl-premiership/2018/round-(.*)/(.*)/&quot;,&quot;ticketsUrl&';

$dl = new DataLayer();
//$round = $dl->getRound();


//pull round from NRL html (draw.html)
if(preg_match_all("~$regexRound~siU",$input,$matchRound,PREG_SET_ORDER))
{
//var_dump ($matchRound);
$round = $matchRound[0][1];
}



//search for pattern '&quot;nickName&quot;:&quot;(.*)&quot;,&quot;odds&quot;'  where &quot;nickName&quot;:&quot; is the first delimiter, and (.*) is 
//everything in between and &quot;,&quot;odds&quot; is the end delimiter.

echo 'Teams pulled from regex search of NRL page: </br>';
if(preg_match_all("~$regexTeams~siU",$input,$matches,PREG_SET_ORDER)){
	for($i=0;$i<sizeof($matches,0);$i++)
	{
		echo $matches[$i][1] . ' vs ' . $matches[$i+=1][1] . '</br>';
	}
}

$numberOfTeams = count($matches);
//echo $numberOfTeams;

/*
if ($input)
{
echo $matches[0][1] . ' vs ' . $matches[1][1] . '</br>'; //GET TEAMS
echo $matches[2][1] . ' vs ' . $matches[3][1] . '</br>'; //GET TEAMS
echo $matches[4][1] . ' vs ' . $matches[5][1] . '</br>'; //GET TEAMS
echo $matches[6][1] . ' vs ' . $matches[7][1] . '</br>'; //GET TEAMS
echo $matches[8][1] . ' vs ' . $matches[9][1] . '</br>'; //GET TEAMS
echo $matches[10][1] . ' vs ' . $matches[11][1] . '</br>'; //GET TEAMS
echo $matches[12][1] . ' vs ' . $matches[13][1] . '</br>'; //GET TEAMS
echo $matches[14][1] . ' vs ' . $matches[15][1] . '</br>'; //GET TEAM

echo $numberOfTeams . '</br>';

}
else
echo 'no teams matched </br>';
*/

$dl->connect();
$teams = $dl->getTeams(); //get teams and associated ID's from footytipping database.
	
echo '</br>';
echo 'Team ID\'s from Database: </br>';
var_dump($teams);
$map = array();
//$x = 0;

echo '</br></br>';

echo 'Mapping database IDs to NRL matches:</br>';
$j = 0; //iterator for "foreach" loop
foreach($teams as $team)
{
//echo $teams[$i] . '</br>';
	for ($i = 0; $i < $numberOfTeams*2; $i++){

		if ($teams[$j+1] === strtolower($matches[$i][1]))
		{
			echo 'MATCH FOUND! (database ID = '. $teams[$j] .' maps to matches['.$i.'][1] : '.$teams[$j+1].' and '.$matches[$i][1].'</br>';
			array_push($map,$i, $teams[$j], $matches[$i][1]);
		}
	}
	$j+=2;
}

//echo '</br>';
//var_dump($map);

echo '</br></br>';

echo 'Current Round: ' . $round . '</br>';

$round_rowid = $dl->GetLatestRoundRowID();
$yearrowid = $dl->getyearrowid();
$year = $dl->getYear();

echo 'VALUES TO BE SUBMITTED IN TO DB, BELOW:</br>';
for ($i = 0; $i < $numberOfTeams; $i+=2){

	$round_rowid++;
	$team1 = $map[array_search($i,$map, true) + 1];
	$team2 = $map[array_search($i+1,$map, true) + 1];
	
	echo $round_rowid . ',' . $team1 . ',' . $team2 . ',' . $round . ',' . $yearrowid . '</br>' ;
	//$dl->InsertTeamsByRound($rowid,$team1,$team2, $round, $year);
}

echo '</br>';
echo '<a href="regexsubmit_1.php">SUBMIT</a>';


?>

	

