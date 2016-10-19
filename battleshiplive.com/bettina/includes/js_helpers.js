///////////////////////////////////////////////
/**********************************************

js_helpers.js a file of javascript helper functions. 
***********************************************/
function show_confirm(msg, reloc)
{
	var r=confirm(msg + "\n\nPress OK to DELETE or cancel to return");
	if (r==true)
	{
		window.location = reloc;
	}
}