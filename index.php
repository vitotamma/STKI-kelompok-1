<?php
// demo.php

// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();

// connect ke database (?)
$host = mysqli_connect("localhost","root","");
$db = mysqli_select_db($host,"stki_nazief");

//ambil dokumen dari database
$ambilBerita = mysqli_query($host,"SELECT dokumen_berita FROM dokumen WHERE id_dokumen=1");
$arrayBerita = mysqli_fetch_array($ambilBerita);
$sentence = $arrayBerita['dokumen_berita'];

//manggil metodenya Steeming
$output   = $stemmer->stem($sentence);

//buat variabel global untuk cari jumlah kata yamg sering keluar dan berapa kali
$GLOBALS ['nilaiTerbesar'] = 0;
$GLOBALS ['kataTerbanyak'] = " ";

echo "teks sebelum steem : " . $sentence;
echo "<br>";
echo "<br>";

echo "teks setelah steem : " . $output . "\n";
echo "<br>";
echo "<br>";


//cari jumlah kata, mulai hitung waktu persteem kata
$words = explode(' ', $output); //misah perkata
$data   = array_count_values($words); //ngitung banyak kata di 1 dokumen berita
foreach($data as $x => $x_value) {
  $start = microtime(TRUE);
    echo $x." : ".$x_value;

    //cari yang terbesar
    if ($GLOBALS['nilaiTerbesar'] < $x_value) {
        $GLOBALS['nilaiTerbesar'] = $x_value;
        $GLOBALS['kataTerbanyak'] = $x;
      }
    echo "<br>";
    $finish = microtime(TRUE);
    $totaltime = $finish - $start; //dapetin lama pengerjaan
    echo "Steeming kata " .$x. " dilakukan selama ".$totaltime." detik hingga selesai";
    echo "<br>";
    echo "<br>";
}

echo "<br>";
echo "Nilai Terbesar : " . $GLOBALS['nilaiTerbesar'];
echo "<br>";
echo "Kata Terbanyak Muncul : " . $GLOBALS['kataTerbanyak'];
echo "<br>";


?>
