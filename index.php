<?php
session_start();

if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany']==true)
{
  header('Location: gra.php');
  exit();
}
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
  <title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>

<h1>
Tylko martwi ujrzeli koniec wojny - Platon
</h1>

  <a href='rejestracja.php'>Zarejestruj się! </a> <br /> <br />
  
<form action="zaloguj.php" method="post">
      Login: <br/> <input type="text" name="login"> <br />
      Hasło: <br/> <input type="password" name="haslo"><br /><br />
      <input type="submit" value="zaloguj sie" />
</form>

<?php
  if(isset($_SESSION['blad']))
  echo $_SESSION['blad'];
?>
 
</body>
</html>
