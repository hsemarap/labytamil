<?php
if(!defined('LOGGEDIN'))
{ 
	header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
	echo "<h1>403 Forbidden<h1><h4>You are not authorized to access the page.</h4>";
	echo '<hr/>'.$_SERVER['SERVER_SIGNATURE'];
	exit(1);
}
session_start();
mysql_connect("localhost","paramesh","pass");
mysql_select_db("labyrinth");
$level=0;

$_SESSION['details']=1;
		$_SESSION['userid']="para";
		$_SESSION['name']="mesh";
		$_SESSION['email']="adsf";
		$_SESSION['type']="fb";
		//$_SESSION['level']=0;
		$_SESSION['try0']=0;$_SESSION['try1']=0;$_SESSION['try2']=0;$_SESSION['try3']=0;$_SESSION['try4']=0;$_SESSION['try5']=0;$_SESSION['try6']=0;$_SESSION['try7']=0;$_SESSION['try8']=0;


if(!isset($_SESSION['details'])){
	$res=mysql_query("SELECT * FROM `tamil_users` WHERE `id` = '$userid'");
	while($row=mysql_fetch_assoc($res)){
		$_SESSION['details']=1;
		$_SESSION['userid']=$row['id'];
		$_SESSION['name']=$row['name'];
		$_SESSION['email']=$row['email'];
		$_SESSION['type']=$row['type'];
		$_SESSION['level']=$row['level'];
		$_SESSION['try0']=$row['0'];$_SESSION['try1']=$row['1'];$_SESSION['try2']=$row['2'];$_SESSION['try3']=$row['3'];$_SESSION['try4']=$row['4'];$_SESSION['try5']=$row['5'];$_SESSION['try6']=$row['6'];$_SESSION['try7']=$row['7'];$_SESSION['try8']=$row['8'];
	}
}else {
		$name=$_SESSION['name'];
		$email=$_SESSION['email'];
		$type=$_SESSION['type'];
		$level=$_SESSION['level'];
		$try0=$_SESSION['try0'];$try1=$_SESSION['try1'];$try2=$_SESSION['try2'];$try3=$_SESSION['try3'];$try4=$_SESSION['try4'];$try5=$_SESSION['try5'];$try6=$_SESSION['try6'];$try7=$_SESSION['try7'];$try8=$_SESSION['try8'];	
}
if(isset($_SESSION['level']))$level=$_SESSION['level'];
else $_SESSION['level']=$level;
print_r($_SESSION);
if(isset($_SESSION['details']))$userid=$_SESSION['userid'];else $userid=$_SESSION['userId'];
if($level==0)$levelstring="பயிற்சி";
if($level==1)$levelstring="முதல்";
if($level==2)$levelstring="இரண்டாம்";
if($level==3)$levelstring="மூன்றாம்";
if($level==4)$levelstring="நான்காம்";
if($level==5)$levelstring="ஐந்தாம்";
if($level==6)$levelstring="ஆறாம்";
if($level==7)$levelstring="ஏழாம்";
if(!isset($_POST['answer']))
{

	$res=mysql_query("SELECT * FROM `tamil_questions` where `level` = '$level'");
	while($row=mysql_fetch_assoc($res)){
		$question=$row['question'];$images=$row['images'];
	}
	$images=explode(',',$images);
	$imagelist="<tr><td style='text-align:center'>";
	if(count($images)==1)$imagelist.= "<img src='upload/$images[0]' style='float:center;margin-left:20px;height:350'/>";	
	if(count($images)==2)foreach($images as $i)$imagelist.= "<img src='upload/$i' style='float:left;margin-left:20px;height:300'/>";	
	if(count($images)==3)foreach($images as $i)$imagelist.= "<img src='upload/$i' style='float:left;margin-left:20px;height:250'/>";	
	$imagelist.="</td></tr>";
	$html=<<<HTML
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<table style="padding:15px;border:1px solid grey;width:95%;">
<tr><td><h2>$levelstring பாகம்</h2></td><td></td><td></td></tr>
<tr><td colspan='3' style='text-align:center'><h2>$question</h2></td></tr>
$imagelist
<tr style='padding-top:50px;'><td colspan='3' style='text-align:center'>
<form action='./' method='POST'>
<label>	
உங்கள் பதில் ?</label><input type='text' name='answer'>
<input type='submit' name='submit' value='submit'>
</form>
</td></tr>
</table>
</html>
HTML;
	echo $html;
}else{
	//mysql_query("INSERT INTO `tamil_questions`(`answer`) VALUES('$_POST[answer]')");
	$res=mysql_query("SELECT * FROM `tamil_users` WHERE `id` = '$userid'");
	while($row=mysql_fetch_assoc($res)){
		$userid=$row['id'];
		$name=$row['name'];
		$email=$row['email'];
		$type=$row['type'];
		$level=$row['level'];
		$try0=$row['0'];$try1=$row['1'];$try2=$row['2'];$try3=$row['3'];$try4=$row['4'];$try5=$row['5'];$try6=$row['6'];$try7=$row['7'];$try8=$row['8'];
	}
	$res=mysql_query("SELECT * FROM `tamil_questions` WHERE `level` = '$level' AND `answer` = '".mysql_real_escape_string($_POST[answer])."'");
	if(mysql_error())die(mysql_error());
	echo "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
</head><div style='position:absolute;left:30%;top:30%'><h1>";
	if(mysql_num_rows($res)>0){
		echo "$_POST[answer] என்பது  சரியான பதில்.";
		$_SESSION['level']=$level+1;

		mysql_query("UPDATE `tamil_users` SET `level` = '".($level+1)."' AND `$level` = '".(${"try$level"}+1)."' WHERE `name` = '$name' ");

	}
	else {
		echo "தவறான பதில். முயற்சி திருவினை ஆக்கும்.மீண்டும் முயற்சி செய் ";
		mysql_query("UPDATE `tamil_users` SET `$level` = '".(${"try$level"}+1)."' WHERE `name` = '$name' ");
	}
//	echo $_POST['answer'].($_POST['answer']);
	echo "</h1></div></html>";
}
?>
