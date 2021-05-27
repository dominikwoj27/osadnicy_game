<?php

session_start();

if(!isset($_POST['login']) || !isset($_POST['haslo']))
{
  header('Location; index.php');
  exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0)
{
  echo "error: ".$polaczenie->connect_errno;
}

else
{
  $login = $_POST['login'];
  $haslo = $_POST['haslo'];
  $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    /* $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8"); */

$sql = sprintf ("SELECT * FROM uzytkownicy WHERE '%s'=user",
mysqli_real_escape_string($polaczenie,$login));

if ($result = @$polaczenie->query($sql))
{
  $ilu_userow = $result->num_rows;
  if($ilu_userow>0)
  {
    $wiersz = $result->fetch_assoc();

      if (password_verify($haslo,$wiersz['pass']))
      {
            $_SESSION['zalogowany'] = true;

            $_SESSION['id'] = $wiersz['id'];
            $_SESSION['user'] = $wiersz['user'];
            $_SESSION['drewno'] = $wiersz['drewno'];
            $_SESSION['kamien'] = $wiersz['kamien'];
            $_SESSION['zboze'] = $wiersz['zboze'];
            $_SESSION['email'] = $wiersz['email'];
            $_SESSION['dnipremium'] = $wiersz['dnipremium'];

            unset($_SESSION['blad']);
            $result->close();
            header('Location: gra.php');
      }
      else
      {
      $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo </span>';
      header('Location: index.php');
       $_SESSION['zalogowany'] = false;
      }
  }
  else
  {
$_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub haslo </span>';
header('Location: index.php');
 $_SESSION['zalogowany'] = false;
  }
}


  $polaczenie->close();
}




 ?>
