//==========================================================================================
// dump: This function takes any variable (primitive, array, hashes, object), 
//       and will return a text representation of that object content.
//       This function is similar to PHP print_r function
//      args: variable - any variable - primitive, array, hash(associative array), object
//            level    - (optional)
//    return: The textual representation of the array.
//==========================================================================================
function dump(variable, level) 
{
    var dumped_text  = "";
    if(!level) level = 0;
    
    var padding = "";                       //padding at the beginning of the line
    for(var j=0; j<level+1; j++) 
        padding += "    ";
    
    if(typeof(variable) == 'object')                            //Object or array? 
    {
        for(var item in variable)                               //loop thru all properties 
        {
            var value = variable[item];                         //get property value
            
            if(typeof(value) == 'object')                       //Object or array?
            {
                dumped_text += padding + "'" + item + "' ...\n";
                dumped_text += dump(value, level+1);            //recursive call
            } 
            else
                dumped_text += padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 
    else                                                        //string or numeric 
        dumped_text = "===> "+variable+" <===("+ typeof(variable) +")";

    return dumped_text;
}
//=====================================================================================
