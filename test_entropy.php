<?php 
require_once('phpass/src/Phpass/Strength.php');
require_once('phpass/src/Phpass/Strength/Adapter.php');
require_once('phpass/src/Phpass/Strength/Adapter/Base.php');
require_once('phpass/src/Phpass/Strength/Adapter/Nist.php');
require_once('phpass/src/Phpass/Strength/Adapter/Wolfram.php');
require_once('password_random.php');
require_once('entropy_nist.php');
require_once('entropy_wolfram.php');

$tests = 10000;
function test_data() 
{
  $wolfram = new Phpass\Strength\Adapter\Wolfram();
  $nist = new Phpass\Strength\Adapter\Nist();
  $data = array();
  for ($test = 0; $test < 1000; ++$test) {
    $password = password_random();
    $cnist= $nist->check($password);
    $fnist = entropy_nist($password);
    $cwolfram = $wolfram->check($password);
    $fwolfram=entropy_wolfram($password);
    $data[] = array("password" => $password, 
		    "nist" => array( "c" => $cnist, "f" => $fnist ), 
		    "wolfram" => array( "c" => $cwolfram, "f" => $fwolfram));
  }
  return $data;
}
?>
<!DOCTYPE: html>
<html>
<title>Test Entropy</title>
<script src="entropy_nist.js"></script>
<script src="entropy_wolfram.js"></script>
<style>.note { color: red; } </style>
<body>
<script>
   var tests = <?php echo json_encode(test_data()) ?> ;
   var notes_fnist = 0;
   var notes_jnist = 0;
   var notes_fwolfram = 0;
   var notes_jwolfram = 0;

   function test_data()
   {
    document.write("<table>");
    document.write("<tr><th>password</th><th>nist (class / function / javascript)</th><th>wolfram (class / function / javascript)</td><th>note</th></tr>");   for (var test=0; test<tests.length; ++test) {
      var password=tests[test]['password'];
      var cnist=tests[test]['nist']['c'];
      var fnist=tests[test]['nist']['f'];
      var jnist=entropy_nist(password);

      var cwolfram=tests[test]['wolfram']['c'];
      var fwolfram=tests[test]['wolfram']['f'];
      var jwolfram=entropy_wolfram(password);

      var note_fnist = Math.abs(cnist-fnist) > 1e-6;
      var note_jnist = Math.abs(cnist-jnist) > 1e-6;
      if (note_fnist) ++notes_fnist;
      if (note_jnist) ++notes_jnist;

      var note_fwolfram = Math.abs(cwolfram-fwolfram) > 1e-6;
      var note_jwolfram = Math.abs(cwolfram-jwolfram) > 1e-6;
      if (note_fwolfram) ++notes_fwolfram;
      if (note_jwolfram) ++notes_jwolfram;
      
      document.write("<tr><td>");
      for (var i=0; i<password.length; ++i)
      {
  	document.write("&#" + password.charCodeAt(i) + ";");
      }
      document.write("</td>");
      document.write("<td" + ((note_jnist || note_fnist) ? " class='note'" : "") + ">" +
		     cnist + "/" + fnist + "/" + jnist + "</td>");
      document.write("<td" + ((note_jwolfram || note_fwolfram) ? " class='note'" : "") + ">" +
		     cwolfram + "/" + fwolfram + "/" + jwolfram + "</td>");
      document.write("<td>");
      if (note_fnist) document.write("nist(function)");
      if (note_jnist) document.write("nist(javascript)");
      if (note_fwolfram) document.write("wolfram(function)");
      if (note_jwolfram) document.write("wolfram(javascript)");
      document.write("</td>");
      document.write("</tr>");
    }
    document.write("</table>");
    document.write("Nist php function notes: " + notes_fnist + "<br/>\n");
    document.write("Nist javascript notes: " + notes_jnist + "<br/>\n");
    document.write("Wolfram php function notes: " + notes_fwolfram + "<br/>\n");
    document.write("Wolfram javascript notes: " + notes_jwolfram + "<br/>\n");
  }
test_data();
</script>    
</body>
</html>
