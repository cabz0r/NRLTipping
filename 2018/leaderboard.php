<html>

<head>
	<link rel="stylesheet" type="text/css"  href="./css/style.css" />
</head>

<body>
<div class="center1">
<?php

//THIS FILE GENERATES THE HTML CODE FOR AN AJAX RESPONSE 
//TO DISPLAY VERSIONS AND THE VERSIONS THEY WERE BUILT FROM.

include_once 'variables.php';

$var = new variables();
$conn = odbc_connect($var->getODBC(),$var->getUser(),$var->getPass());

IF ($conn)
{
$resultQuery = "exec sp_GetUserScoreByYear " . $var->getYear();
		//perform the query
		$result=odbc_exec($conn, $resultQuery);
		
		$colNum = odbc_num_fields($result);
		$rowNum = odbc_num_rows($result);
		
		echo '<div class="logo"><img src="2015_NRL_Logo_sml.png" /></div>';
		echo '<h3>NRL Telstra Premiership Leaderboard ' . $var->getYear() .'</h3>';
		echo '<div class="lbcenter">';
		echo '<table class="lbcenter">';
		
			FOR ($j=1; $j<= $colNum; $j++)
			{ 
				echo "<th>" . odbc_field_name ($result, $j ) . "</th>";
			}
			
			WHILE(odbc_fetch_row($result))
			{
				echo '<tr>';
				
				for($i=1; $i<=$colNum; $i++)
				{
					if ($i==1)
						echo '<td class="lbuser">'. odbc_result($result,$i). '</td>';
					else
						echo '<td class="lbscore">'. odbc_result($result,$i). '</td>';
				}
				echo '</tr>';
			}
		//echo '<tr><td>&nbsp</td></tr>';
		echo '</table>';
		echo '</div><br /> <!--end leaderboard class-->';
		echo '<a href="index.php"><button class="btn_blue" style="width:100%;">Return</button></a>';


}
else
echo "odbc not connected";

//close the connection
odbc_close ($conn);
?>
</div><!-- end center class -->
</body>
</html>