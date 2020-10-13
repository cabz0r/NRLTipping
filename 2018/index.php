<html>
<head>

<link rel="stylesheet" type="text/css"  href="./css/style.css" >

<script type="text/javascript">

function loadResults() {

var eUsr = document.getElementById("user");
var strUsr = '';
strUsr = eUsr.value;

	if (strUsr != 'username') //if string has been changed from the default drop down selection <select user>, proceed.
	{
		var eSel = document.getElementsByTagName("select");
		var strSel = '';
		for ( var i = 0; i < eSel.length-1; i++ )
		{
			//get selections for each match according to specific round ID and team ID selected.
			//round ID = value between 1-8 multiplied by current round (e.g. 1, 2, 3 etc).
			strSel = strSel + eSel[i].id + eSel[i].value + ',';
		}

		//add selections and user into single string to be passed to processing page to be input in to "picks" table.
		var strSelUsr = strSel + strUsr;
		//alert(strSelUsr);

		var xhttp = new XMLHttpRequest();

		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
			  document.getElementById("results").innerHTML = xhttp.responseText;
			}
		  };

	  //window.alert("post.php?strSelUsr="+strSelUsr);
	  
	  xhttp.open("GET", "post.php?strSelUsr="+strSelUsr, true);
	  xhttp.send();
	}
	else //if strUsr variable is assigned the literal string "username" from the element eUsr.value drop down.
	{
		alert('Select a username before submitting.');
	}
  
}


</script>

</head>



<body>
<div id="results" >

<form id="round" >
<table id="main" class="opacity">

<?php 
//populate both listbox elements
include_once 'DataLayer.php';

$dl = new DataLayer();

$current_round = $dl->getRound();
$year = $dl->getYear();
$last_round = 0;

$current_round == 1 ? $last_round = $current_round : $last_round = $current_round - 1;

$dl->connect();
$dl->getTeamsByRound($current_round,$year);
//$dl->getLastWeeksWinner();
?>

<tr><td align="middle" colspan="4" class="rnd">USERNAME: 
<?php 
$dl->getUsers(); 
$dl->disconnect();
?>
</td></tr>
<tr><td></td><td></td></tr>
</table>

</form>

<button class="btn_blue" type="button" onClick="loadResults()"  value="submit" style="width:200px;">Submit Picks</button><br />
<a href="reviewCurrentRoundTips.php"><button class="btn_blue" type="button" style="width:200px;">Review Round Tips</button></a><br />
<a href="leaderboard.php"><button class="btn_blue" type="button" style="width:200px;">Leaderboard Results</button></a><br />
<a href="scorebylastround.php"><button class="btn_blue" type="button" style="width:200px;">Recent Round Scores</button></a><br />

</div><!--end results div -->



</body>
</html>