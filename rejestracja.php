<?php
session_start();

if (isset($_POST['email']))
{
//udana walidacja
$wszystko_ok = true;
$nick = $_POST['nick'];
$email = $_POST['email'];
$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
$haslo1 = $_POST['haslo1'];
$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
$haslo2 = $_POST['haslo2'];



//sprawdzamy dlugosc nicku 3-20
if (strlen($nick)>20 ||strlen($nick)<3)
{
  $_SESSION['e_nick'] = "nick musi zawierać się między od 3 do 20 znaków";
  $wszystko_ok = false;
}

if(ctype_alnum($nick)==false)
{
  $wszystko_ok =false;
  $_SESSION['e_nick'] = "nick musi składać się z liter i cyfr (bez polskich znaków)";
}


if ((filter_var($emailB, FILTER_VALIDATE_EMAIL))==false || ($emailB != $email))
{
  $wszystko_ok = false;
  $_SESSION['e_email'] = "podano niepoprawny adres email";

}
if (strlen($haslo1)>20 || strlen($haslo1)<8)
{
    $_SESSION['e_haslo1'] = "haslo musi zawierać się między od 8 do 20 znaków";
    $wszystko_ok = false;
}

if ($haslo1 != $haslo2)
{
    $_SESSION['e_haslo2'] = "podane hasła nie są identyczne";
    $wszystko_ok = false;
}

if(!isset($_POST['regulamin']))
{
  $_SESSION['e_box'] = "nie zaakcceptowano regulaminu";
  $wszystko_ok = false;
}
$sekret = "6LfyOYMaAAAAAEU9e2DVovZSFBCDH-O2y5D_XSuB";
$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
$odpowiedz = json_decode($sprawdz);

if($odpowiedz->success==false)
{
    $_SESSION['e_bot'] = "zaznacz captcha!";
    $wszystko_ok = false;
}

require_once "connect.php";

mysqli_report(MYSQLI_REPORT_STRICT);
try
{
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());

  }
  else {
//czy dany mail istnieje?
    $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
     if(!$rezultat) throw new Exception ($polaczenie->error);

      $ile_takich_maili = $rezultat->num_rows;
      if ($ile_takich_maili>0) {
        $_SESSION['e_email'] = "adres eamil juz istnieje w naszej bazie danych";
        $wszystko_ok = false;
      }

      //czy dany nick istnieje?
          $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
           if(!$rezultat) throw new Exception ($polaczenie->error);

            $ile_takich_nickow = $rezultat->num_rows;
            if ($ile_takich_nickow>0) {
              $_SESSION['e_nick'] = "nick juz istnieje w naszej bazie danych";
              $wszystko_ok = false;
}

if($wszystko_ok ==true)
{
  if ($polaczenie->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick', '$haslo_hash','$email' ,100,100,100,now() + INTERVAL 14 DAY)"))
  {
    $_SESSION['udanarejestracja']=true;
    header('Location: witamy.php');
  }
  else throw new Exception($polaczenie->error);

}

    $polaczenie->close();
  }

}

catch (Exception $e)
{
  echo '<span style = "color: red;"> błąd serwera, przepraszamy za niedogodności iprosimy o spróbowanie później.</span>';
  echo 'informacja developerska: '.$e;
}

}


 ?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
  <title>Osadnicy - załóż konto by zacząć przygodę</title>
  <script src='https://www.google.com/recaptcha/api.js'></script>


  <style>
.error
{
  color: red;
  margin-top: 10px;
  margin-bottom: 10px;
}
  </style>

</head>




<body>

<form method="post">
    Nickname: <br /> <input type='text' name='nick'/> <br />
<?php
if(isset($_SESSION['e_nick']))
{
  echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
  unset($_SESSION['e_nick']);
}

?>

    email: <br /> <input type='text' name='email'/> <br />

    <?php
    if(isset($_SESSION['e_email']))
    {
      echo '<div class="error">'.$_SESSION['e_email'].'</div>';
      unset($_SESSION['e_email']);
    }?>


    hasło: <br /> <input type='password' name='haslo1'/> <br />
    <?php
    if(isset($_SESSION['e_haslo1']))
    {
      echo '<div class="error">'.$_SESSION['e_haslo1'].'</div>';
      unset($_SESSION['e_haslo1']);
    }?>

    powtórz hasło: <br /> <input type='password' name='haslo2'/> <br />
    <?php
    if(isset($_SESSION['e_haslo2']))
    {
      echo '<div class="error">'.$_SESSION['e_haslo2'].'</div>';
      unset($_SESSION['e_haslo2']);
    }?>

      <label>
        <input type="checkbox" name="regulamin"/> Akceptuję regulamin
      </label>

      <?php
      if(isset($_SESSION['e_box']))
      {
        echo '<div class="error">'.$_SESSION['e_box'].'</div>';
        unset($_SESSION['e_box']);
      }?>

      <br />

      <div class = "g-recaptcha" data-sitekey="6LfyOYMaAAAAAIkgqCkcZGLXYa1jmIWxWEIY-Tr_"></div>

      <?php
      if(isset($_SESSION['e_bot']))
      {
        echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
        unset($_SESSION['e_bot']);
      }?>

  <br />
      <input type="submit" value="zarejestruj się" />
</form>



</body>



</html>
