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

    if ($_POST['algorithm']==='gabungan') {
        stemGabungan();
    }
}

function postDocument($data)
{
    global $conn;
    $query = "INSERT INTO document(document) VALUES('$data')";    

    $result = mysqli_query($conn,$query);

    if ($result) {
        $id_doc = mysqli_insert_id($conn);
        
        $normalized_word = normalizer($data);
        $doc_word = explode(' ', $normalized_word);
        
        $arr = ['id_doc'=>$id_doc, 'array'=>$doc_word];
        postKata($arr);
    } else{
        die("Insert gagal ".mysqli_error($conn));
    }
}

function postKata($kata){
    global $conn;
    // echo 'postKata masuk'.'<br>';
    foreach ($kata['array'] as $word) {
        $find = findKata($word);
        
        if ($find['ketemu']) {
            // echo 'findKata ketemu'.'<br>';
            // if (mysqli_num_rows($find['query']) > 0) {
                $id_doc = $kata['id_doc'];
            //     $row = mysqli_fetch_assoc($find) or die(mysqli_error());
            //     $id_kata = $row['id'];
                $id_kata = $find['id_kata'];
                $arr = ['id_doc'=>$id_doc, 'id_kata'=>$id_kata];
                // updateFrekuensi($arr);
                $findInDocumentKata = findInDocumentKata($arr);
                if ($findInDocumentKata) {
                    updateFrekuensi($arr);
                } else{
                    createDocumentKata($arr);
                }
            // }
            
        } else{
            // echo 'findKata tidak ketemu, buat baru'.'<br>';
            $id_doc = $kata['id_doc'];
            $query = "INSERT INTO kata (kata) VALUES ('$word')";
            mysqli_query($conn, $query) or die(mysqli_error($conn));
            $id_kata = mysqli_insert_id($conn);

            $arr = ['id_doc'=>$id_doc, 'id_kata'=>$id_kata];
            createDocumentKata($arr);
        }
    }
}

function findInDocumentKata($arr)
{
    global $conn;
    $id_doc = $arr['id_doc'];
    $id_kata = $arr['id_kata'];
    $sql = "SELECT * FROM document_kata WHERE id_document='$id_doc', id_kata='$id_kata'";
    if (mysqli_query($conn, $sql)) {
        // word found, just update frequency.
        return true;
    } else{
        // word not found, create a new one.
        return false;
    }
}

function createDocumentKata($arr)
{
    // echo 'createDocumentKata masuk <br>';
    global $conn;
    $id_kata = $arr['id_kata'];
    $id_doc = $arr['id_doc'];
    $query = "INSERT INTO document_kata (id_document, id_kata, frekuensi) VALUES ('$id_doc', '$id_kata', 1)";
    $result = mysqli_query($conn, $query);
    if ($result) {
        // echo 'createDocumentKata berhasil<br><br><br>';
    } else{
        // echo 'createDocumentKata gagal<br><br><br>';
    }
}

function updateFrekuensi($kata)
{
    // echo 'update frekuensi masuk <br>';
    global $conn;
    $id_kata = $kata['id_kata'];
    $id_document = $kata['id_doc'];

    $sql = "UPDATE document_kata SET frekuensi = frekuensi +1 WHERE id_kata='$id_kata' AND id_document='$id_document'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo (gettype($result));
        if (mysqli_num_rows($result)==0) {
            echo '0 row affected. creating new record';
            createDocumentKata($kata);
        }
    } else{
        // echo 'update frekuensi gagal'.mysqli_error($conn).' <br><br>';
    }
}

function findKata($kata)
{
    global $conn;
    $query = "SELECT * FROM kata WHERE (kata='$kata')";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result)>0) {
        $row = mysqli_fetch_assoc($result);
        return ['ketemu'=>true, 'id_kata'=>$row['id']];
    } else{
        return ['ketemu'=>false];
    }
    // return mysqli_query($conn, $query);
}

function getAllKata()
{
    global $conn;
    $query = "SELECT * FROM kata";
    $result = mysqli_query($conn, $query);
    $row = array();
    if (mysqli_num_rows($result)>0) {
        while ($kata = mysqli_fetch_assoc($result)) {
            array_push($row, $kata);
        }
        return $row;
    } else{
        return mysqli_error($conn);
    }
}

function stemNazief($data)
{
    global $stemmer;
    $dictionary = __DIR__ . './vendor/sastrawi/sastrawi/data/kata-dasar.txt';
    $dict_kata_dasar = explode("\n", file_get_contents($dictionary));
    
    $start = microtime(true);
    $stem = $stemmer->stem($data['kata']);
    $elapsedTime = number_format((microtime(true) - $start),4,'.','');
    
    if (in_array($stem, $dict_kata_dasar)) {
        return ['kata_asal' => $data['kata'], 'stem' => $stem, 'waktu' => $elapsedTime, 'status' => 1];
    } else{
        return ['kata_asal' => $data['kata'], 'stem' => $stem, 'waktu' => $elapsedTime, 'status' => 0];
    }
}

function stemEhcs($data)
{
    $ehcs = new Ehcs();
    $start = microtime(true);
    $stem = $ehcs->process($data['kata']);
    $elapsedTime = number_format((microtime(true) - $start),4,'.','');

    if ($stem['root']) {
        return ['kata_asal' => $data['kata'], 'stem' => $stem['stem'], 'waktu' => $elapsedTime, 'status' => 1];
    } else{
        return ['kata_asal' => $data['kata'], 'stem' => $stem['stem'], 'waktu' => $elapsedTime, 'status' => 0];
    }
}

function stemGabungan()
{
    global $conn;
    echo 'stem gabungan<br><br>';
    $list = getAllKata();

    foreach ($list as $kata) {
        $nazief = stemNazief($kata);
        $ehcs = stemEhcs($kata);
        $result = ['id' => $kata['id'], 'kata' => $kata['kata'], 'stem_nazief' => $nazief['stem'], 'stem_ehcs' => $ehcs['stem'], 'waktu_nazief' => $nazief['waktu'], 'waktu_ehcs' => $ehcs['waktu'], 'status_nazief' => $nazief['status'], 'status_ehcs' => $ehcs['status']];
        updateKata($result);
    }        

}

function updateKata($kata)
{
    global $conn;

    $id = $kata['id'];
    $kata_dasar_1 = $kata['stem_nazief'];
    $kata_dasar_2 = $kata['stem_ehcs'];
    $waktu_1 = $kata['waktu_nazief'];
    $waktu_2 = $kata['waktu_ehcs'];
    $status_1 = $kata['status_nazief'];
    $status_2 = $kata['status_ehcs'];

    $sql = "UPDATE kata SET kata_dasar_1='$kata_dasar_1',kata_dasar_2='$kata_dasar_2',waktu_1='$waktu_1',waktu_2='$waktu_2',status_1='$status_1',status_2='$status_2' WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        // echo $kata['kata'].' telah berhasil diupdate<br>';
        // words updated successfully
        header('location:result.php');
    } else{
        // echo $kata['kata'].' gagal diupdate <br>';
        // words failed to update.
    }
    
}

function normalizer($text)
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z]/im', ' ', $text);
    $text = preg_replace('/( +)/im', ' ', $text);

    return trim($text);
}
?>