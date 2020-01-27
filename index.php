<?php

  include "promethee.php";

  $label = ["Kamera", "Baterai", "Harga"];

  $merk = ["SAMSUNG", "XIAOMI", "IPHONE", "VIVO", "REALME", "OPPO"];

  $data[] = [8, 3000, 1500000];
  $data[] = [16, 4000, 2000000];
  $data[] = [8, 3400, 1000000];
  $data[] = [2, 1500, 800000];
  $data[] = [12, 5000, 3000000];
  $data[] = [8, 4000, 2600000];

  $priority = [0.2, 0.5, 0.3];

  $cost_benefit = ["benefit", "benefit", "cost"];

  $promethee = new Promethee($data, $label, $priority, $cost_benefit, $merk);
  $prioritas = $promethee->getResult();

  foreach($prioritas as $p) {
    echo $p['label']." score : ".$p['score'];
    echo "<br>";
  }

?>
