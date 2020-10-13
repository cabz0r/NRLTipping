<?php

include_once 'DataLayer.php';


//get page contents from NRL site (www.nrl.com) - needs to be updated manually by getting the HTML from the DRAWs page and saving it to draw.html on local web server (C:\Apache2_2\htdocs\FootyTipping\2018\draw.html)
$url = "draw.html";
$input = @file_get_contents($url) or die ("Could not access file: $url");
$regexTeams = '&quot;nickName&quot;:&quot;(.*)&quot;,&quot;odds&quot;'; //not used for regex_results
$regexRound = ':&quot;/draw/nrl-premiership/2018/round-(.*)/(.*)/&quot;,&quot;ticketsUrl&'; //used to get the current round



$url2 = "results.html";
$inputResults = @file_get_contents($url2) or die ("could not access file: $url2"); 
$regexResults = '&quot;score&quot;:(.*),&quot;teamPosition'; //used to get the scored for matches completed
$regexGameTime = '&quot;gameTime&quot;:&quot;80:00&quot;}}'; //used to count which matches have been completed in their entirety for the round


//pull round from NRL html (draw.html)

if(preg_match_all("~$regexRound~siU",$inputResults,$matchRound,PREG_SET_ORDER))
{
//var_dump($matchRound);
$round = $matchRound[0][1];
}

//$round = 10;


$dl = new DataLayer();
$dl->connect();
$year = $dl->getYear();



//get rowids for current round from database: store as array
$rowids = $dl->getRowIdsByRound($round,$year);

echo 'dbo.results table: </br>';
for($i=0;$i<sizeof($rowids,0);$i+=3){
echo 'key='. $i .', res_rowid=' . $rowids[$i] . ', [teamAScore]=' . $rowids[$i+1] . ' vs ' . $rowids[$i+2] . '=[teamBScore] </br>';
}
//var_dump($rowids);

if (sizeof($rowids,0) > 0){ //if current round returns associated round rowids
//get the highest res_rowid which has a res_winner = NULL from database

echo "</br></br>";
//$minRowid = $dl->getMinRowIdWhereWinnerIsNULL($round,$year);
$currentRowid = $dl->getMinRowIdWhereWinnerIsNULL($round,$year);
//$maxRowid = $dl->getMaxRowIdWhereWinnerIsNULL($round,$year);
//echo $minRowid . "</br>";
//echo $maxRowid . "</br>";

//search array for res_rowid and return index from array
$currentRowidKey = array_search($currentRowid, $rowids);
echo 'CurrentRowid Key = ' . $currentRowidKey;

echo '</br></br>';
echo 'Scores/Results pulled from regex search of NRL page: ('.$url2.') </br>';
//get scores from results.html file



if(preg_match_all("~$regexGameTime~siU",$inputResults,$numberOfGamesFullTime,PREG_SET_ORDER))
{
$gamesPlayedOffset = sizeof($numberOfGamesFullTime,0);
//echo 'gamesPlayedOffset: ' . $gamesPlayedOffset . '</br></br>';
}

if(preg_match_all("~$regexResults~siU",$inputResults,$scores,PREG_SET_ORDER))
{
echo 'SCORES</br>';
var_dump($scores);

echo '</br></br> ROWIDS </br>';

var_dump($rowids);

$numberOfGamesAlreadyEntered = $rowids[$currentRowidKey] - $rowids[0];

echo '</br></br>';

//echo 'gamesPlayedOffset: ' . $gamesPlayedOffset-$numberOfGamesAlreadyEntered . '</br></br>';
$GamesToBeEntered = $gamesPlayedOffset-$numberOfGamesAlreadyEntered;
echo 'GamesToBeEntered ' . $GamesToBeEntered . '</br>';
echo 'numberOfGamesAlreadyEntered ' . $numberOfGamesAlreadyEntered . '</br>';

	$j = $numberOfGamesAlreadyEntered*3; //set value for J = key so that correct selections of teams can occur in below loop
	for($i=$numberOfGamesAlreadyEntered;$i<$GamesToBeEntered;$i++) //initialise $i = value of key to insert correct winner dependent on how many games have already been played.
	{
		
		if ($scores[$i*2][1] > $scores[$i*2+1][1])
			{echo "exec sp_insertIntoResults " . $rowids[$j] . "," . $rowids[$j]  . "," . $rowids[$j+1] . " </br>";
			} 
		else
			{echo "exec sp_insertIntoResults " . $rowids[$j]  . "," . $rowids[$j]  . "," . $rowids[$j+2] . " </br>";
			} 
		$j+=3;
		//echo 'i='.$i. ', j=' .$j . '</br>';
		
	}
}
echo '</br></br>';
echo '<a href="regex_results_submit_1.php">SUBMIT</a>';
}
Else
{
echo 'no results to pull from (' . $url2 . ')';
}

?>

	

