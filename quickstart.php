<?php
include("dbconnection.php");
  function get_from_pcl(){
  $values = file_get_contents('https://sheets.googleapis.com/v4/spreadsheets/1h9q45fnlchB1s2Ma-I6fHBooChIs7KAen1-ezF79HBg/values/Form%20Responses%201?key=AIzaSyCm4yY-i_KjXBmfEROSCoRmO7ePKrYQMDc');
  $values_arr = json_decode($values,true);
  $values_array = $values_arr["values"];
  return $values_array;
}

function get_from_pml(){
  $values = file_get_contents('https://sheets.googleapis.com/v4/spreadsheets/1h9q45fnlchB1s2Ma-I6fHBooChIs7KAen1-ezF79HBg/values/Form%20Responses%202?key=AIzaSyCm4yY-i_KjXBmfEROSCoRmO7ePKrYQMDc');
  $values_arr = json_decode($values,true);
  $values_array = $values_arr["values"];
  return $values_array;
}

function get_from_editor(){
  $values = file_get_contents('https://sheets.googleapis.com/v4/spreadsheets/1h9q45fnlchB1s2Ma-I6fHBooChIs7KAen1-ezF79HBg/values/Form%20Responses%203?key=AIzaSyCm4yY-i_KjXBmfEROSCoRmO7ePKrYQMDc');
  $values_arr = json_decode($values,true);
  $values_array = $values_arr["values"];
  return $values_array;
}

function transposeData($data)
{
  $retData = array();
    foreach ($data as $row => $columns) {
      foreach ($columns as $row2 => $column2) {
          $retData[$row2][$row] = $column2;
      }
    }
  return $retData;
}

function name_array($values){
  $t = transposeData($values);
  $result = array_column($t, null, '0');
  foreach ($result as $key => $subArr) {
      unset($subArr['0']);
      $result[$key] = $subArr;
  }
  return $result;
}

function get_filter_wilayah($filter_values, $kab = NULL, $kec = NULL, $desa = NULL, $nks = NULL, $noruta = NULL){
  if(!is_null($kab)){
    $filter_values = array_filter($filter_values, function($var) use ($kab){
      return (strpos($var[1],$kab)!==false);
    });
  }
  if(!is_null($kec)){
    $filter_values = array_filter($filter_values, function($var) use ($kec){
      return (strpos($var[2],$kec)!==false);
    });
  }
  if(!is_null($desa)){
    $filter_values = array_filter($filter_values, function($var) use ($desa){
      return (strpos($var[3],$desa)!==false);
    });
  }
  if(!is_null($nks)){
    $filter_values = array_filter($filter_values, function($var) use ($nks){
      return (strpos($var[4],$nks)!==false);
    });
  }
  if(!is_null($noruta)){
    $filter_values = array_filter($filter_values, function($var) use ($noruta){
      return (strpos($var[5],$noruta)!==false);
    });
  }
  return $filter_values;
}

function get_progress_wilayah($tingkatan, $prov = TRUE, $kab = NULL, $kec = NULL, $desa = NULL){
  $dbhost = 'localhost';
  $dbusername = 'root';
  $dbpassword = '';
  $dbname = 'susenas_kaltara';
  $db = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
  if ($tingkatan == 1){
    $values = get_from_pcl();
  }
  if ($tingkatan == 2){
    $values = get_from_pml();
  }
  if ($tingkatan == 3){
    $values = get_from_editor();
  }
  $t = name_array($values);
  if ($prov == TRUE){
    if (!is_null($kab)){
      if (!is_null($kec)){
        if (!is_null($desa)){
          $progres_desa = array();
            $filter_desa = get_filter_wilayah($values,$kab,$kec,$desa);
            $nks_query = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE desa LIKE "'.$desa.'"');
            $nks_list = array();
            $i = 0;
            while ($row = mysqli_fetch_assoc($nks_query)){
              $nks_list[$i] = $row['nks'];
              $i = $i+1;
            }
            if (!is_null($filter_desa)){
              // $t = transposeData($filter_desa);
          //get nama desa
              // $a = array_unique($t[7]);
              $a = $nks_list;
              sort($a);
              $i = 0;
              for($i==0;$i<count($a);$i++){
                $progres_desa_query = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE nks LIKE "'.$a[$i].'"');
                $j = 0;
                $b = null;
                while ($row = mysqli_fetch_assoc($progres_desa_query)){
                  $b[$j] = $row['nks'];
                  $j=$j+1;
                }
                $progres_desa_pembagi[$i] = count($b)*10;
                $progres_desa[$i] = get_filter_wilayah($filter_desa,$kab,$kec,$desa,$a[$i]);
                $progres_desa[$i] = count($progres_desa[$i]);
                $progres_desa_persen[$i]  = $progres_desa[$i]*100/$progres_desa_pembagi[$i];
                
              }
              $gabung = array($a,$progres_desa,$progres_desa_persen);
            }else{
              $gabung = array(null,null);
            }
        }else{
          $progres_kec = array();
            $filter_kec = get_filter_wilayah($values,$kab,$kec);
            $desa_query = mysqli_query($db,'SELECT DISTINCT desa FROM kaltara WHERE kecamatan LIKE "'.$kec.'"');
            $desa_list = array();
            $i = 0;
            while ($row = mysqli_fetch_assoc($desa_query)){
              $desa_list[$i] = $row['desa'];
              $i = $i+1;
            }
            if (!is_null($filter_kec)){
              // $t = transposeData($filter_kec);
          //get nama desa
              // $a = array_unique($t[6]);
              $a = $desa_list;
              sort($a);
              $i = 0;
              for($i==0;$i<count($a);$i++){
                $progres_kec_query = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE desa LIKE "'.$a[$i].'"');
                $j = 0;
                $b = null;
                while ($row = mysqli_fetch_assoc($progres_kec_query)){
                  $b[$j] = $row['nks'];
                  $j=$j+1;
                }
                $progres_kec_pembagi[$i] = count($b)*10;
                $progres_kec[$i] = get_filter_wilayah($filter_kec,$kab,$kec,$a[$i]);
                $progres_kec[$i] = count($progres_kec[$i]);
                $progres_kec_persen[$i]  = $progres_kec[$i]*100/$progres_kec_pembagi[$i];
              }
              $gabung = array($a,$progres_kec,$progres_kec_persen);
            }else{
              $gabung = array(null,null);
            }
        }
      }else{
        $progres_kab = array();
        $filter_kab = get_filter_wilayah($values,$kab);
        $kec_query = mysqli_query($db,'SELECT DISTINCT kecamatan FROM kaltara WHERE kabupaten LIKE "'.$kab.'"');
        $kec_list = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($kec_query)){
          $kec_list[$i] = $row['kecamatan'];
          $i = $i+1;
        }
        // $t = transposeData($filter_kab);
        //get nama kecamatan
        // $a = array_unique($t[5]);
        $a = $kec_list;
        sort($a);
        $i = 0;
        for($i==0;$i<count($a);$i++){
           $progres_kab_query = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kecamatan LIKE "'.$a[$i].'"');
          $j = 0;
          $b = null;
          while ($row = mysqli_fetch_assoc($progres_kab_query)){
            $b[$j] = $row['nks'];
            $j=$j+1;
          }
          $progres_kab_pembagi[$i] = count($b)*10;

          $progres_kab[$i] = get_filter_wilayah($filter_kab,$kab,$a[$i]);
          $progres_kab[$i] = count($progres_kab[$i]);
          $progres_kab_persen[$i]  = $progres_kab[$i]*100/$progres_kab_pembagi[$i];
        }
        $gabung = array($a,$progres_kab,$progres_kab_persen);
      }
      }else{
        $kab_query = mysqli_query($db,'SELECT DISTINCT kabupaten FROM kaltara');
        $kab_list = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($kab_query)){
          $kab_list[$i] = $row['kabupaten'];
          $i = $i+1;
        }
        $progres_prov = array();
        // $a = array_unique($t['Kabupaten']);
        
        $a = $kab_list;
        sort($a);
        $i = 0;
        for($i==0;$i<count($a);$i++){
          $progres_prov_query = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kabupaten LIKE "'.$a[$i].'"');
          $j = 0;
          $b = null;
          while ($row = mysqli_fetch_assoc($progres_prov_query)){
            $b[$j] = $row['nks'];
            $j=$j+1;
          }
          $progres_prov_pembagi[$i] = count($b)*10;
          $progres_prov[$i] = get_filter_wilayah($values,$a[$i]);
          $progres_prov[$i] = count($progres_prov[$i]);
          $progres_prov_persen[$i]  = $progres_prov[$i]*100/$progres_prov_pembagi[$i];
        }
        $gabung = array($a,$progres_prov,$progres_prov_persen);
      }
    }
    return $gabung;
  }

  function perbandingan($banding, $kab = NULL, $kec = NULL, $desa = NULL, $nks = NULL){
    $value_pcl = get_from_pcl();
    $value_pml = get_from_pml();
    $value_editor = get_from_editor();
    if($banding == 1){
      $filter_value_pcl = get_filter_wilayah($value_pcl, $kab, $kec, $desa, $nks, $noruta = NULL);
      $filter_value_pml = get_filter_wilayah($value_pml, $kab, $kec, $desa, $nks, $noruta = NULL);
    }else{
        if($banding == 2){
        $filter_value_pcl = get_filter_wilayah($value_pcl, $kab, $kec, $desa, $nks, $noruta = NULL);
        $filter_value_pml = get_filter_wilayah($value_editor, $kab, $kec, $desa, $nks, $noruta = NULL);
      }else{
        if($banding==3){
          $filter_value_pcl = get_filter_wilayah($value_pml, $kab, $kec, $desa, $nks, $noruta = NULL);
          $filter_value_pml = get_filter_wilayah($value_editor, $kab, $kec, $desa, $nks, $noruta = NULL);  
        }
      }  
    }
      $temp = 1;
      $kol = 0;
      $result = array();
      for ($temp = 1;$temp<11;$temp++){
        $filter_ruta_pcl = array_filter($filter_value_pcl, function($var) use ($temp){
          return ($var[5]==$temp);
        });
        $filter_ruta_pml = array_filter($filter_value_pml, function($var) use ($temp){
          return ($var[5]==$temp);
        });
        $filter_ruta_pcl = array_values($filter_ruta_pcl);
        if(!empty($filter_ruta_pcl)){
          $filter_ruta_pcl = $filter_ruta_pcl[0];
        }
        
        $filter_ruta_pml = array_values($filter_ruta_pml);
        if(!empty($filter_ruta_pml)){
          $filter_ruta_pml = $filter_ruta_pml[0];
        }
        
        $temp2 = 6;
        for($temp2 = 6;$temp2<24;$temp2++){
          if(empty($filter_ruta_pcl)){
            $aaa = null;
          }else{
            $aaa = $filter_ruta_pcl[$temp2];  
          }
          if(empty($filter_ruta_pml)){
            $bbb = null;
          }else{
            $bbb = $filter_ruta_pml[$temp2];  
          }
          if(is_null($aaa) || is_null($bbb)){
            $resl = 3;
          }else{
            if($aaa == $bbb){
              $resl = 1;
            }else{
              $resl = 2;
            }
          }
        $result[$temp][$kol] = $resl;
        $kol = $kol+1;
        }
        $kol = 0;
      }
      return $result;
  }
  // $filter_value_pcl = get_filter_wilayah(get_from_pcl(), $kab, $kec, $desa, '15033', $noruta = NULL);
  // $filter_value_pml = get_filter_wilayah(get_from_pml(), $kab, $kec, $desa, '15033', $noruta = NULL);
  // $temp = 1;
  // $kol = 0;
  // $result = array();
  // for ($temp = 1;$temp<11;$temp++){
  //   $filter_ruta_pcl = array_filter($filter_value_pcl, function($var) use ($temp){
  //     return ($var[8]==$temp);
  //   });
  //   $filter_ruta_pml = array_filter($filter_value_pml, function($var) use ($temp){
  //     return ($var[8]==$temp);
  //   });
  //   $filter_ruta_pcl = array_values($filter_ruta_pcl);
  //   if(!empty($filter_ruta_pcl)){
  //     $filter_ruta_pcl = $filter_ruta_pcl[0];
  //   }
    
  //   $filter_ruta_pml = array_values($filter_ruta_pml);
  //   if(!empty($filter_ruta_pml)){
  //     $filter_ruta_pml = $filter_ruta_pml[0];
  //   }
    
  //   $temp2 = 9;
  //   for($temp2 = 9;$temp2<31;$temp2++){
  //     if(empty($filter_ruta_pcl)){
  //       $aaa = null;
  //     }else{
  //       $aaa = $filter_ruta_pcl[$temp2];  
  //     }
  //     if(empty($filter_ruta_pml)){
  //       $bbb = null;
  //     }else{
  //       $bbb = $filter_ruta_pml[$temp2];  
  //     }
  //     if(is_null($aaa) || is_null($bbb)){
  //       $resl = 3;
  //     }else{
  //       if($aaa == $bbb){
  //         $resl = 1;
  //       }else{
  //         $resl = 2;
  //       }
  //     }
  //   $result[$temp][$kol] = $resl;
  //   $kol = $kol+1;
  //   }
  //   $kol = 0;
  // }
  // $a = get_from_pml();
  // print_r($a[0][9]);
  // include("dbconnection.php");
  // $kec = "TANA TIDUNG";
  // $kecamatan = mysqli_query($db,'SELECT DISTINCT nks FROM kaltara WHERE kabupaten LIKE "'.$kec.'"');
  // $i = 0;
  // while ($row = mysqli_fetch_assoc($kecamatan)){
  //   $a[$i] = $row['nks'];
  //   $i=$i+1;
  // }
  // $a = get_progress_wilayah(1,$prov = TRUE, "BULUNGAN");
  // print_r($a);
  // $a = perbandingan(2, $kab = "BULUNGAN", $kec = "TANJUNG SELOR", $desa = "TANJUNG SELOR HILIR", $nks = "15040");
  // print_r($a);
?>