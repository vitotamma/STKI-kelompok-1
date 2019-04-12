<?php
// demo.php

// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();

// kalimat aslinya
$sentence = 'Perekonomian Indonesia sedang dalam pertumbuhan yang membanggakan';

//$start = microtime(TRUE);
$output   = $stemmer->stem($sentence);
echo $output;
// $GLOBALS ['nilaiTerbesar'] = 0;
// $GLOBALS ['kataTerbanyak'] = " ";

// echo "teks sebelum steem : " . $sentence;
// echo "<br>";
// echo "<br>";

// echo "teks setelah steem : " . $output . "\n";
// echo "<br>";
// echo "<br>";


// //cari jumlah kata
// $words = explode(' ', $output);
// $data   = array_count_values($words);
// foreach($data as $x => $x_value) {
//   $start = microtime(TRUE);
//     echo $x." : ".$x_value;
//     if ($GLOBALS['nilaiTerbesar'] < $x_value) {
//         $GLOBALS['nilaiTerbesar'] = $x_value;
//         $GLOBALS['kataTerbanyak'] = $x;
//       }
//     echo "<br>";
//     $finish = microtime(TRUE);
//     $totaltime = $finish - $start;
//     echo "Steeming kata |" .$x. " dilakukan selama |".$totaltime."   detik hingga selesai";
// }

// echo "<br>";
// echo "Nilai Terbesar : " . $GLOBALS['nilaiTerbesar'];
// echo "<br>";
// echo "Kata Terbanyak Muncul : " . $GLOBALS['kataTerbanyak'];
// echo "<br>";

//$finish = microtime(TRUE);


// $totaltime = $finish - $start;
// echo "Steeming dilakukan selama ".$totaltime." detik hingga selesai"

?>
