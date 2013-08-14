<?php
if(0)
{ 
	header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
	echo "<h1>403 Forbidden<h1><h4>You are not authorized to access the page.</h4>";
	echo '<hr/>'.$_SERVER['SERVER_SIGNATURE'];
	exit(1);
}
session_start();
header( 'Content-Type: text/html; charset=utf-8' ); 
$con=mysql_connect("localhost","paramesh","pass");
//mysql_set_charset(‘utf8′,$con);
mysql_select_db("labyrinth");

$user="admin";
if($user!="admin")die("You Do Not Have Permissions for this page. Only the Admin can view this page.");

if(sizeof($_POST)==0 && sizeof($_GET)==0)
{
$res=mysql_query("SELECT * FROM `tamil_questions`");
	$html=<<<HTML
<html>
<head>
<title>தமிழ் மன்றம்| படம் பார்த்து பதில் சொல் </title>
<script type='text/javascript' src='jquery.js'></script>
<script type='text/javascript' src='upload.js'></script>
<script>
$(function(){
$("#uploadimage").upload({
    name: 'image',
    method: 'post',
    action: './questions.php',
    enctype: 'multipart/form-data',
    params: {
      path: 'upload/',
      file: 'file'
    },
    autoSubmit: true,
    onSubmit: function() {
      //alert('onSubmit');
    },
    onSelect: function() {
      //alert('onSelect');
    },
    onComplete: function(data) {
      $("#message-upload").text(data);
      $("#message-upload").css("visibility","visible");
    }
  });
});
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<br />
<br />
<br />
<form action='./questions.php' enctype='multipart/form-data' method='POST' style='float:right;margin-right:100px;'>
<h2  style='pointer:cursor;color:blue'>Upload Images</h2>
<button id='uploadimage'>Click</button>
<!--<input type='file' name='image'>
<input type='submit' name='submit' value='submit'>-->
<span id='message-upload'></span>
</form>
<form action='./questions.php' method='POST' style='float:right;margin-right:40px;'>
<h2>Add Question</h2>
<input type='hidden' name='newquestion' value='submit'>
<input type='text' name='level' placeholder='Level'><br />
<label for='newques'>Question:</label><br />
<textarea id='newques' style='height:100px;width:300px' name='question'></textarea><br />
<input type='text' name='image1' placeholder='Image 1'><br />
<input type='text' name='image2' placeholder='Image 2'><br />
<input type='text' name='image3' placeholder='Image 3'><br />
<input type='text' name='answer' placeholder='Answer'><br />
<input type='submit' name='submit' value='submit'>
</form>
HTML;
if(mysql_num_rows($res)==0)$html.="No questions added yet";
else while($row=mysql_fetch_assoc($res)){
	$text=$row['question'];
	$level=$row['level'];
	$images=explode(',',$row['images']);
	foreach($images as $i)
	{$img[]="value='$i'";$src[]="$i";}
	$answer=$row['answer'];
$html.="
<form action='./questions.php' method='POST' style='display:inline;float:left;margin-left:40px;'>
<h2>Edit Question for Level $level</h2>
<label for='newques'>Text:</label><br />
<input type='hidden' name='editquestion' value='$_POST[id]'>
<input type='hidden' name='level' value='$level'>
<textarea id='newques' style='height:100px;width:300px' name='question'>$text</textarea><br />
";
$fileselect1=$fileselect2=$fileselect3="<option value=''> </option>";
$files=array_diff(scandir("upload"),array(".",".."));
foreach($files as $f)
	{
		$fileselect1.="<option ".($src[0]==$f?" selected ":"")."value='$f'>$f</option>";
		$fileselect2.="<option ".($src[1]==$f?" selected ":"")."value='$f'>$f</option>";
		$fileselect3.="<option ".($src[2]==$f?" selected ":"")."value='$f'>$f</option>";
	}
$html.="
<select name='image1'>$fileselect1</select><img height='20' src='upload/$src[0]' />
<select name='image2'>$fileselect2</select><img height='20' src='upload/$src[1]' />
<select name='image3'>$fileselect3</select><img height='20' src='upload/$src[2]' /><br /><br />
<!--<input type='text' name='image1' placeholder='Image 1' $img[0]><img height='20' src='upload/$src[0]' /><br />
<input type='text' name='image2' placeholder='Image 2' $img[1]><br />
<input type='text' name='image3' placeholder='Image 3' $img[2]><br />-->
<input type='text' name='answer' placeholder='Answer' value='$answer'><br />
<input type='submit' name='submit' value='submit'>
</form>
";

}
$html.=<<<HTML
</body>
</html>
HTML;
	echo $html;
}else
if(isset($_POST['newquestion']))
{
	$level=$_POST['level'];if($level==""||$level<0)die("Please enter valid level");
	$res=mysql_query("SELECT * FROM `tamil_questions` WHERE `level` = '$level'");
	if(mysql_error())die(mysql_error());
	if(mysql_num_rows($res)>0)die("There is a question already set for level $level Please edit it.");
	$question=$_POST['question'];
	if($_POST['image1']!='')$images=$_POST['image1'];
	if($_POST['image2']!='')$images.=','.$_POST['image2'];
	if($_POST['image3']!='')$images.=','.$_POST['image3'];
	$answer=$_POST['answer'];
	$query="INSERT INTO `tamil_questions` VALUES('','$level','$question','$images','$answer')";
	//echo $query;
	$res=mysql_query($query);
	if(mysql_error())die(mysql_error());
	else echo "Added Successfully";
//	echo $_POST['answer'].($_POST['answer']);
}else
if(isset($_POST['editquestion']))
{
	$level=$_POST['level'];if($level==""||$level<0)die("Please enter valid level");
	$question=$_POST['question'];
	//print_r($_POST);
	if($_POST['image1']!='')$images=$_POST['image1'];
	if($_POST['image2']!='')$images.=','.$_POST['image2'];
	if($_POST['image3']!='')$images.=','.$_POST['image3'];
	$answer=$_POST['answer'];
//	$question=mb_convert_encoding($question, "utf-8");
//	$answer=mb_convert_encoding($answer, "utf-8");
	$query="UPDATE `tamil_questions` SET `question` = '$question' , `images` = '$images' , `answer` = '$answer' WHERE `level` = '$level'";
	$query="UPDATE `tamil_questions` SET `question` = '$question' , `images` = '$images' , `answer` = '$answer' WHERE `tamil_questions`.`level` = $level";
	//$query=mb_convert_encoding($query, "utf-8");
//	echo $query;
	//mysql_query("SET NAMES 'UTF8'");
	$res=mysql_query($query);
	if(mysql_error())die(mysql_error());
	else echo "Edited Successfully";
}else if(sizeof($_FILES)){
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["image"]["name"]);
$extension = end($temp);
  if ($_FILES["image"]["error"] > 0)echo "Return Code: " . $_FILES["image"]["error"] . "<br>";
  else
    {
    if (file_exists("upload/" . $_FILES["image"]["name"]))
      echo $_FILES["image"]["name"] . " already exists. ";
    else
      {
      move_uploaded_file($_FILES["image"]["tmp_name"],"upload/" . $_FILES["image"]["name"]);
      echo "Stored in: " . "upload/" . $_FILES["image"]["name"];
      chmod("upload/" . $_FILES["image"]["name"],0755);
      }
    }
}
?>
