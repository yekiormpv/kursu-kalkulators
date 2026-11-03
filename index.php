<?php
require_once "data.php";
require_once "functions.php";

$kludas = 0;

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
<div class="container">
<table>
	<caption>Kursi</caption>
	<thead>
	<tr><th>Kursa nosaukums</th><th>Cena 1 dalībniekam</th></tr>
	</thead>
	<?php
	foreach($kursi as $kurss => $dati) {
		$nosaukums = $dati["nosaukums"];
		$cena = $dati["cena"];
		echo "<tr><td>$nosaukums</td><td>$cena</td></tr>";
	}
	?>
</table>
<?= "<p>Kursu skaits: " . count($kursi) ."</p>" ?>
</div>
<div class="container">
<h2>Kursu pieteikumu kalkulators</h2>
<form action="" method="POST">	
	<label for="vards">Vārds:</label>
	<input type="text" name="vards" required><br>
	<label for="epasts">E-pasts:</label>
	<input type="email" name="epasts" required><br>
	<label for="kurss">Kursa izvēle:</label>
	<select id="kurss" name="kurss" required>
		<?php
		foreach($kursi as $kurss => $dati) {
			echo "<option value=\"{$kurss}\"";
			// echo $selected === $key ? " selected" : '';
			echo ">{$dati["nosaukums"]} - {$dati["cena"]} €</option>";
			echo "</option>";
		};
		?>
	</select><br>
	<label for="skaits">Dalībnieku skaits (1-5):</label>
	<input type="number" id="skaits" name="skaits" min="1" max="5" required><br>

	<button type="submit" value="Yes">Iesniegt</button>

<?php
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
	}

	if($kludas === 0) { 
		/* aprēķiniem nepieciešami vienas vietas cena un dalībnieku skaits) */
		$cena = $kursi[$kurss]["cena"];
		[$kop_cena, $atlaide, $gala_cena] = aprekins($cena, $skaits);

		$kursa_nosaukums = $kursi[$kurss]["nosaukums"];
		show_results($vards, $epasts, $kursa_nosaukums, $skaits, $kop_cena, $atlaide, $gala_cena);
	} else {
		echo "<p class=\"error\">Lūdzu, labojiet kļūdas un iesniedziet formu vēlreiz.</p>";
	}
}

function show_results(string $vards, string $epasts, string $kursa_nosaukums, int $skaits, float $kop_cena, float $atlaide, float $gala_cena) {
	echo "<p class=\"message\">Paldies! Pieņemts pietekums:</p>";
	echo "<p class=\"message\">Kursu $skaits dalībniekiem ir pieteicis: $vards</p>";
	echo "<p class=\"message\">Kursa pieteicēja e-pasts: $epasts</p>";
	echo "<p class=\"message\">Reģistrēts(i) kursam: $kursa_nosaukums</p>";
	echo "<p class=\"message\">Dalībnieku skaits: $skaits</p>";
	echo "<p class=\"message\">Kursa cena par $skaits dalībniekiem: $kop_cena €</p>";
	echo "<p class=\"message\">Atlaide: $atlaide €</p>";
	echo "<p>Gala cena: $gala_cena €</p>";
}
?>
</form>
</body>
</html>
