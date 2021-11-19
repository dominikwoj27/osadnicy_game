<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
header('Location: index.php');
exit();
}
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
</head>

<body>
<?php
echo "Witaj ".$_SESSION['user']."!        ".'[<a href="logout.php">Wyloguj się!</a>]<br /> ';
echo "<br /> <b>Drewno:</b> ".$_SESSION['drewno'];
echo " | "."<b>Kamień:</b> ".$_SESSION['kamien'];
echo " | "."<b>Zboże:</b> ".$_SESSION['zboze'];
echo "<br /> <b>email:</b> ".$_SESSION['email']."<br />"."<br /> <br /> ";
echo "<br /> Data wygaśnięcia premium: ".$_SESSION['dnipremium'];
$dataczas = new DateTime();
//$dataczas = now();
echo "<br />Data i czas serwera: ".$dataczas->format('Y-m-d H:i:s')."<br>";

$koniec = DateTime::createFromFormat ('Y-m-d H:i:s', $_SESSION['dnipremium']);
$roznica = $dataczas->diff($koniec);

if($dataczas<$koniec)
{
  echo "pozostało premium:  ".$roznica->format('%d dni, %H godzin, %i minut, %s sekund');
}
else {
  echo "premium niekatywne od:  ".$roznica->format('%d dni, %H godzin, %i minut, %s sekund');
}
?>
</body>
</html>
