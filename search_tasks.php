<?php 
$title_tag = 'Search Task Codes';

require 'page_header.php'; ?>
		<h4>Search for specific text within the task description:</h4>
		<div>
			<input type="text" id="search_text" onkeyup="doRequest()"></input>
			<input type="submit" value="Search" onclick="doRequest()"></input>
			<input type="hidden" id="output" value="XML"></input>
		</div>
		<br>
		<div id="divTable"></div>

<script src="ajax.js"> </script>
<script src="objConvert.js"> </script>
<script src="table.js"> </script>
<script src="dump.js"> </script>		<!-- optional -->
<script type="text/javascript">

  var url = "http://oit.scps.nyu.edu/~ricardon/project/task_search.php"	        //server process to call

//doRequest()

//=================================================================================================
// doRequest: process the request onLoad and when execute button is clicked
//=================================================================================================
function doRequest(method) {

	var param = "search_text=" + document.getElementById("search_text").value + "&output=" + document.getElementById("output").value;

	var callback = doResponse; //setup a callback function

	ajaxRequest(url, "GET", param, callback); //call ajax request

} //pass it the callback fucntion


//========================================================================================
// doResponse: receives the ajax response in all the following formats:
//             respText - the response as a text format. Also used for HTML 
//             respXML  - the response as an XML DOM object
//             respJSON - the response as a JSON object
//             respHeaders - All response headers as a single string 
//========================================================================================
function doResponse(respText, respXML, respJSON, respHeaders)
{
	var arrObj = dom2arrObj(respXML);			//convert DOM to an array of objects
	var array2 = arrObj2arr(arrObj);			//convert array of objects to 2 dim array

	var headers = array2.shift();		//pop off the 1st array as headers 
	var data    = array2;			//the rest is all the raw data

	Table(headers, data, 0, 'asc')		//call table.js 
 }

//==============================================================================================
</script>
	</body>
</html>