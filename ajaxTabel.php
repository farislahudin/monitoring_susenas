<?php
	$kab = null;
	$kec = null;
	$desa = null;
	$nks = null;
	$banding = null;
	$result = null;
	// if(!empty($_POST['perbandingan'])){
	// 	$banding = $_POST['perbandingan'];
	// }
	if(!empty($_POST['kabupaten'])){
		$kab = $_POST['kabupaten'];
	}
	if(!empty($_POST['kecamatan'])){
		$kec = $_POST['kecamatan'];
	}
	if(!empty($_POST['desa'])){
		$desa = $_POST['desa'];
	}
	if(!empty($_POST['nks'])){
		$desa = $_POST['nks'];
	}
	if(!empty($_POST['perbandingan'])){
		$banding = $_POST['perbandingan'];
	}
	if($banding == null){
		$result = null;
	}else{
		$result = perbandingan($banding, $kab, $kec, $desa, $nks);
	}
	// if(!empty($banding) && !empty($kab) && !empty($kec) && !empty($desa) && !empty($nks)){
		
	// }
	// print_r($kab);
	// print_r($kec);
	// print_r($desa);
	// print_r($nks);
	// print_r($banding);
	// print_r($result);
	// print_r($result[1][1]);
		// echo"<thead>";
  //                  echo "<tr>";
  //                     echo "<th>Variabel</th>";
  //                     echo"<th>Ruta 1</th>";
  //                     echo"<th>Ruta 2</th>";
  //                     echo"<th>Ruta 3</th>";
  //                     echo"<th>Ruta 4</th>";
  //                     echo"<th>Ruta 5</th>";
  //                     echo"<th>Ruta 6</th>";
  //                     echo"<th>Ruta 7</th>";
  //                     echo"<th>Ruta 8</th>";
  //                     echo"<th>Ruta 9</th>";
  //                     echo"<th>Ruta 10</th>";
  //                   echo"</tr>";
  //                 echo"</thead>";
	// }
	
?>