<?php
//========================================================================
// File: dittoFrontEnd.php
// Author: Benny Saxén
// Date: 2018-11-29
//========================================================================

//========================================================================
// Library
//========================================================================
//========================================================================
function update_thing($thingid,$value,$path)
//========================================================================
{
  $curl1 = "curl -u ditto:ditto -X PUT -d '$value' ";
  $curl2 = "'http://localhost:8080/api/1/things/$thingid/$path'";
  $curl = $curl1.$curl2;
  echo $curl;
  system($curl);
}
//========================================================================
function delete_thing($thingid)
//========================================================================
{
  $curl1 = "curl -u ditto:ditto -X DELETE ";
  $curl2 = " 'http://localhost:8080/api/1/things/$thingid'";
  $curl = $curl1.$curl2;
  //echo "$curl<br>";
  system($curl);
}
//========================================================================
function create_thing($thingid,$json)
//========================================================================
{
  $curl1 = "curl -u ditto:ditto -X PUT -d '{";
  $curl2 = "}' 'http://localhost:8080/api/1/things/$thingid'";
  $curl = $curl1.$json.$curl2;
  //echo $curl;
  system($curl);
}
//========================================================================
function list_all_things()
//========================================================================
{
$inp = file_get_contents("http://ditto:ditto@localhost:8080/api/1/search/things?fields=thingId");
$jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($inp, TRUE)),RecursiveIteratorIterator::SELF_FIRST);

echo("<table border=1>");
foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "<tr><td>$key</td>";
    } else {
        //echo "     $key = $val";
        if($val != -1)echo "<td><a href=\"dittoFrontEnd.php?do=list_thing&thingid=$val\">$val</a></td><tr>";
    }
 }
 echo "</table>";
}
//========================================================================
function list_thing($thingid)
//========================================================================
{
  echo "<a href=\"dittoFrontEnd.php?do=hide_thing\">Hide</a>";
  $url = "http://ditto:ditto@localhost:8080/api/1/things/$thingid";
  //echo $url;
  $inp = file_get_contents($url);
  $jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($inp, TRUE)),RecursiveIteratorIterator::SELF_FIRST);

  echo("<table border=1>");
  foreach ($jsonIterator as $key => $val) 
  {
    if(is_array($val)) 
    {
        echo "<tr><td><b>$key</b></td><tr>";
    } else 
    {
        echo "<tr><td><font color=\"green\">$key</font></td><td>$val</td></tr> ";
    }
  }
  echo "</table>";
  echo "<font color=\"red\">Careful! </font><a href=\"dittoFrontEnd.php?do=delete_thing&thingid=$thingid\">Delete Thing</a>";
}

//========================================================================
// Logic
//========================================================================
if (isset($_GET['do']))
{
  $do = $_GET['do'];
  //echo $do;

  if ($do == 'list_all_things')
  {
    $enable_list_all_things = 1;
  }

  if ($do == 'list_thing')
  {
    $thingid = $_GET['thingid'];
    $enable_list_thing = 1;
  }

  if ($do == 'delete_thing')
  {
    $thingid = $_GET['thingid'];
    delete_thing($thingid);
  }

}

if (isset($_POST['do']))
{
  $do = $_POST['do'];

  if ($do == 'create_thing')
  {
    $thingid = $_POST['thingid'];
    $json = $_POST['json'];
    create_thing($thingid,$json);
  }

  if ($do == 'update_thing')
  {
    $thingid = $_POST['thingid'];
    $path = $_POST['path'];
    $value = $_POST['value'];
    update_thing($thingid,$value,$path);
  }

}
//========================================================================
// Presentation
//========================================================================
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><head>";
echo "</head>";
echo "<body>";

echo "<a href=dittoFrontEnd.php?do=list_all_things>list all things</a><br>";
echo "
 <table border=1>
 <form action=\"#\" method=\"post\">
   <input type=\"hidden\" name=\"do\" value=\"create_thing\">
   <tr><td>Thing Id</td><td><input type=\"text\" name=\"thingid\" value=\"$thingid\" size=\"40\"></td></tr>
   <tr><td>JSON</td><td><textarea id=\"story\" name=\"json\" rows=\"5\" cols=\"60\"></textarea></td></tr>
   <tr><td><input type= \"submit\" value=\"Create Thing\"></td><td></td></tr>
 </form></table>

  <table border=1>
  <form action=\"#\" method=\"post\">
    <input type=\"hidden\" name=\"do\" value=\"update_thing\">
    <tr><td>Thing Id</td><td><input type=\"text\" name=\"thingid\" value=\"$thingid\" size=\"40\"></td></tr>
    <tr><td>Attribute</td><td><input type=\"text\" name=\"path\" value=\"\" size=\"40\"></td></tr>
    <tr><td>Value</td><td><input type=\"text\" name=\"value\" value=\"\" size=\"20\"></td></tr>
    <tr><td><input type= \"submit\" value=\"Update Thing\"></td><td></td></tr>
  </form></table>

 ";

if ($enable_list_thing == 1)
{
  list_thing($thingid);
}
list_all_things();
 echo "</body>";

 //========================================================================
 // End of File
 //========================================================================
