<?
session_start(); 
require_once "classes/queries.php";

    $select = new Query();
    $select->openConnect("root","1234", "khpk");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <title>Складання розкладу</title>
 </head>
 <body>
	<div class = "wrapper">

		<h1 id='nav'>Навчальні роки</h1>	
		<table id='years'>
		<tr>
			<th colspan='2'>Семестри</th>
		</tr>
		<tr>
			<th id='zag_sem'>Осінній</th>
			<th id='zag_sem'>Весняний</th>
		</tr>
		<? $q = $select->selectQuery(array("*"), "study_year");
			while ($us = mysql_fetch_array($q)) { ?>
				<tr> 
					<td width='50px'><a href="create.php?id=<?=$us['id']?>&sem=fill"><?=$us['begin']?></a></td>
					<td><a href="create.php?id=<?=$us['id']?>&sem=spring"><?=$us['end']?></a></td>
				</tr>
			<? } mysql_close(); ?>
			</table>
	</div>
	</body>
</html>