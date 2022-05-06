<?php 
	$turib_auth = $db->customQuery("Select * From turib_auth Where user_id='".$user->id."'")->getRow();
?>
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">
            <!--<b><img class="logo" src="https://www.innovaart.com.tr/turib/wp-content/uploads/2021/03/beyazlogo28.png"></b>-->
        </a>
        <span class="logotext"><?php echo $turib_auth->ad_soyad; ?> <?php echo $turib_auth->sicil_no; ?></span>
        <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar16">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar16">
            <ul class="navbar-nav ml-auto">
                <?php if($user->type==1){ ?>
                    <li class="nav-item"> <a class="nav-link" href="adduser.php?rnd=<?=$user->rnd?>"><i class="fa fa-user-plus fa-fw"></i> Kullanici Ekle</a> </li>
                <?php } ?>
                <li class="nav-item"> <a class="nav-link" href="addcriteria.php?rnd=<?=$user->rnd?>">Kriter Ekle</a> </li>
                <li class="nav-item"> <a class="nav-link" href="criterias.php?rnd=<?=$user->rnd?>">Alarmlar</a> </li>
                <li class="nav-item"> <a class="nav-link" href="mevcutislemler.php">Tamamlanan Islemler</a> </li>
                <li class="nav-item"> <a id="logoutbtn" class="nav-link" href="logout.php?user_id=<?=$user->id?>&rnd=<?=$user->rnd?>">Cikis</a> </li>
            </ul>
        </div>
    </div>
</nav>