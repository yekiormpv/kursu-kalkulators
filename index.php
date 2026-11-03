<?php
require_once "data.php";
?>
<!DOCTYPE html>
<html lang="lv">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IT kursi: kursu piteikumu kalkulators</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
	<h1>Interneta skola: <span>IT kursi</span></h1>
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
<form action="" method="post">	
	<label for="vards">Vārds:</label>
	<input type="text" name="vards" required><br>
	<label for="epasts">E-pasts:</label>
	<input type="email" name="epasts" required><br>
	<label for="kurss">Kursa izvēle:</label>
	<select id="kurss" name="kurss" required>
		<?php
		foreach($kursi as $kurss => $dati) {
			echo "<option value={$dati["nosaukums"]} - {$dati["cena"]} €";
			// echo $selected === $key ? "selected" : '';
			echo "</option>";
		};
		?>
	</select><br>
	<label for="skaits">Dalībnieku skaits (1-5):</label>
	<input type="number" id="skaits" name="skaits" min="1" max="5" required><br>

	<button type="submit" value="Yes">Iesniegt</button>
</form>
</body>
</html>
