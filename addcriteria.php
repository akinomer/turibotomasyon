<?php
include('vt.php');

$user = $db->customQuery("SELECT * FROM users WHERE rnd='".$_GET['rnd']."'")->getRow();

//echo $user->id;

if($_SESSION['login-'.$user->rnd]!=true){
    header('Location: index.php');
}  

include('alimemirleri.php');

//$user = $db->customQuery("SELECT * FROM users WHERE username='".$_SESSION['username']."'")->getRow();

if($_GET['tip']=='ekle'){
    $sorgula = $db->customQuery("SELECT COUNT(*) AS total, id FROM kriterler WHERE elus='".$_POST['elus']."' AND user_id='".$user->id."' GROUP BY id")->getRow();
    if($sorgula->total > 0){
        echo "var|".$sorgula->id;
        //$_POST['smscode'] = "";
        //$_POST['status'] = 0;
        //$db->table('kriterler')->where('id','=',$sorgula->id)->update($_POST);
        $db->customQuery("Delete From kriterler Where elus='".$_POST['elus']."' AND user_id='".$user->id."'");
        $_POST['user_id'] = $user->id;
        //var_dump($_POST);
        $db->table('kriterler')->insert($_POST);
        echo "yok|".$db->lastInsertId();
    }else{
        $_POST['user_id'] = $user->id;
        //var_dump($_POST);
        $db->table('kriterler')->insert($_POST);
        echo "yok|".$db->lastInsertId();
    }

    $db->customQuery('UPDATE turib_auth SET status=0 WHERE user_id='.$user->id);

    exit;
}
if($_GET['tip']=='smscode'){
    $db->customQuery('UPDATE kriterler SET smscode="'.$_POST['smscode'].'" WHERE id="'.$_GET['last_alarm_id'].'"');
    exit;
}


if($_GET['tip']=='smsstatus_check'){
    $smsstatus_check = $db->customQuery('Select status From kriterler Where id='.$_GET['last_alarm_id'])->getRow();
    echo $smsstatus_check->status;
    exit;
}

if($_GET['tip']=='singalIDUpdate'){
    $db->customQuery('UPDATE users SET signal_id="'.$_GET['signalUID'].'" WHERE username="'.$_SESSION['username'].'"');
    exit;
}

if($_GET['tip']=='intervalGet'){
    
    //$db->customQuery('UPDATE users SET signal_id="'.$_GET['signalUID'].'" WHERE username="'.$_SESSION['username'].'"');

	$verilerigetir = $db->customQuery('Select * From turib_auth Where user_id='.$user->id.' Order By id DESC LIMIT 1')->getRow();

    $cookie = "";
    //echo $verilerigetir->cookies;
    $kuki = json_decode($verilerigetir->cookies,1);


    for($i=0; $i<count($kuki); $i++){
        $cookie .= $kuki[$i]['name']."=".$kuki[$i]['value'].";";
    }



    //$json = json_decode(file_get_contents("alimEmirleri.json"),1)['data'];
    $json = json_encode(json_decode(alimEmirleri($cookie),1)['data']);
	
    echo $json;
	
    //$json = json_decode(file_get_contents("alimEmirleri.json"),1)['data'];
	
    //echo json_encode($json);
    //exit;


	/*for($i = 0; $i < count($json); $i++){
			?>
			<tr id="satir-<?=$i?>" onmouseover="setBackground(this, '#12bbad');"
				onmouseout="restoreBackground(this);">
				<td><?=$json[$i]['Tarih']?></td>
				<td id="urunadi-<?=$i?>"><?=$json[$i]['UrunAdi']?></td>
				<td id="elus-<?=$i?>"><?=$json[$i]['ISIN_ELUS_KODU']?></td>
				<td id="miktar-<?=$i?>"><?=$json[$i]['Miktar']?></td>
				<td><?=$json[$i]['Kalan']?></td>
				<td id="fiyat-<?=$i?>"><?=$json[$i]['Fiyat']?></td>
				<td>

				</td>
				<td class='text-center'><?=$json[$i]['Avans']?></td>
				<td><?=$json[$i]['LisansNo']?></td>
				<td id="unvan-<?=$i?>"><?=$json[$i]['LisansliDepoAdi']?></td>
				<td id="lokasyon-<?=$i?>"><?=$json[$i]['Mensei']?></td>
			</tr>
	<?php
		}*/
	
    exit;
}

include('alarm_denetle.php');

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="panel.css">
    <!--<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>-->
    <script>
      /*window.OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
        OneSignal.init({
          appId: "69e82c00-75c9-4b5d-8f85-b7eece5fb0b4",
          safari_web_id: "",
          notifyButton: {
            enable: true,
          },
          subdomainName: "213-248-131",
        });
      });*/
    </script>
    <style>
        .logo{
            height: 50px;
        }
        .logotext{
            color: white;
            font-weight: 500;
        }
        .navbar-brand {
            margin-right: 0.2rem !important;
        }
        .table{
            font-size: 13px;
        }
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #CCC !important;
        }
        .pname{
            font-size: 15px;
            font-weight: 500;
        }
        #loading {
            position: fixed;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            text-align: center;
            opacity: 1;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
            opacity: 0.8;
            z-index: 100;
        }
    </style>
</head>
<body>
<div id="loading">
  <img id="loading-image" src="spinner.gif" alt="Loading..." />Yukleniyor...
</div>
<?php
include('menu.php');
?>
<div class="container-fluid">
    <div class="row">
		<!--<div class="col-md-12 mt-3">
			<button class="btn btn-primary" id="intervalStatus">Tablo Yenile</button>
            <span id="msg"></span>
		</div>-->
		
        <div class="col-md-10 mt-3">
			
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                    <th>Tarih</th>
                    <th>Urun Adi</th>
                    <th>Elus Kodu</th>
                    <th>Miktar(kg)</th>
                    <th>Kalan Miktar(kg)</th>
                    <th>Fiyat</th>
                    <th style="color: red;">Alarm Fiyat</th>
                    <th>Mahsup/Avans</th>
                    <th>Lisans No</th>
                    <th>Unvan</th>
                    <th>Lokasyon</th>
                    </tr>
                    <tr id="filterrow">
                    <th>Tarih</th>
                    <th>Urun Adi</th>
                    <th>Elus Kodu</th>
                    <th>Miktar(kg)</th>
                    <th>Kalan Miktar(kg)</th>
                    <th>Fiyat</th>
                    <th style="color: red;">Alarm Fiyat</th>
                    <th>Mahsup/Avans</th>
                    <th>Lisans No</th>
                    <th>Unvan</th>
                    <th>Lokasyon</th>
                    </tr>
                </thead>
                <tbody id='table_tbody_id'> 
            <?php
                
                $verilerigetir = $db->customQuery('Select * From turib_auth Where user_id='.$user->id.' Order By id DESC LIMIT 1')->getRow();
                //echo 'Select * From turib_auth Where user_id='.$user->id.' Order By id DESC LIMIT 1';
                $cookie = "";
                //echo $verilerigetir->cookies;
                $kuki = json_decode($verilerigetir->cookies,1);


                for($i=0; $i<count($kuki); $i++){
                    $cookie .= $kuki[$i]['name']."=".$kuki[$i]['value'].";";
                }

                //$json = json_decode(file_get_contents("alimEmirleri.json"),1)['data'];
                //echo alimEmirleri($cookie);
                $json = json_decode(alimEmirleri($cookie),1)['data'];
                for($i = 0; $i < count($json); $i++){
                    ?>
                    <tr id="satir-<?=$i?>" onmouseover="setBackground(this, '#12bbad');"
                        onmouseout="restoreBackground(this);">
                        <td><?=$json[$i]['Tarih']?></td>
                        <td id="urunadi-<?=$i?>"><?=$json[$i]['UrunAdi']?></td>
                        <td id="elus-<?=$i?>"><?=$json[$i]['ISIN_ELUS_KODU']?></td>
                        <td id="miktar-<?=$i?>"><?=$json[$i]['Miktar']?></td>
                        <td><?=$json[$i]['Kalan']?></td>
                        <td id="fiyat-<?=$i?>"><?=$json[$i]['Fiyat']?></td>
                        <td>

                        </td>
                        <td class='text-center'><?=$json[$i]['Avans']?></td>
                        <td><?=$json[$i]['LisansNo']?></td>
                        <td id="unvan-<?=$i?>"><?=$json[$i]['LisansliDepoAdi']?></td>
                        <td id="lokasyon-<?=$i?>"><?=$json[$i]['Mensei']?></td>
                    </tr>
            <?php
                }
            ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-2 mt-3">
            <table class="table" style="position: sticky; top: 0;">
                <tr>
                    <td colspan="2">
                        <h5>Kriter Belirleme Alani</h5>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"> Banka
                        <?php 
                            $banka_hesaplar = str_replace('"[',"[",$verilerigetir->banka_hesaplar);
                            $banka_hesaplar = str_replace(']"',"]",$banka_hesaplar);
                            $banka_hesaplar = json_decode($banka_hesaplar,1);
                        ?>
                        <select id="banka" class="form-control">
                            <option value="">Banka Hesap Seçiniz</option>
                            <?php
                                for($i=0; $i<count($banka_hesaplar); $i++){
                                    ?>
                                    <option value="<?=$banka_hesaplar[$i]?>"><?=$banka_hesaplar[$i]?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        * opsiyonel
                    </td>
                </tr>
                <tr>
                    <td>Unvan</td>
                    <td><span class="unvan" id="unvan"></span></td>
                </tr>
                <tr>
                    <td>Lokasyon</td>
                    <td><span class="lokasyon" id="lokasyon"></span></td>
                </tr>
                <tr>
                    <td>Satis Fiyat</td>
                    <td><span class="mevcut_fiyat" id="mevcut_fiyat"></span></td>
                </tr>
                <tr>
                    <td>Miktar (kg)</td>
                    <td><span class="miktar" id="miktar"></span></td>
                </tr>
                <tr>
                    <td>Urun Adi </td>
                    <td><span class="pname" id="urunadi"></span></td>
                </tr>
                <tr>
                    <td>Elus Kodu: </td>
                    <td><input type="text" id="elus" class="form-control" placeholder="Elus Kodu"></td>
                </tr>
                <tr>
                    <td>Fiyat: </td>
                    <td><input type="text" id="fiyat" class="form-control" placeholder="Fiyat"></td>
                </tr>
                <tr>
                    <td colspan="2"><button id="alarmbtn" type="button" class="form-control btn btn-outline-secondary">Alarm Ekle</button> </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<?php
    include('footer.php');

  ?>
<script>

    
    function satirTikla(){
        $('tr[id^="satir-"]').click(function(){
            //console.log($(this).closest('tr').index());
            console.log($(this).attr('id'));
            var id = $(this).attr('id').split('-')[1];
            console.log(id);
            $('#urunadi').html($('#urunadi-'+id).html());
            $('#elus').val($('#elus-'+id).html());
            $('#miktar').html($('#miktar-'+id).html());
            $('#fiyat').val($('#fiyat-'+id).html());
            $('#mevcut_fiyat').html($('#fiyat-'+id).html());
            $('#unvan').html($('#unvan-'+id).html());
            $('#lokasyon').html($('#lokasyon-'+id).html());
        });
    }
    

    function setBackground(me, color)
    {
        me.setAttribute("data-oldback", me.style.background);
        me.style.background=color;
        me.style.color="#fff";
    }

    function restoreBackground(me)
    {
        me.style.background = me.getAttribute("data-oldback");
        me.style.color = me.getAttribute("data-oldback");
    }

    function stopPropagation(evt) {
		if (evt.stopPropagation !== undefined) {
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true;
		}
	}
	
	var table;

    $(document).ready(function(){
		
        var rnd = localStorage.getItem("rnd");

		if("checksum" in localStorage){
			console.log('var');
		}else{
			localStorage.setItem('checksum', 0);
		}

		
		if($('#banka option').length==1){
			if(localStorage.getItem('checksum') < 2){
				var checksum = localStorage.getItem('checksum');
				checksum = parseInt(checksum) + 1;
				localStorage.setItem('checksum', checksum);
				location.reload();
			}else{
				localStorage.setItem('checksum', 0);
			}
			
			
		}
		
		
		$('#intervalStatus').click(function(){
            $('#msg').html('Yukleniyor');
			//$('tbody:eq(0)').html('<img src="spinner.gif">');
			$(this).attr("disabled", true);
            table.clear().draw();
            $.ajax({
                method: "GET",
                url: "addcriteria.php?tip=intervalGet&rnd=<?=$_GET['rnd']?>",
                success: function (data) {
                    console.log(data);
                    table.clear().draw()

                    data = JSON.parse(data);
                    for(var i=0; i < data.length; i++){
                        //console.log(i);
                        table
                            .row.add([
                                data[i]['Tarih'],
                                data[i]['UrunAdi'],
                                data[i]['ISIN_ELUS_KODU'],
                                data[i]['Miktar'],
                                data[i]['Kalan'],
                                data[i]['Fiyat'],
                                '',
                                data[i]['Avans'],
                                data[i]['LisansNo'],
                                data[i]['LisansliDepoAdi'],
                                data[i]['Mensei']
                                ])
                            .draw()
                            .node();
                            
                    } 

                    for(var i=0; i < data.length; i++){
                        $('#table_tbody_id tr:eq('+i+')').attr('onmouseover',"setBackground(this, '#12bbad');")
                        $('#table_tbody_id tr:eq('+i+')').attr('onmouseout',"restoreBackground(this);")
                        $('#table_tbody_id tr:eq('+i+')').attr('id','satir-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(0)').attr('id','sorting_-'+parseInt(i+1))
                        $('#table_tbody_id tr:eq('+i+') td:eq(1)').attr('id','urunadi-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(2)').attr('id','elus-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(3)').attr('id','miktar-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(5)').attr('id','fiyat-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(9)').attr('id','unvan-'+i)
                        $('#table_tbody_id tr:eq('+i+') td:eq(10)').attr('id','lokasyon-'+i) 
                    }

                    $('tr[id^="satir-"]').click(function(){
                        //console.log($(this).closest('tr').index());
                        //console.log($(this).attr('id'));
                        var id = $(this).attr('id').split('-')[1];
                        //console.log(id);
                        $('#urunadi').html($('#urunadi-'+id).html());
                        $('#elus').val($('#elus-'+id).html());
                        $('#miktar').html($('#miktar-'+id).html());
                        $('#fiyat').val($('#fiyat-'+id).html());
                        $('#mevcut_fiyat').html($('#fiyat-'+id).html());
                        $('#unvan').html($('#unvan-'+id).html());
                        $('#lokasyon').html($('#lokasyon-'+id).html());
                    });
                       
                    
                    $('#intervalStatus').attr("disabled", false);
                    $('#msg').html('');
                    $('#filterrow th input').each(function( index ) {
                        //console.log( index + ": " + $( this ).val() );
                        table.column( index )
                        .search( $( this ).val() )
                        .draw();
                    });
                    
                    var last_alarm_id = 0;
                    //creaate loop for all rows
                    var uzunluk = $('tr').length;
                    <?php

                        $arr = array();
                        $arr_fiyat = array();
                       
                        $cr = $db->customQuery("Select elus, fiyat From kriterler Where user_id=".$user->id)->getAll();
                        foreach ($cr as $c) {
                            array_push($arr_fiyat, $c->fiyat);
                            array_push($arr, $c->elus);
                        }
                        $elus = implode('","', $arr);
                        $elus = '"'.$elus.'"';
                        $alarm_fiyat = implode('","', $arr_fiyat);
                        $alarm_fiyat = '"'.$alarm_fiyat.'"';
                        
                    ?>
                    var index = -1;
                    var alarm_fiyat = [<?=$alarm_fiyat?>];
                    var arr = [<?=$elus?>];
                    for(var i=0; i < uzunluk; i++){
                        if(arr.includes($("tr:eq("+i+") td:eq(2)").html())){
                            index = arr.indexOf($("tr:eq("+i+") td:eq(2)").html());
                            $("tr:eq("+i+") td:eq(6)").html("<strong style='color: red;'>"+alarm_fiyat[index]+"</strong>");
                        }
                    }
                    
                    
                }
            });

			
		});
				
		
		

        $('#loading').hide();

        $('#myTable thead tr#filterrow th').each( function () {
            var title = $('#myTable thead th').eq( $(this).index() ).text();
            $(this).html( '<input class="form-control" type="text" onclick="stopPropagation(event);" placeholder="Ara '+title+'" />' );
        } );
    
        table = $('#myTable').DataTable({
            
			"dom": '<"top"i>rt<"bottom"flp><"clear">',
			"columns":[
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": true
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				},
				{
					"sortable": false
				}
			],
            iDisplayLength: -1,
            
            "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]]
        });

        $("#myTable thead input").on( 'keyup change', function () {
            table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
				
			$('tr[id^="satir-"]').click(function(){
				//console.log($(this).closest('tr').index());
				console.log($(this).attr('id'));
				var id = $(this).attr('id').split('-')[1];
				console.log(id);
				$('#urunadi').html($('#urunadi-'+id).html());
				$('#elus').val($('#elus-'+id).html());
				$('#miktar').html($('#miktar-'+id).html());
				$('#fiyat').val($('#fiyat-'+id).html());
				$('#mevcut_fiyat').html($('#fiyat-'+id).html());
				$('#unvan').html($('#unvan-'+id).html());
				$('#lokasyon').html($('#lokasyon-'+id).html());
			});
        } );

        var last_alarm_id = 0;
        //creaate loop for all rows
        var uzunluk = $('tr').length;
        <?php
            $arr = array();
            $arr_fiyat = array();
           
            $cr = $db->customQuery("Select elus, fiyat From kriterler Where user_id=".$user->id)->getAll();
            foreach ($cr as $c) {
                array_push($arr_fiyat, $c->fiyat);
                array_push($arr, $c->elus);
            }
            $elus = implode('","', $arr);
            $elus = '"'.$elus.'"';
            $alarm_fiyat = implode('","', $arr_fiyat);
            $alarm_fiyat = '"'.$alarm_fiyat.'"';
        ?>
        var index = -1;
        var alarm_fiyat = [<?=$alarm_fiyat?>];
        var arr = [<?=$elus?>];
        for(var i=0; i < uzunluk; i++){
            if(arr.includes($("tr:eq("+i+") td:eq(2)").html())){
                index = arr.indexOf($("tr:eq("+i+") td:eq(2)").html());
                $("tr:eq("+i+") td:eq(6)").html("<strong style='color: red;'>"+alarm_fiyat[index]+"</strong>");
            }
        }

        $('tr[id^="satir-"]').click(function(){
            //console.log($(this).closest('tr').index());
            console.log($(this).attr('id'));
            var id = $(this).attr('id').split('-')[1];
            console.log(id);
            $('#urunadi').html($('#urunadi-'+id).html());
            $('#elus').val($('#elus-'+id).html());
            $('#miktar').html($('#miktar-'+id).html());
            $('#fiyat').val($('#fiyat-'+id).html());
            $('#mevcut_fiyat').html($('#fiyat-'+id).html());
            $('#unvan').html($('#unvan-'+id).html());
            $('#lokasyon').html($('#lokasyon-'+id).html());
        });

        // click event for alarmbtn
        $('#alarmbtn').click(function(){

            var banka = $('#banka').val();
            var unvan = $('#unvan').html();
            var lokasyon = $('#lokasyon').html();
            var mevcut_fiyat = $('#mevcut_fiyat').html();
            var miktar = $('#miktar').html();
            var urunadi = $('#urunadi').html();
            var elus = $('#elus').val();
            var fiyat = $('#fiyat').val();

            console.log(banka);
            console.log(elus);
            console.log(fiyat);

            if(elus == '' || fiyat == ''){
                alert('Lütfen tüm alanları doldurunuz.');
            }else{
                //ajax request
                $.ajax({
                    url: 'addcriteria.php?tip=ekle&rnd='+rnd,
                    type: 'POST',
                    data: {
                        unvan: unvan,
                        lokasyon: lokasyon,
                        mevcut_fiyat: mevcut_fiyat,
                        miktar: miktar,
                        urunadi: urunadi,
                        elus: elus,
                        fiyat: fiyat,
                        banka: banka,
                        rnd: rnd
                    },
                    success: function(response, status){
                        console.log(status);
                        if(status=='success'){
                            //location.href = 'criterias.php';
                            alert('Alarm Kurulmustur.')
                        }
                    }
                });
            }

            

        })

        
       

    })
</script>
</body>

</html>