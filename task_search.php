<?php
	//require 'db_connect.php';

    $colNames = array( ); 			# array to hold returned column names
    $data     = array( );			# 2dim array to hold returned data

    read_param( );				#read data from the query String
    execute_sql( );				#execute the SQL 
    send_result( );				#send the results using either XML or JSON

//==================================================================
function read_param( )
{
      global $searchStr, $output;

      $searchStr = $_GET['search_text']; 			#get HTML form param fields
      $output    = $_GET['output']; 				#using the GET method
}
//==================================================================
function execute_sql( )
{
      global $searchStr, $output, $data, $error;

      if (! $searchStr  or  ! $output)						# if nor provided
      {
	$error='Enter URL?name=...&output=XML|JSON';
	return;
      }

      $connect = mysqli_connect("p:localhost","ricardo","ricardo","ricardo");    #persist connection
      if (! $connect)
      {
	$error = 'Could not connect: ' . mysqli_connect_error( );
	return;
      }

      $sql = "select task_code, task_desc
      	  from nr_task_codes  
	      where    task_desc like '%$searchStr%' 
	      order by task_code";    

      $cursor = mysqli_query($connect, $sql); 			#execute the query
      if (! $cursor)
     {
	$error = 'Could not execute query: ' .  mysqli_error($connect);
	return;
      }

      $i=0;
      while ($row = mysqli_fetch_row($cursor)) 		           #loop thru rows
	$data[$i++] = $row;					           #store row in 2 dim data array

      mysqli_free_result($cursor); 				           #free result buffer
}
//==================================================================
function send_result( )
{
      global $data, $output, $error;

       if (! $output)  							#if not provided
      {
	header("Content-type: text/html");

	print "<h3> $error </h3> \n";					#display error message
      }

       //------for XML---------------------------------------------------------------------------------------

      if ($output=='XML' || $output=='xml')				#if requesting XML 
      {
	header("Content-type: text/xml");
	print "<?xml version='1.0'?> \n";
	print "<sqlData>\n";

	foreach($data as $row)					#loop thru the rows
	{
	      $task_code = $row[0];						#first column
	      $task_desc   = $row[1];					#second column

	      print "\t<row>\n";
	      print "\t\t<task_code>$task_code</task_code>\n";
	      print "\t\t<task_desc>$task_desc</task_desc>\n";
	      print "\t</row>\n";
	}
	print "</sqlData>\n";
     }


    //-----For JSON------------------------------------------------------------------------------------------

      if ($output=='JSON' || $output=='json')				#if requesting JSON 
      {
	header("Content-type: application/json");

	$jsonString =  "[\n";

	foreach($data as $row)					#loop thru the rows
	{
	      $task_code = $row[0];						#first column
	      $task_desc   = $row[1];					#second column

	      $jsonString .= "\t{";

	      $jsonString .= "\"task_code\":\"$task_code\", ";
	      $jsonString .= "\"task_desc\":\"$task_desc\"";

	      $jsonString .= "},\n";
	}

	$jsonString = preg_replace("/,\n$/"," \n", $jsonString);   	#strip off the last comma 

	$jsonString .=  "]\n";

	print $jsonString;
       }
}

?>