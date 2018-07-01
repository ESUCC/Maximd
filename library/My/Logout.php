class Logout
{
  function logitout ($vari) {
    $myf2=fopen("/tmp/logout.txt","w");
    fwrite($myf2,$vari);
    fclose($myf2);
  }
}
