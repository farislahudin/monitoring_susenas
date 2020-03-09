<?php
	
	// include("quickstart.php");
	$kab = null;
	$kec = null;
	$desa = null;
	if(!empty($_POST['kabupaten'])){
		$kab = $_POST['kabupaten'];
	}
	if(!empty($_POST['kecamatan'])){
		$kec = $_POST['kecamatan'];
	}
	if(!empty($_POST['desa'])){
		$desa = $_POST['desa'];
	}
	$dataset = get_progress_wilayah($asal, $prov = TRUE, $kab, $kec, $desa);
	$label = json_encode($dataset);
	
	// if(!empty($desa)){
	// 	$pembagi = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE desa LIKE "'.$desa.'"');	
	// }elseif(!empty($kec)){
	// 	$pembagi = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kecamatan LIKE "'.$kec.'"');
	// }elseif (!empty($kab)) {
	// 	$pembagi = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kabupaten LIKE "'.$kab.'"');
	// }else{
	// 	$pembagi = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kecamatan LIKE "'.$kec.'"');	
	// }
	
	// print_r($label);
	echo "<script type=\"text/javascript\">\n";
	echo "var label = ${label};\n";
	echo "</script>\n";
	// print_r($dataset);
	// print_r($label);
	
?>