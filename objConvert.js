//=====================================================================================
// dom2arrObj: Converts an XML DOM with 3 levels to an array of objects
//             level 1 is the array, 
//             level 2 is the object element key, level 3 the object element value
//        args: - an XML DOM object
//      return: - an array of objects           
//=====================================================================================
function dom2arrObj(dom) 
{
    var row    = 0;
    var arrObj = new Array();                           //declare an array

    if (!dom) return;

    root = dom.documentElement;                         //navigate to the root node

    for (var i=0; i < root.childNodes.length; i++)      //for as many child nodes 
    {       
        level2 = root.childNodes[i];                    //2nd level node
        if (level2.nodeType == 1)                       //if it is an element (not attr, comment, etc.)
        {
            obj = new Object();                         //create an object
            for (var j=0; j < level2.childNodes.length; j++) 
            {       
                level3 = level2.childNodes[j];          //3rd level node 
                if (level3.nodeType == 1)               //if it is an element node      
                {
                    nodeName  = level3.nodeName;            //get the node name
                    if (window.ActiveXObject)               //for I.E. 
                        nodeText  = level3.text;            //get the text property 
                    else                                    //for Firefox/W3C
                        nodeText  = level3.textContent;     //get the textContent property 

                    obj[nodeName] = nodeText;               //build the object
                }
            }
            arrObj[row++] = obj;                        //add to array of objects
        }
    }
    return(arrObj);
}
//==============================================================================================
// dom2text: converts an XML DOM to text string 
//         args: - an XML DOM object
//       return: - text representation of the XML        
//==============================================================================================
function dom2text(dom)
{
    if (!dom) return;

    if (window.ActiveXObject)                           //for Internet Explorer
        var text = dom.xml;                             //obtain the XML text 
     
    if (window.XMLHttpRequest)                          //for Firefox/W3C
    {                               
        serial   = new XMLSerializer();                 //create a serializer
        var text = serial.serializeToString(dom);       //serialize the DOM to text
    }

    return(text);
}

//==============================================================================================
// arrObj2arr: converts an array of objects into a 2 dim array 
//             first row being the headers
//             remaining rows are the data  
//         args: - an array of objects
//       return: - a 2 dim array, first row are the element names        
//==============================================================================================
function arrObj2arr(arrObj)
{
    var maxCount = 0;                   //holds the maximum number of properties 
    var maxElement;                     //holds the element in the array with the max properties
    var array2 = new Array();           //declare 2 dim array

    for (i=0; i<arrObj.length; i++)         //loop through the entire array
    {
        count = 0;
        for (prop in arrObj[i])             //loop through each object
        count++;                    //count the properties
        if (count > maxCount)
        {
            maxCount   = count;
            maxElement = i; 
        }
    }

    var j   = 0;    
    var headers = new Array();                      //declare row array
    for (prop in arrObj[maxElement])
    headers[j++] = prop;    

    array2[0] = headers;                            //store headers is slot 0

    for (i=0; i<arrObj.length; i++)                 //loop through the entire array
    {
        var j   = 0;
        var row = new Array();
        for (j=0; j<headers.length; j++)            //loop through each object
        {   
            row[j] = arrObj[i][headers[j]]          //build row array from property values
            if (! row[j]) row[j] = '';              //if element does not exist, make it '' 
        }
        array2[i+1] = row;                          //build 2 dimensional array 
    }
    return(array2)
}
//==============================================================================================

