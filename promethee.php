<?php

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
    // aspect must be array of string
    $this->data = $data;
    $this->label = $label;
    $this->priority = $priority;
    $this->cost_benefit = $cost_benefit;
    $this->candidate = $merk;
  }

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

  private function promethee2($satu, $dua) {
    $hasil = $satu - $dua;
    if($hasil < 0) return (float)0;

    return $hasil;
  }

  public function getResult() {
    $normalize = [];
    $afterNormalize = [];
    $sumNormalize = [];
    $aggregate_pos = [];
    $aggregate_neg = [];
    $diff_result = [];
    $result = [];

    $this->max = $this->getMax();
    $this->min = $this->getMin();

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

    for($i = 0; $i < count($normalize[0]); $i++) {
      $temp = [];
      for($j = 0; $j < count($normalize[0]); $j++) {
        for($k = 0; $k < count($this->label); $k++) {
          if($j != $i) {
            $temp[$k] = $this->promethee2($normalize[$k][$i], $normalize[$k][$j]);
            // echo "Promethee II : ".$i." dan ".$j." adalah : ".$temp[$k]."<br>";
          } else {
            $temp[$k] = -1;
          }
        }
        $afterNormalize[] = $temp;
      }
    }

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

    for($i = 0; $i < count($sumNormalize); $i++) {
      $temp = 0;
      for($j = 0; $j < count($sumNormalize[$i]); $j++) {
        if($sumNormalize[$i][$j] > 0) {
          $temp += $sumNormalize[$i][$j];
        }
      }
      $aggregate_pos[] = $temp / (count($sumNormalize[$i])-1);
    }

    for($i = 0; $i < count($sumNormalize[0]); $i++) {
      $temp = 0;
      for($j = 0; $j < count($sumNormalize); $j++) {
        if($sumNormalize[$j][$i] > 0) {
          $temp += $sumNormalize[$j][$i];
        }
      }
      $aggregate_neg[] = $temp / (count($sumNormalize[$i])-1);
    }

    for($i = 0; $i < count($aggregate_pos); $i++) {
      $diff_result[$i] = $aggregate_pos[$i] - $aggregate_neg[$i];
      $result[$i] = ['label' => $this->candidate[$i], 'score' => $diff_result[$i]];
    }

    for($i = 0; $i < count($result); $i++) {
      for($j = 0; $j < $i; $j++) {
        if($result[$i]['score'] > $result[$j]['score']) {
          $temp = $result[$i]['score'];
          $result[$i]['score'] = $result[$j]['score'];
          $result[$j]['score'] = $temp;
        }
      }
    }

    return $result;

  }
}

?>