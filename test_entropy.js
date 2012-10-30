function test_table(rows)
{
    document.write("<table>");
    for (var i=0; i<rows; ++i) {
	test_row();
    }
    document.write("</table>");

}

function test_row()
{
    var password = random_pasword();
    var password_js_entropy_nist= entropy_nist(password);
    var password_js_entropy_wolfram = entropy_wolfram(password);
    var password_php_entropy_nist= entropy_ajax("nist",password);
    var password_php_entropy_wolfram = entropy_ajax("wolfram",password);
    
    var note_nist = Math.abs(password_php_entropy_nist-password_js_entropy_nist) >= 0.5;
    var note_wolfram = Math.abs(password_php_entropy_wolfram-password_js_entropy_wolfram) >= 0.5;    
    
    document.write("<tr><td>");
    for (var i=0; i<password.length; ++i)
    {
	document.write("&" + password.charCodeAt(i) + ";");
    }
    document.write("</td>");
    document.write("<td" + (note_nist ? " class='note'" : "") + ">" +
		   password_js_entropy_nist + " - " + password_php_entropy_nist + "</td>");
    document.write("<td" + (note_wolfram ? " class='note'" : "") + ">" +
		   password_js_entropy_wolfram + " - " + password_php_entropy_wolfram + "</td>");
    document.write("</tr>");
}
