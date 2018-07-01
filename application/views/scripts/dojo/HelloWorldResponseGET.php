<?php
  /*
  * HelloWorldResponseGET.php
  * --------
  *
  * Print the name that is passed in the
  * 'name' $_GET parameter in a sentence
  */

  header('Content-type: text/plain');
  print "Hello {$_GET['name']}, welcome to the world of Dojo!\n";
?>