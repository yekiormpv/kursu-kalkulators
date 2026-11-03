<?php
require_once "data.php";
require_once "functions.php";

$kludas = 0;
$selected = $_POST["kurss"] ?? "";

$vards = $_POST["vards"] ?? "";
$epasts = $_POST["epasts"] ?? "";

?>

<!DOCTYPE html>
<html lang="lv">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kursu pieteikuma kalkulators</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
		<h1>Interneta skola: <span class="subtitle">Kursu pieteikuma kalkulators</span></h1>
</header>

<table>
	<thead>
		<tr><th width="60%">Kursa nosaukums</th><th>Cena 1 dalībniekam, &euro;</th></tr>	<!-- vai &#8364; -->
		<caption>Pašlaik piedāvātie kursi</caption>
	</thead>
	<?php
	foreach($kursi as $kurss => $dati) {
		$nosaukums = $dati["nosaukums"];
		$cena = $dati["cena"];
		echo sprintf("<tr><td>%s</td><td class=\"td_price\">%.2f</td></tr>", $nosaukums, $cena);
	}
	?>
</table>
<?= "<p>Pašlaik pieejamo kursu skaits: " . count($kursi) ."</p>" ?>

<div class="calculator">
<h2>Kursu pieteikumu kalkulators</h2>

<?php /*
if($_SERVER["REQUEST_METHOD"] === "POST") {
	$vards = trim($_POST["vards"]) ?? "";
	if($vards == "") {	
		echo "<p class=\"error\">Nav ievadīts vārds.</p>";
		$kludas += 1;
	}
	$epasts = trim($_POST["epasts"]) ?? "";
	if(!filter_var($epasts, FILTER_VALIDATE_EMAIL)) {
		echo "<p class=\"error\">Nederīga e-pasta adrese.</p>";
		$kludas += 1;
	}
	$kurss = trim($_POST["kurss"]) ?? "";
	if(!array_key_exists($kurss, $kursi)) {
		echo "<p class=\"error\">Izvēlētais kurss nav pieejams.</p>";
		$kludas += 1;
	}
	$skaits = trim($_POST["skaits"]) ?? "";
	if($skaits < 1 || $skaits > 5) {
		echo "<p class=\"error\">Dalībnieku skaits jābūt no 1 līdz 5.</p>";
		$kludas += 1;
	} */
?>

<form action="" method="POST">	
	<label for="vards">Vārds:</label>
	<input type="text" name="vards" value="<?= htmlspecialchars($vards ?? '') ?>"><br>
	<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		$vards = trim($_POST["vards"]) ?? "";
		if($vards == "") {	
			echo "<p class=\"error\">Nav ievadīts vārds.</p>";
			$kludas += 1;
		}
	}
	?>
	<label for="epasts">E-pasts:</label>
	<input type="text" name="epasts" value="<?= htmlspecialchars($epasts ?? '') ?>"><br>
	<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		$epasts = trim($_POST["epasts"]) ?? "";
		if(!filter_var($epasts, FILTER_VALIDATE_EMAIL)) {
			echo "<p class=\"error\">Nederīga e-pasta adrese.</p>";
			$kludas += 1;
		}
	}
	?>
	<label for="kurss">Kursa izvēle:</label>
	<select id="kurss" name="kurss">
		<?php
		foreach($kursi as $kurss => $dati) {
			echo "<option value=\"{$kurss}\"";
			echo $selected === $kurss ? " selected" : '';
			echo ">{$dati["nosaukums"]}</option>";
			echo "</option>";
		};
		?>
	</select><br>
	<?php
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		$kurss = trim($_POST["kurss"]) ?? "";
		if(!array_key_exists($kurss, $kursi)) {
			echo "<p class=\"error\">Izvēlētais kurss nav pieejams.</p>";
			$kludas += 1;
		}
	}
	?>
	<label for="skaits">Dalībnieku skaits (1-5):</label>
	<input type="text" id="skaits" name="skaits" min="1" max="5" value="<?= htmlspecialchars($skaits ?? '') ?>"><br>
	<?php
	$skaits = 0;
	if($_SERVER["REQUEST_METHOD"] === "POST") {
		$skaits = trim($_POST["skaits"]) ?? 0;
		if($skaits < 1 || $skaits > 5) {
			echo "<p class=\"error\">Dalībnieku skaits jābūt no 1 līdz 5.</p>";
			$kludas += 1;
		}
	}

	echo "<button name=\"submit\" type=\"submit\" value=\"Yes\">Iesniegt</button>";
	
	function show_results(string $vards, string $epasts, string $kursa_nosaukums, int $skaits, float $kop_cena, float $atlaide, float $gala_cena) {
	echo "<div class=\"message\"><h2 >Paldies! Pieņemts pietekums:</h2>";
	echo "<p>Kursu $skaits dalībniekiem ir pieteicis: $vards</p>";
	echo "<p>Kursa pieteicēja e-pasts: $epasts</p>";
	echo "<p>Reģistrēts(i) kursam: $kursa_nosaukums</p>";
	echo "<p>Dalībnieku skaits: $skaits</p>";
	echo "<p>Kursa cena par $skaits dalībniekiem: $kop_cena €</p>";
	echo "<p>Atlaide: $atlaide €</p>";
	echo "<p>Gala cena: $gala_cena €</p></div>";
	}

	if(isset($_POST["submit"])) {
		if($kludas === 0) {
			$cena = $kursi[$kurss]["cena"];
			[$kop_cena, $atlaide, $gala_cena] = aprekins($cena, $skaits);

			$kursa_nosaukums = $kursi[$kurss]["nosaukums"];
			show_results($vards, $epasts, $kursa_nosaukums, $skaits, $kop_cena, $atlaide, $gala_cena);
		} else {
			echo "<p class=\"error\">Lūdzu, labojiet kļūdas un iesniedziet formu vēlreiz.</p>";
		}
	}
	?>
</form>
</div>
</body>
</html>