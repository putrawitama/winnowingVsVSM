<?php

    require_once('PreProcessing.php');
    require_once('winnowing/Kgram.php');
    require_once('winnowing/Window.php');
    require_once('winnowing/Winnowing.php');
    require_once('winnowing/JaccardCoeficients.php');

    $stemming = isset($_GET["pre"]) ? (in_array("stemming", $_GET["pre"]) ? true : false) : false;
    $whitespaces = isset($_GET["pre"]) ? (in_array("whitespace", $_GET["pre"]) ? true : false) : false;
    $k = intval($_GET["k"]);
    $hashBasePrime = intval($_GET["prime"]);
    $w = intval($_GET["w"]);

    //teksnya
    $teks1 = $_GET["teks1"];
    $teks2 = $_GET["teks2"];

    //================================================================================= PreProcessing

    $preprocess1 = new PreProcessing($teks1, $whitespaces, $stemming);
    $preprocess2 = new PreProcessing($teks2, $whitespaces, $stemming);

    $preprocessText1 = $preprocess1->getResult();
    $preprocessText2 = $preprocess2->getResult();

    //================================================================================= Kgram
    
    $kgram1 = new Kgram($k, $hashBasePrime, $preprocessText1);
    $kgram2 = new Kgram($k, $hashBasePrime, $preprocessText2);

    $kgramResult1  = $kgram1->getResult();
    $kgramResult2  = $kgram2->getResult();

    //================================================================================= Window

    $window1 = new Window($w, $kgramResult1);
    $window2 = new Window($w, $kgramResult2);

    $windowResult1 = $window1->getResult();
    $windowResult2 = $window2->getResult();

    //================================================================================= Winnowing

    $winnowing1 = new Winnowing($windowResult1);
    $winnowing2 = new Winnowing($windowResult2);

    $fingerprint1 = $winnowing1->getResult();
    $fingerprint2 = $winnowing2->getResult();

    //================================================================================= Similarity JaccardCoeficients
 
    $similarity = new JaccardCoeficients($fingerprint1, $fingerprint2);
    $result =  $similarity->getResult();

    // echo '<pre>';
    // var_dump($fingerprint1);
    // var_dump($arrayTeks);
    $windowTampil1 = [];
    $windowTampil2 = [];
    foreach ($windowResult1 as $key) {
        array_push($windowTampil1, join(", ", $key));
    }

    foreach ($windowResult2 as $key) {
        array_push($windowTampil2, join(", ", $key));
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hasil</title>

    <link rel="stylesheet" href="./asset/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <span class="navbar-brand mb-0 h1">Winnowing vs VSM</span>
    </nav>

    <div class="container mt-5">
        <div class="card mb-5">
            <div class="card-body">
                <h3>Presentase Kemiripan: <?php echo  number_format($result,2) ?>%</h3>
                <hr>

                <div class="row mt-3 mb-3">
                    <div class="col-12">
                        <h5>PreProcessing</h5>

                        <div class="form-group">
                            <label>teks 1</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6"><?php echo $preprocessText1?></textarea>
                        </div>
                        <div class="form-group">
                            <label>teks 2</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6"><?php echo $preprocessText2?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-12">
                        <h5>K-Gram</h5>

                        <div class="form-group">
                            <label>teks 1</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6">[<?php echo join("], [", $kgramResult1["string"])?>]</textarea>
                        </div>
                        <div class="form-group">
                            <label>teks 2</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6">[<?php echo join("], [", $kgramResult2["string"])?>]</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h5>Rolling Hash</h5>

                        <div class="form-group">
                            <label>teks 1</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6"><?php echo join(", ", $fingerprint1)?></textarea>
                        </div>
                        <div class="form-group">
                            <label>teks 2</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="6"><?php echo join(", ", $fingerprint2)?></textarea>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="./asset/js/bootstrap.min.js"></script>
</body>
</html>