<html>

<head>
	<link rel="stylesheet" type="text/css"  href="./css/style.css" />
</head>

<body>
<div class="center1">

<?php

include_once 'variables.php';

$var = new variables();
$conn = odbc_connect($var->getODBC(),$var->getUser(),$var->getPass());
$numberOfGames = $var->getNumberOfGames();

IF ($conn)
{

		
		$resultQuery = "exec sp_GetUserPicks " . $var->getRound() . ',' . $var->getYear();
		//perform the query to get the results of users submission; then use for display a bit further down as a summary of their picks.
		$result=odbc_exec($conn, $resultQuery);
	
		
		//get number of a columns and rows returned
		$colNum = odbc_num_fields($result);
		$rowNum = odbc_num_rows($result);
		
		//echo $colNum .'<br>';
		//echo $rowNum.'<br>';
		
		echo '<table class="lbcenter" cellspacing="0" cellpadding="0">';
		
			//loop through columns to get headers from the results
			FOR ($j=3; $j<= $colNum; $j++)
			{ 
				echo "<th>" . odbc_field_name ($result, $j ) . "</th>";
			}

			$count = 1; //counts the number of times the while loop has been executed.
			
			WHILE(odbc_fetch_row($result))
			{
				//$rows = 1;
				$selectedUserID = odbc_result($result,1);
				$userWhoAlreadyTippedID = $p_userrowid;
				
				//echo 'selectedUserID ' . $selectedUserID . '</br>';
				//echo 'username ' . odbc_result($result,2) . '</br>';
				

				//DO HEADINGS/DIVIDERS FOR EACH USERS PICKS DETERMINED BY NUMBER OF GAMES PLAYED FOR THE ROUND
				if($numberOfGames == 1){
					if ($count==1 || $count==2 || $count==3 || $count==4 || $count==5 || $count==6 || $count==7 || $count==8){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				
				ELSE if($numberOfGames == 2){
					if ($count==1 || $count==3 || $count==5 || $count==7 || $count==9 || $count==11 || $count==13 || $count==15){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				
				ELSE if($numberOfGames == 4){
					if ($count==1 || $count==5 || $count==9 || $count==13 || $count==17 || $count==21 || $count==25 || $count==29){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				
				ELSE if($numberOfGames == 6){
					if ($count==1 || $count==7 || $count==13|| $count==19 || $count==25 || $count==31 || $count==37 || $count==43){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				
				ELSE if($numberOfGames == 7){
					if ($count==1 || $count==8 || $count==15 || $count==22 || $count==29 || $count==36 || $count==44 || $count==51){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				ELSE{ //if full 8 games being played this round
					if ($count==1 || $count==9 || $count==17 || $count==25 || $count==33 || $count==41 || $count==49 || $count==57){
					echo '<tr><td colspan="5" class="user">'.odbc_result($result,2).'</td></tr>';
					}
				}
				
				//BEGIN TABLE ROW AND DETERMINE HIGHLIGHTING FOR SELECTED USER
				if ($selectedUserID == $userWhoAlreadyTippedID)
				{echo '<tr class="userpicks">';} ELSE {echo '<tr class="lbuser">';}
				
				for($i=3; $i<=$colNum; $i++)
				{echo '<td>'. odbc_result($result,$i) . '</td>';} //TD DETAILS
				
				echo '</tr>'; //END TABLE ROW
				
				//echo $rows;
				//$rows ++;
				$count ++;
			}
		echo '</table> </br>';
		echo '<a href="index.php"><button class="btn_blue" style="width:100%;">Return</button></a>';
		}
		ELSE 
			echo "odbc not connected";
			
//close the connection to database
odbc_close ($conn);
		
?>

</div><!-- end center1 class -->
</body>
</html>