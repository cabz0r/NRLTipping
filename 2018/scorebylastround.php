<html>

<head>
	<link rel="stylesheet" type="text/css"  href="./css/style.css" />
</head>

<body>
<div class="center1">
<?php

//THIS FILE GENERATES THE HTML CODE FOR AN AJAX RESPONSE 
//TO DISPLAY THE PICKS THE USERS HAVE MADE FOR THE SELECTED ROUND SET IN VARIABLES.PHP

include_once 'variables.php';

$var = new variables();
$conn = odbc_connect($var->getODBC(),$var->getUser(),$var->getPass());

IF ($conn)
{
$resultQuery = "exec sp_GetUserScoresByRound 0," . $var->getYear();
		//perform the query
		$result=odbc_exec($conn, $resultQuery);
		
		$colNum = odbc_num_fields($result);
		$rowNum = odbc_num_rows($result);
		$round = odbc_result($result,2);
		

		
		echo '<div class="logo">';
		echo '<h3>Round ' .$round . ' Scores</h3></div><br />'; //End LOGO div
		echo '<div class="lbcenter">';
		echo '<table class="lbcenter">';
		
			FOR ($j=1; $j<= $colNum; $j+=2) //+2 to exclude "r_round" column returned from SQL result set from being output
			{ 
				//print column header from SQL results to HTML table header <th> 
				echo '<th style="width:50%">' . odbc_field_name ($result, $j ) . '</th>';
			}
			
			odbc_fetch_row($result,0); //get first row (position 0): returns result set detail values. Needs to be explicitly called otherwise a single row is left out of the results.
			
			echo '<tr>';
			//print column data from SQL results to HTML table cells
			for($i=1; $i<=$colNum; $i+=2) //+2 to exclude "r_round" column returned from SQL result set from being output
			{
					echo '<td class="lbuser">'. odbc_result($result,$i). '</td>';
			}
			echo '</tr>';
			
				
			WHILE(odbc_fetch_row($result)) //get remaining rows (position 1 onwards): returns result set detail values.
			{
				echo '<tr>';
				//print column data from SQL results to HTML table cells
				for($i=1; $i<=$colNum; $i+=2) //+2 to exclude "r_round" column returned from SQL result set from being output
				{
						echo '<td class="lbuser">'. odbc_result($result,$i). '</td>';
				}
				echo '</tr>';
			}
		//echo '<tr><td>&nbsp</td></tr>';
		echo '</table>';
		echo '</div><br /> <!--end leaderboard class-->';
		echo '<a href="index.php"><button class="btn_blue" style="width:100%;">Return</button></a>';
}
ELSE
echo "odbc not connected";

//close the connection
odbc_close ($conn);
?>
</div><!-- end center class -->
</body>
</html>