//=============================================================================================
// Table: A script that will display any table, and will allow you to sort the columns
//        output will be in a div tag with id='divTable' which must be provided by the client 
//        args:   headers   - an array containing the headers
//                data      - a 2 dimensional array containing the data
//                sortCol   - the initial number column to be sorted on (0 based)
//                sortOrder - asc or desc
//        return: none      - the table will be rendered in a div tag with id='divTable'              
//=============================================================================================
_col       = 0;			//global variable
_headers   = [];		//a global array to hold the headers
_data      = [];		//a global 2 dim array to hold the data
_colOrders = [];		//an array to hold the last order (asc/desc) for each column

//-------------------------------------------------------------------------------------------
//table: like a constructor.  It should only be called by the client
//-------------------------------------------------------------------------------------------
function Table(headers, data, sortCol, sortOrder)
{
    _headers = headers;				//copy incoming array to global array
    _data    = data;				//copy incoming array to global array					

    for (i=0; i<_headers.length; i++)
        _colOrders[i]  = 'asc';			//start all columns in asc order

    _colOrders[sortCol] = sortOrder;		//override with parameter received 

    sortIt(sortCol);				//sort the data based on requested col
}

//-------------------------------------------------------------------------------------------
//sortIt: called when the client clicks on a column header
//-------------------------------------------------------------------------------------------
function sortIt(sortCol)
{
    var sortOrder = _colOrders[sortCol];		//obtain the last order for that column

    sort(sortCol, sortOrder);				//sort the data based on requested col

    render(sortCol, sortOrder);				//render the table

    if ( _colOrders[sortCol] == 'asc')			//switch the order for that column
         _colOrders[sortCol] = 'desc';
    else 
        _colOrders[sortCol]  = 'asc';
}

//-----------------------------------------------------------------------------------------
//sort: prepare the 2 dimentional "data" array for sorting
//      sort the "data" array based on the column chosen
//-----------------------------------------------------------------------------------------
function sort(sortCol, sortOrder)
{						
    _col  = sortCol;					//which column to sort on

    if (sortOrder == 'asc')
        _data.sort(alphaAsc);				//sort the data ascending
    else
        _data.sort(alphaDesc);				//sort the data descending
}

//-------------------------------------------------------------------------------------------
//displayTable: sort and display a 2 dimentional array in an html table
//-------------------------------------------------------------------------------------------
function render(sortCol, sortOrder)
{

    content = '<table id=table1>';
              '   <tr>';

    for (col=0; col < _headers.length; col++)		//loop for as many columns
    {
	header = _headers[col];			 

	if (col == sortCol)
	{	
	    if (_colOrders[col] == 'desc')
		header += '<img src=up.gif border=0>';   	//add the down image for that column
	    else
		header += '<img src=down.gif border=0>'; 	//add the up image for that column
	}
        content += '<th><nobr>' + 
                   '<a href=javascript:sortIt(' + col + ')>' + header + '</a>' + 
                   '</nobr></th>' ;
    }
    
    for (row=0; row < _data.length; row++)		//loop for as many rows
    {
	content += '<tr>';

        for (col=0; col < _data[row].length; col++)
	    content += '<td><nobr>' + _data[row][col] + '&nbsp;</nobr></td>';  

	content += '</tr>';
    }
    content += '</table>';
  
    document.getElementById('divTable').innerHTML = content;	//place in div tag
}
 
//-----------------------------------------------------------------------------------------
//sort functions: sort a 2 dimentional array ascending or descending
//-----------------------------------------------------------------------------------------
function alphaAsc(a, b) 		//a & b are arrays
{
    a1 = a[_col].toLowerCase();	//translate value to lowercase
    b1 = b[_col].toLowerCase();	//so compare is case insensitive

    if (a1 < b1) return -1;		// return negative - Do not Flip elements
    if (a1 > b1) return  1;		// return positive - Flip elements
    return 0;				// Do nothing
}
function alphaDesc(a, b)
{
    a1 = a[_col].toLowerCase();	//translate value to lowercase
    b1 = b[_col].toLowerCase();	//so compare is case insensitive

    if (a1 < b1) return  1;		// return negative - Do not Flip elements
    if (a1 > b1) return -1;		// return positive - Flip elements
    return 0;				// Do nothing
}

//-----------------------------------------------------------------------------------------
