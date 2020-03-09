  $(document).ready(function(){
        $('#kabupaten').on('change', function(){
            var kabupaten = $(this).val()
            if(kabupaten){
                $.ajax({
                    type:'POST',
                    url:'ajaxData.php',
                    data:{'kabupaten':kabupaten},
                    success:function(html){
                        $('#kecamatan').html(html)
                        $('#desa').html('<option value="">--Pilih Desa/Kelurahan--</option>')
                    }
                })
            }else{
                // $('#kecamatan').html('<option value="">Semua Kecamatan</option>')
                // $('#desa').html('<option value="">Semua Desa/Kelurahan</option>')
            }
        })

         $('#kecamatan').on('change', function(){
            var kecamatan = $(this).val()
            if(kecamatan){
                $.ajax({
                    type:'POST',
                    url:'ajaxData.php',
                    data:{'kecamatan':kecamatan},
                    success:function(html){
                        $('#desa').html(html)
                    }
                })
            }
        })

         $('#desa').on('change', function(){
            var desa = $(this).val()
            if(desa){
                $.ajax({
                    type:'POST',
                    url:'ajaxData.php',
                    data:{'desa':desa},
                    success:function(html){
                        $('#nks').html(html)
                    }
                })
            }
        })
        
    }); 