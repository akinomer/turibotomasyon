<?php
include('vt.php');

$user = $db->customQuery("SELECT * FROM users WHERE rnd='".$_GET['rnd']."'")->getRow();

if($_SESSION['login-'.$user->rnd]!=true){
    header('Location: index.php');
}



if($_GET['tip']=='ekle'){
    $_POST['user_id'] = $user->id;
    $db->table('kriterler')->insert($_POST);
    exit;
}

if($_GET['tip']=='durdur'){
    $db->table('kriterler')->where('id','=',$_GET['id'])->update(['status' => 10]);
}

if($_GET['tip']=='baslat'){
    $db->table('kriterler')->where('id','=',$_GET['id'])->update(['status' => 1]);
}

if($_GET['tip']=='fiyatguncelle'){
    $db->table('kriterler')->where('id','=',$_GET['id'])->update(['fiyat' => $_GET['fiyat'], 'banka' => $_GET['banka']]);
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
        .table{
            font-size: 14px;
        }
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #CCC !important;
        }
        .pname{
            font-size: 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>
<?php
include('menu.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php $alarms = $db->customQuery("Select * From kriterler Where user_id=".$user->id." ORDER BY id DESC")->getAll(); ?>
            <table class="table">
                <thead>
                    <th>Unvan</th>
                    <th>Lokasyon</th>
                    <th>ELUS Kodu</th>
					<th>Urun Adi</th>
                    <th>Miktar(kg)</th>
                    <th>Banka</th>
                    <th class="text-right">Satis Fiyati</th>
                    <th class="text-right">Alarm Fiyati</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </thead>
                <tbody>
                <?php foreach ($alarms as $key => $value){ ?>
                <tr>
                    <td><?=$value->unvan?></td>
                    <td><?=$value->lokasyon?></td>
                    <td id='elus-<?=$value->id?>'><?=$value->elus?></td>
					<td><?=$value->urunadi?></td>
                    <td><?=$value->miktar?></td>
                    <td id='banka-<?=$value->id?>'><?=$value->banka?></td>
                    <td><span style="float: right;"><?=$value->mevcut_fiyat?> TL </span></td>
                    <td>
						<button type='button' id="filter_fiyat-<?=$value->id?>" class='form-control btn btn-secondary btn-sm'><span id="fiyat-<?=$value->id?>"><?=$value->fiyat?></span> TL <i class="fa fa-pencil"></i></button>
					</td>
                    <td>Durum</td>
                    <td>
                        <?php if($value->status!=10){ ?>
                            <a title='Islemi durdurmak icin tiklayiniz' href="?tip=durdur&id=<?=$value->id?>&rnd=<?=$_GET['rnd']?>" class="btn btn-outline-warning btn-sm"><i class="fa fa-stop"></i> DURDUR</a>
                        <?php }else{ ?>
                            <a title='Islemi baslatmak icin tiklayiniz' href="?tip=baslat&id=<?=$value->id?>&rnd=<?=$_GET['rnd']?>" class="btn btn-outline-danger btn-sm"><i class="fa fa-play"></i> BASLAT</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<?php
    include('footer.php');
?>
</body>
</html>