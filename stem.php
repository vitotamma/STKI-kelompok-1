<?php

include 'connection.php';
require_once __DIR__ . '/vendor/autoload.php';
include './ehcs.php';
include './vendor/sastrawi/sastrawi/src/Sastrawi/Stemmer/Filter/TextNormalizer.php';

$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer = $stemmerFactory->createStemmer();

if (isset($_POST['dokumen'])) {
    postDocument($_POST['dokumen']);

    $normalizer = normalizer($_POST['dokumen']);

    if ($_POST['algorithm']==='nazief') {
        stemNazief($normalizer);        
    } else if ($_POST['algorithm']==='gabungan') {
        stemGabungan($normalizer);
    } else if ($_POST['algorithm']==='ehcs') {
        stemEhcs($normalizer);
    }
}

function postDocument($data)
{
    global $conn;
    $query = "INSERT INTO document(document) VALUES('$data')";    

    $result = mysqli_query($conn,$query);

    if (!$result) {
        die("Insert gagal ".mysqli_error($conn));
    }
}

function stemNazief($data)
{
    global $stemmer;
    
    $words = explode(' ', $data);
    $result = array();

    foreach ($words as $str) {
        $start = microtime(true);
        $stem = $stemmer->stem($str);
        $elapsedTime = microtime(true) - $start;
        array_push($result, ['kataAsal' => $str, 'hasilStem' => $stem, 'waktuEksekusi' => $elapsedTime]);
    }    
    print("<pre>".print_r($result,true)."</pre>");
}

function stemEhcs($data)
{
    echo 'data = '.$data.'<br>';
    $ehcs = new Ehcs();
    $stemming = $ehcs->checkDerivationPrefix($data);
    echo 'hasil stemming '.$stemming['stem'];
}

function stemGabungan($data)
{
    echo 'stemming with gabungan algorithm';
}

function normalizer($text)
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z]/im', ' ', $text);
    $text = preg_replace('/( +)/im', ' ', $text);

    return trim($text);
}
?>