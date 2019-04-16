<?php

    include './connection.php';

    // query hasil stem
    $sqlStem = "SELECT id, kata, kata_dasar_1, kata_dasar_2, waktu_1, waktu_2, status_1, status_2, SUM(frekuensi) as frekuensi
                FROM `kata` INNER JOIN document_kata ON kata.id = document_kata.id_kata
                GROUP BY document_kata.id_kata";
    $sqlRataPersentase = "SELECT AVG(waktu_1),AVG(waktu_2), AVG(status_1),AVG(status_2), SUM(status_1),SUM(status_2), SUM(waktu_1), SUM(waktu_2) FROM kata";

    $queryStem = mysqli_query($conn, $sqlStem);
    $mysql = mysqli_query($conn, $sqlRataPersentase);

    $rataPersen = mysqli_fetch_assoc($mysql);


    // query dokumen
    $sqlDokumen = "SELECT * FROM document";
    $queryDokumen = mysqli_query($conn,$sqlDokumen);
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Aplikasi Stemming</title>

  <!-- Bootstrap Core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
  <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="css/stylish-portfolio.min.css" rel="stylesheet">
  <link href="datatables.min.css" rel="stylesheet">
  <style>
    .content-section{
      padding-top: 2.5rem;
      padding-bottom: 2.5rem;
      background-color: #fff!important;
    }
    #list_dokumen{
        display: block;
        margin-top: 50px;
    }
    #hasil_stem{
        display:none;
        margin-top: 50px;
    }
    .nav-item{
        cursor:pointer;
    }
    .active:hover{
        color:#fff!important;
    }
  </style>

</head>

<body id="page-top">

  <!-- Header -->
  <header class="masthead d-flex">
    <div class="container text-center my-auto">
      <h1 class="mb-1">Hasil Stemming</h1>
      <h3 class="mb-5">
        <em>Algoritma Nazief vs EHCS</em>
      </h3>

      <a href="#table_result" class="btn btn-xl btn-dark mr-4 lihatdokumen">Lihat Semua Dokumen</a>
      <a href="#table_result" class="btn btn-xl btn-light mr-4 hasilstem">Lihat Hasil Stem</a>
      <br><br>
      <a href="index.html" class="btn btn-xl btn-light mr-4">Stem Lagi...</a>

    </div>
    <div class="overlay"></div>
  </header>

  <!-- Table Result -->
  <section class="content-section bg-light" id="table_result">
    <div class="container text-center">
      <div class="row">
        <div class="col-lg-12 mx-auto">
        <nav class="nav nav-pills nav-fill">
            <a class="nav-item nav-link active lihatdokumen">Daftar Dokumen</a>
            <a class="nav-item nav-link hasilstem">Hasil Stemming</a>
        </nav>
        <div id="list_dokumen">
            <!-- table contains document goes here -->
            <table class="table_dokumen table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($doc = mysqli_fetch_assoc($queryDokumen)) {?>
                    <tr>
                        <td><?php echo $doc['id'] ?></td>
                        <td><?php echo $doc['document'] ?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <div id="hasil_stem">
            <!-- table contains stem result goes here -->
            <table class="table_stem table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kata</th>
                        <th>Stem Nazief</th>
                        <th>Stem Ehcs</th>
                        <th>Waktu Nazief</th>
                        <th>Waktu Ehcs</th>
                        <th>Status Nazief</th>
                        <th>Status Ehcs</th>
                        <th>Frekuensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($stem = mysqli_fetch_assoc($queryStem)) {?>
                    <tr>
                        <td><?php echo($stem['id']) ?></td>
                        <td><?php echo($stem['kata']) ?></td>
                        <td><?php echo($stem['kata_dasar_1']) ?></td>
                        <td><?php echo($stem['kata_dasar_2']) ?></td>
                        <td><?php echo($stem['waktu_1']) ?></td>
                        <td><?php echo($stem['waktu_2']) ?></td>
                        <td><?php echo($stem['status_1']) ?></td>
                        <td><?php echo($stem['status_2']) ?></td>
                        <td><?php echo($stem['frekuensi']) ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
        </div>
      </div>
      <b><?php echo "rata2 waktu Nazief dan Adriani : ".$rataPersen['AVG(waktu_1)'] ?> detik
      <br>
      <?php echo "rata2 waktu EHCS : ".$rataPersen['AVG(waktu_2)'] ?> detik
      <br>
      <b><?php echo "waktu Nazief dan Adriani : ".$rataPersen['SUM(waktu_1)'] ?> detik
      <br>
      <?php echo "waktu EHCS : ".$rataPersen['SUM(waktu_2)'] ?> detik
      <br>
      <?php echo "jumlah Keberhasilan Nazief dan Adriani : ".$rataPersen['SUM(status_1)'] ?>
      <br>
      <?php echo "Persentase Keberhasilan Nazief dan Adriani : ".$rataPersen['AVG(status_1)']*100 ?>%
      <br>
      <?php echo "jumlah Keberhasilan EHCS : ".$rataPersen['SUM(status_2)'] ?>
      <br>
      <?php echo "Persentase Keberhasilan EHCS : ".$rataPersen['AVG(status_2)']*100 ?>%</b>
      <br>
    </div>



  </section>


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded js-scroll-trigger" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for this template -->
  <script src="js/stylish-portfolio.min.js"></script>
  <script src="datatables.min.js"></script>
  <script>
    $(document).ready(function(){
        $('.table_dokumen').DataTable();
        $('.table_stem').DataTable();

        $('.lihatdokumen').on('click', function(){
            $('#hasil_stem').fadeOut(300);
            $('#list_dokumen').delay(250).fadeIn(500);
            $('.nav-item').removeClass('active');
            $('.nav-item.lihatdokumen').addClass('active');
        })
        $('.hasilstem').on('click', function(){
            $('#list_dokumen').fadeOut(300);
            $('#hasil_stem').delay(250).fadeIn(500);
            $('.nav-item').removeClass('active');
            $('.nav-item.hasilstem').addClass('active');
        })
    })
  </script>

</body>

</html>
