<?php
// buatan sendiri
// home made, lagi galau nunggu do'i
// lama kali ini KKN, baru hari pertama udah berasa lama banget

# TODO: algoritma promethee untuk menentukan karyawan yang kompeten
# TODO: tanggal 2 februari ultah dia, beliin apa ya ? ada saran ga ten ??

class Promethee {
  private $data = [];
  private $label = [];
  private $priority = [];
  private $cost_benefit = [];
  private $candidate = [];
  private $max = [];
  private $min = [];

  public function __construct($data, $label, $priority, $cost_benefit, $merk) {
    // data must be array2D of float / int
    // label must be array of string
    // priority must be array of float
    // cost_benefit must be array of string
    // merk maybe need fix
    $this->data = $data;
    $this->label = $label;
    $this->priority = $priority;
    $this->cost_benefit = $cost_benefit;
    $this->candidate = $merk;
  }

  // you can get list of max number
  private function getMax() {
    $max = [];
    for($i = 0; $i < count($this->label); $i++) {
      $max[$i] = $this->data[0][$i];
    }

    for($i = 0; $i < count($this->label); $i++) {
      for($j = 0; $j < count($this->data); $j++) {
        if($this->data[$j][$i] > $max[$i]) {
          $max[$i] = $this->data[$j][$i];
        }
      }
    }

    return $max;
  }

  // you can get list of min number
  private function getMin() {
    $min = [];
    for($i = 0; $i < count($this->label); $i++) {
      $min[$i] = $this->data[0][$i];
    }

    for($i = 0; $i < count($this->label); $i++) {
      for($j = 0; $j < count($this->data); $j++) {
        if($this->data[$j][$i] < $min[$i]) {
          $min[$i] = $this->data[$j][$i];
        }
      }
    }

    return $min;
  }

  // to get difference by sub category by category
  private function promethee2($satu, $dua) {
    $hasil = $satu - $dua;

    // jika nilai minus, maka akan diubah menjadi angka 0
    if($hasil < 0) return (float)0;

    return $hasil;
  }

  // full algorithm for promethee
  public function getResult() {
    $normalize = [];
    $afterNormalize = [];
    $sumNormalize = [];
    $aggregate_pos = []; // agregasi untuk bagian positif
    $aggregate_neg = []; // agregasi untuk bagian negatif
    $diff_result = []; // hasil dari agregasi positif - agregasi negatif
    $result = []; // hasil dari rekomendasi

    // mencari nilai maksimum dan minimum
    $this->max = $this->getMax();
    $this->min = $this->getMin();

    # TODO: melakukan normalisasi
    for($i = 0; $i < count($this->label); $i++) {
      $temp = [];
      $pembagi = $this->max[$i] - $this->min[$i];
      for($j = 0; $j < count($this->data); $j++) {
        if($this->cost_benefit == "benefit") {
          $temp[$j] = $this->data[$j][$i] / $pembagi;
        } else {
          $temp[$j] = $this->data[$j][$i] / $pembagi;
        }
      }
      $normalize[] = $temp;
    }

    # TODO: melakukan normalisasi dengan cara mencari nilai selisih
    for($i = 0; $i < count($normalize[0]); $i++) {
      $temp = [];
      for($j = 0; $j < count($normalize[0]); $j++) {
        for($k = 0; $k < count($this->label); $k++) {
          if($j != $i) {
            $temp[$k] = $this->promethee2($normalize[$k][$i], $normalize[$k][$j]);
            # uncomment if you want to see diff score
            // echo "Promethee II : ".$i." dan ".$j." adalah : ".$temp[$k]."<br>";
          } else {
            $temp[$k] = -1;
          }
        }
        $afterNormalize[] = $temp;
      }
    }

    # TODO: melakukan perkalian dengan nilai bobot yang telah ditentukan ke dalam variabel priority
    $count = 0;
    for($i = 0; $i < count($afterNormalize); $i++) {
      $temp = 0;
      if(!($count % (count($afterNormalize[$i]) * 2))) $x = [];
      for($j = 0; $j < count($afterNormalize[$i]); $j++) {
          $temp += $afterNormalize[$i][$j] * $this->priority[$j];
      }
      $count++;
      array_push($x, $temp);

      if(!($count % (count($afterNormalize[$i]) * 2)))
        $sumNormalize[] = $x;
    }

    # TODO: mencari agregasi positif
    for($i = 0; $i < count($sumNormalize); $i++) {
      $temp = 0; // reset nilai variabel sementara
      for($j = 0; $j < count($sumNormalize[$i]); $j++) {
        if($sumNormalize[$i][$j] > 0) {
          $temp += $sumNormalize[$i][$j];
        }
      }
      // selalu kurang 1, karena 1 kolom adalah perbandingan atas kategorinya sendiri
      $aggregate_pos[] = $temp / (count($sumNormalize[$i])-1);
    }

    # TODO: mencari agregasi negatif
    for($i = 0; $i < count($sumNormalize[0]); $i++) {
      $temp = 0;
      for($j = 0; $j < count($sumNormalize); $j++) {
        if($sumNormalize[$j][$i] > 0) {
          $temp += $sumNormalize[$j][$i];
        }
      }

      // selalu kurang 1, karena 1 kolom adalah perbandingan atas kategorinya sendiri
      $aggregate_neg[] = $temp / (count($sumNormalize[$i])-1);
    }

    # TODO: mencari bobot agregasi sebagai hasil dengan cara (agregasi positif - agregasi negatif)
    for($i = 0; $i < count($aggregate_pos); $i++) {
      $diff_result[$i] = $aggregate_pos[$i] - $aggregate_neg[$i];
      $result[$i] = ['label' => $this->candidate[$i], 'score' => $diff_result[$i]];
    }

    // sorting using bubble sort algorithm
    for($i = 0; $i < count($result); $i++) {
      for($j = 0; $j < $i; $j++) {
        // mengurutkan dari yang terbesar
        if($result[$i]['score'] > $result[$j]['score']) {
          # TODO: melakukan swap
          $temp_score = $result[$i]['score'];
          $result[$i]['score'] = $result[$j]['score'];
          $result[$j]['score'] = $temp_score;

          $temp_label = $result[$i]['label'];
          $result[$i]['label'] = $result[$j]['label'];
          $result[$j]['label'] = $temp_label;
        }
      }
    }

    // result must be array assocciate
    // label and score
    return $result;
  }
}

// numpang curhat yaa ten.. wkwkwk
// sebenernya sekarang lagi kerja sih.. cuman udah pada kelar..
// gegara habis kerjaannya, jadi meracau sendiri di komentar program..
// semoga ga kebawa pas presentasi yaa ten.. wkwk

?>
