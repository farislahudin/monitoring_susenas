<?php
	include('dbconnection.php');
	// function console_log( $data ){
	//   echo '<script>';
	//   echo 'console.log('. json_encode( $data ) .')';
	//   echo '</script>';
	// }
	
	
	// $kec = $_POST["kecamatan"];
	if(!empty($_POST["kabupaten"])){
		echo '<option value="">--Pilih Kecamatan--</option>';
		$kab = $_POST["kabupaten"];
		$kecamatan = mysqli_query($db,'SELECT DISTINCT kecamatan FROM kaltara WHERE kabupaten LIKE "'.$kab.'"');
    	while ($row = mysqli_fetch_assoc($kecamatan)){
    		echo '<option value="'.$row['kecamatan'].'">'.$row['kecamatan'].'</option>';
    	
    }
	}elseif (!empty($_POST["kecamatan"])) {
		echo '<option value="">--Pilih Desa/Kelurahan--</option>';
		$kec = $_POST["kecamatan"];
			$desa_list = mysqli_query($db,'SELECT DISTINCT desa FROM kaltara WHERE kecamatan LIKE "'.$kec.'"');
			while ($row = mysqli_fetch_assoc($desa_list)){
	    	echo '<option value="'.$row['desa'].'">'.$row['desa'].'</option>';
	    }
	}elseif (!empty($_POST["desa"])) {
		echo '<option value="">--Pilih Nomor Kode Sampel--</option>';
		$desa = $_POST["desa"];
			$nks = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE desa LIKE "'.$desa.'"');
			while ($row = mysqli_fetch_assoc($nks)){
	    	echo '<option value="'.$row['nks'].'">'.$row['nks'].'</option>';
	    }
	}
?>