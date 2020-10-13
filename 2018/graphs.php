<?php

include_once 'variables.php';


include_once 'variables.php';

$var = new variables();
$conn = odbc_connect($var->getODBC(),$var->getUser(),$var->getPass());



IF ($conn)
{

	$resultQuery = "exec sp_GetUserRoundScoresForGraph " . $var->getYear();
	$result=odbc_exec($conn, $resultQuery);
	$colNum = odbc_num_fields($result);
	
	$count = 1;
	
	WHILE(odbc_fetch_row($result))
	{
		if ($count==1){
			echo odbc_result($result,1) . '<br/><br/>'; //echo user
		}
		
		
		if ($count==odbc_result($result,2)){		
		echo "round: " . odbc_result($result,2);
		
		ECHO '<div style="padding:13px; border-style:solid; border-width:3px; border-color:white; background-color:red; float:left; width:0px; height:' . odbc_result($result,3) * 20 . 'px;" class="bargraph">' .odbc_result($result,3) .'</div>';
		//<div style="float:left;">' . odbc_result($result,2).'</div>';
		}
		
		if ($count == $var->getRound())
		{
			$count = 0;
			echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
		}
	
		$count ++;
	}
	
}
else
{
	echo 'not connected to database server';
}

?>