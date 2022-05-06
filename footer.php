<style type="text/css">
    .bold{
        font-weight: bold;
    }
</style>
<script src="https://jsuites.net/v4/jsuites.js"></script>

<script>

function HandleOnClose(){
	$.get("logout.php", function(data, status){
	    console.log("Data: " + data + "\nStatus: " + status);
	  });
}


$(window).on("˜", function(e) {
 	   //HandleOnClose();   //do something
});

	
</script>
<script>


$(document).ready(function(){
    var rnd = localStorage.getItem("rnd");

	$('button[id^="filter_fiyat-"]').click(function(){
		var id = $(this).attr('id').split('-')[1];
		console.log($('#fiyat-'+id).html());
		$('#modaltitle').html($('#elus-'+id).html());
		$('#fiyat_box').val($('#fiyat-'+id).html());
		$('#fiyat_id').val(id);
		$("#banka option[value='"+$('#banka-'+id).html()+"']").prop('selected', true);
		$('#myModal').modal('show');
	});
	
	$('#fiyat_update_btn').click(function(){
		var id = $('#fiyat_id').val();
		var fiyat = $('#fiyat_box').val();
		var banka = $('#banka').val();
		$.get("criterias.php?tip=fiyatguncelle&id="+id+"&fiyat="+fiyat+"&banka="+banka, function(data, status){
			$('#fiyat-'+id).html(fiyat);
			$('#banka-'+id).html(banka);
			$('#myModal').modal('hide');
		});
	});

    $('#onay_btn').click(function(){
        var onaylanan_banka = $('#onaylanan_banka').val();
        var onay_alarm_id = $('#onay_alarm_id').val();
        var miktar_box_onay = $('#miktar_box_onay').val().replace(/[^0-9]/g, '');
        console.log(miktar_box_onay);
        if(onaylanan_banka=='' || miktar_box_onay=='' || miktar_box_onay==0){
            alert('Lutfen bilgileri doldurunuz');
        }
        else{
            $.get("criterias.php?tip=onayla&id="+onay_alarm_id+'&banka='+onaylanan_banka+'&miktar_onay='+miktar_box_onay, function(data, status){
                console.log(data);
                $('#myModal2').modal('hide');
            });
        }
    });

    $('#reddet_btn').click(function(){
        var onay_alarm_id = $('#onay_alarm_id').val();
        $.get("criterias.php?tip=reddet&id="+onay_alarm_id+'&rnd='+rnd, function(data, status){
            console.log(data);
            $('#myModal2').modal('hide');
            location.reload();
        });
    })

    $("#myModal2").on("hide.bs.modal", function () {
        location.reload();
    });
    

    var interval = setInterval(function(){ 
        $.get("criterias.php?tip=alarm&rnd="+rnd+'&user_id=<?=$user->id?>', function(data, status){
            console.log(data);

            if(data!="false"){
                
                clearInterval(interval);

                var cisin = JSON.parse(data);

                if(cisin['rnd']==rnd){
                    var bMusic = new Audio('./alarm.mp3');
                    //bMusic.play()
                    $('.modal-body:eq(1) b:eq(0)').css('font-weight','bold');
                    $('.modal-body:eq(1) b:eq(2)').css('font-weight','bold');
                    $('.modal-body:eq(1) b:eq(3)').css('font-weight','bold');
                    console.log(cisin);
                    $('#onay_alarm_id').val(cisin['id']);
                    $('#myModal2 .modal-title').html(cisin['urunadi']+'<br><small>'+cisin['elus']+'</small>')
                    $('#kalan_miktar_onay').html(cisin['kalanmiktar'])
                    $('#fiyat_box_onay').html(cisin['bulunanfiyat']);
                    $('#lokasyon_onay').html(cisin['lokasyon']);
                    $('#unvan_onay').html(cisin['unvan']);
                    $('#miktar_box_onay').html(cisin['bulunanmiktar']);
                    if(cisin['banka']!=null){
                        $("#banka_onay option[value='"+cisin['banka']+"']").prop('selected', true);
                    }
                    $('#myModal2').modal('show');
                }
                

            }
        });
    }, 1000);//

});
</script>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"  id='modaltitle'>Modal Header</h4>
		<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
		<input type="hidden" id="fiyat_id">
		<p>
			 Banka
			<?php 
				$banka = $db->customQuery('Select banka_hesaplar From turib_auth Where user_id='.$user->id)->getRow();
				$banka_hesaplar = str_replace('"[',"[",$banka->banka_hesaplar);
				$banka_hesaplar = str_replace(']"',"]",$banka_hesaplar);
				$banka_hesaplar = json_decode($banka_hesaplar,1);
			?>
			<select id="banka" class="form-control">
				<option value="">Banka Hesap Seçiniz</option>
				<?php
					for($i=0; $i < count($banka_hesaplar); $i++){
						?>
						<option value="<?=$banka_hesaplar[$i]?>"><?=$banka_hesaplar[$i]?></option>
						<?php
					}
				?>
			</select>
			<small><b style="color: red;">* Bos olmamali</b></small>
		</p>
        <p><input type="text" id="fiyat_box" class="form-control"></p>
		<p><button id="fiyat_update_btn" class="form-control btn btn-secondary">Guncelle</button></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
    </div>

  </div>
</div>
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="onay_alarm_id">
        <p>
            <b>Banka</b>
            <?php 
                $banka = $db->customQuery('Select banka_hesaplar From turib_auth Where user_id='.$user->id)->getRow();
                $banka_hesaplar = str_replace('"[',"[",$banka->banka_hesaplar);
                $banka_hesaplar = str_replace(']"',"]",$banka_hesaplar);
                $banka_hesaplar = json_decode($banka_hesaplar,1);
            ?>
            <select id="onaylanan_banka" class="form-control">
                <option value="">Banka Hesap Seçiniz</option>
                <?php
                    for($i=0; $i < count($banka_hesaplar); $i++){
                        ?>
                        <option value="<?=$banka_hesaplar[$i]?>"><?=$banka_hesaplar[$i]?></option>
                        <?php
                    }
                ?>
            </select>
            <small><b style="color: red;">* Bos olmamali</b></small>
        </p>
        <p><b>Unvan</b><br><span id="unvan_onay"></span></p>
        
        <p><b>Lokasyon</b><br><span id="lokasyon_onay"></span></p>

        <p><b>Kalan Miktari</b><br><span id="kalan_miktar_onay"></span></p>
        
        <p><b class="bold">Fiyati</b><br><span id="fiyat_box_onay"></span> TL</p>
        
        <p><b class="bold">Miktari</b><br><input data-mask='#.##0,00' id="miktar_box_onay" class="form-control"> Kg</p>
        <p>
            <div class="row">
                <div class="col-md-9">
                    <button id="onay_btn" class="form-control btn btn-secondary">Onayla</button>
                </div>
                <div class="col-md-3">
                    <button id="reddet_btn" class="form-control btn btn-danger">Reddet</button>
                </div>
            </div>
            
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
    </div>

  </div>
</div>