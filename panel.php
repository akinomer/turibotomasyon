<?php
    include('vt.php');

    $user = $db->customQuery("SELECT * FROM users WHERE rnd='".$_GET['rnd']."'")->getRow();

    if($_SESSION['login-'.$user->rnd]!=true){
        header('Location: index.php');
    }    

    $suresorgula = $db->customQuery('Select COUNT(*) as total, logindate from turib_auth where user_id="'.$user->id.'" GROUP BY id')->getRow();
    if($suresorgula->total > 0){
        $getvalues = $db->customQuery('Select * From turib_auth Where user_id="'.$user->id.'"')->getAll();
        foreach($getvalues as $key => $value){
            $from_time = strtotime(date('Y-m-d H:i:s'));
            $to_time = strtotime($value->logindate);
            $diff = round(abs($to_time - $from_time) / 60,2);
            if($diff > 700){
                $db->customQuery('DELETE FROM turib_auth WHERE user_id="'.$user->id.'"');
                ?>
                <script>
                    location.reload();
                </script>
                <?php
            }
        }
    }

    if($_GET['tip']=='smscodetalep'){
        $data = [
            'user_id' => $user->id
        ];
        $db->table('emirler')->insert($data);
        echo $db->lastInsertId();
        exit;
    }

    if($_GET['tip']=='ekle'){

        $varmibak = $db->customQuery('Select Count(*) As total, id From turib_auth Where turibusername="'.$_POST['turibusername'].'" And turibparola="'.$_POST['turibparola'].'" Group By id')->getRow();

        //echo 'Select Count(*) As total, id From turib_auth Where turibusername="'.$_POST['turibusername'].'" And turibparola="'.$_POST['turibparola'].'" Group By id';

        if($varmibak->total == 0){
            $data = [
                'user_id' => $user->id,
                'turibusername' => $_POST['turibusername'],
                'turibparola' => $_POST['turibparola'],
                'type' => $_POST['type'],
                'vergino' => $_POST['vergino']
            ];
            $db->table('turib_auth')->insert($data);
            echo $db->lastInsertId();
        }else{
            $data = [
                'user_id' => $user->id,
                'smscode' => "",
                'turibusername' => $_POST['turibusername'],
                'turibparola' => $_POST['turibparola'],
                'status'    => 0
            ];
            $db->table('turib_auth')->where('id','=',$varmibak->id)->update($data);
            echo $varmibak->id;
        }
        exit;
    }

    if($_GET['tip']=='smscode'){
        $db->customQuery('UPDATE turib_auth SET smscode="'.$_POST['smscode'].'" WHERE id="'.$_GET['last_alarm_id'].'"');
        exit;
    }
    
    
    if($_GET['tip']=='smsstatus_check'){
        $smsstatus_check = $db->customQuery('Select status From turib_auth Where id='.$_GET['last_alarm_id'])->getRow();
        echo $smsstatus_check->status;

        if($smsstatus_check->status==6){
            $db->table('turib_auth')->where('id','=',$_GET['last_alarm_id'])->delete();
        }

        if($smsstatus_check->status==4){
            $db->customQuery('UPDATE turib_auth SET status=100 WHERE id='.$_GET['last_alarm_id']);
        }
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
    </style>
</head>
<body>
<?php
    include('menu.php');
    ?>
  <div class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
            <table class="table">
                <!--<tr>
                    <td>
                        <?php 
                            echo $_SESSION['username-'.$user->rnd];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php 
                            echo $_SESSION['login-'.$user->rnd];
                        ?>
                    </td>
                </tr>-->
                <tr>
                    <td class="text-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio1" value="0" checked>
                            <label class="form-check-label" for="inlineRadio1">Gercek Kisi</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio2" value="2">
                            <label class="form-check-label" for="inlineRadio2">Sirket</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio3" value="3">
                            <label class="form-check-label" for="inlineRadio3">Sirket Temsilcisi</label>
                        </div>
                        <div class="form-check form-check-inline mb-3">
                            <input class="form-check-input" type="radio" name="type" id="inlineRadio4" value="4">
                            <label class="form-check-label" for="inlineRadio4">Acente</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <div class="form-group"> <label for="form16">Vergi No</label> <input type="text" class="form-control" id="vergino" placeholder="Vergi No"> </div>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <div class="form-group"> <label for="form16">TC Kimlik No</label> <input type='text' id='turibusername' class='form-control' placeholder='TC Kimlik No'> </div>
                    </td>
                </tr>
                <tr>
                    <td>
                    <div class="form-group"> <label for="form16">Parola</label>    <input type='password' id='turibparola' class='form-control' placeholder='Turib Parolaniz'></div>
                    </td>
                </tr>   
                <tr>
                    <td colspan="2"><button id="alarmbtn" type="button" class="form-control btn btn-secondary">Giris Yap</button> </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="satir_smscode" style="margin-top: 80px;display:none;">
                            <input type="text" name="smscode" id="smscode" class="form-control" placeholder="Telefonunuza gelecek SMS kodunu giriniz">
                            <button type="button" id="smscodebtn" class="form-control btn btn-primary mt-2">SMS Kodu Gönder</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="smscode_status" style="display:none;">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <?php
    include('footer.php');

  ?>


    <script>


        $(document).ready(function(){

            //if(document.querySelectorAll('[type="radio"]:checked')[0].value==0){
            $('.table tr').eq(1).css('display','none');
            //}
            
            $('input[type="radio"]').click(function(){
                console.log(document.querySelectorAll('[type="radio"]:checked')[0].value);
                for(var i=1; i <= 2; i++){
                    if(document.querySelectorAll('[type="radio"]:checked')[0].value==0 || document.querySelectorAll('[type="radio"]:checked')[0].value==4){
                        if(i==1){
                            $('.table tr').eq(i).css('display','none');
                        }else{
                            $('.table tr').eq(i).css('display','table-row');
                        }
                    }
                    if(document.querySelectorAll('[type="radio"]:checked')[0].value==2){
                        if(i==2){
                            $('.table tr').eq(i).css('display','none');
                        }else{
                            $('.table tr').eq(i).css('display','table-row');
                        }
                    }
                    if(document.querySelectorAll('[type="radio"]:checked')[0].value==3){
                        $('.table tr').eq(i).css('display','table-row');
                    }
                }
            })

            var last_alarm_id = 0;
            //alarmbtn click event
            $('#alarmbtn').click(function(){
                $("#smscode").val("");
                
                //get username and password
                var turibusername = $('#turibusername').val();
                var turibparola = $('#turibparola').val();
                var type = document.querySelectorAll('[type="radio"]:checked')[0].value;
                var vergino = $('#vergino').val();

                var data = {
                    'turibusername': turibusername,
                    'turibparola': turibparola,
                    'type': type,
                    'vergino': vergino
                };

                if(turibusername=='' || turibparola==''){
                    alert('Kullanici adi ve parola bos birakilamaz');
                    return;
                }else{
                    var rnd = localStorage.getItem("rnd");
                    $.ajax({
                        url: 'panel.php?tip=ekle&rnd='+rnd,
                        type: 'POST',
                        data: data,
                        success: function(response){
                            last_alarm_id = response;
                            $('#satir_smscode').show();
                            $('#smscode_status').show();
                            $('#smscode_status').html('<div class="alert alert-warning" role="alert">Giris yapilmasi bekleniyor.</div>');
                            //setinterval with 1 second


                            


                            var interval = setInterval(function(){
                                $.ajax({
                                    url: 'panel.php?tip=smsstatus_check&last_alarm_id='+response+'&rnd='+rnd,
                                    type: 'GET',
                                    success: function(data){
                                        if(data=='5'){
                                            $('#smscode_status').html('<div class="alert alert-danger" role="alert">Sure Doldu. SMS Kodu girilmedi</div>');
                                            $('#smscode').val("");
                                            clearInterval(interval);
                                        }
                                        if(data=='4'){
                                            $('#smscode_status').html('<div class="alert alert-danger" role="alert">SMS Kodu yanlis</div>');
                                            $('#smscode').val("");
                                            $('#smscode_status').html('');
                                            
                                        }
                                        if(data=='6'){
                                            $('#smscode_status').html('<div class="alert alert-danger" role="alert">Kullanici adi ya da parola yanlis</div>');
                                            $('#smscode').val("");
                                            clearInterval(interval);
                                        }
                                        if(data=='7'){
                                            $('#smscode_status').html('<div class="alert alert-success" role="alert">SMS kodunu tekrar girmek icin 30 saniye bekleyiniz</div>');
                                            $('#smscode').val("");
                                            clearInterval(interval);
                                        }
                                    }
                                });
                            },5000);
                        }
                    });
                }
            });

            $('#smscodebtn').click(function(){
            //get sms code
            var smscode = $('#smscode').val();
            //send sms code to server
            var rnd = localStorage.getItem("rnd");
            $.ajax({
                url: 'panel.php?tip=smscode&last_alarm_id='+last_alarm_id+'&rnd='+rnd,
                type: 'POST',
                data: {'smscode': smscode},
                success: function(response){
                    $('#smscode_status').html('<div class="alert alert-success" role="alert">SMS Kodu gonderildi. Onay icin bekleyiniz</div>');
                    //setInterval with 1 second
                    var interval = setInterval(function(){
                        $.ajax({
                            url: 'panel.php?tip=smsstatus_check&last_alarm_id='+last_alarm_id+'&rnd='+rnd,
                            type: 'GET',
                            success: function(response){
                                if(response=='5'){
                                    $('#smscode_status').html('<div class="alert alert-danger" role="alert">Sure Doldu. SMS Kodu girilmedi</div>');
                                    $('#smscode').val("");
                                    clearInterval(interval);
                                }
                                if(response=='4'){
                                    $('#smscode_status').html('<div class="alert alert-danger" role="alert">SMS Kodu yanlis</div>');
                                    setTimeout(function(){
                                        $('#smscode_status').html('<div class="alert alert-warning" role="alert">Sms kodunu tekrar deneyiniz</div>');
                                    },2000);
                                    clearInterval(interval);
                                    //$('#smscode').val("");
                                    //clearInterval(interval);
                                }
                                if(response=='2'){
                                    $('#smscode_status').html('<div class="alert alert-success" role="alert">SMS Kodu doğru, Alarm ekleme sayfasina yonlendiriliyorsunuz.</div>');
                                    $('#smscode_status').hide();
                                    $('#satir_smscode').hide();
                                    setTimeout(function(){
                                        window.location.href = 'addcriteria.php?rnd='+rnd;
                                    },2000);
                                    clearInterval(interval);
                                }
                            }
                        });
                    }, 5000);
                }
            });

        })
            
        })
    </script>
</body>

</html>