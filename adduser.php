<?php
include('vt.php');

$user = $db->customQuery("SELECT * FROM users WHERE rnd='".$_GET['rnd']."'")->getRow();


if($_SESSION['login-'.$user->rnd]!=true){
    header('Location: index.php');
}  

if($_GET['tip']=='sil'){
    $db->table('users')->where('id','=',$_GET['id'])->delete();
    header('Location: adduser.php');
}


if($_POST){
    $db->table('users')->insert($_POST);
    echo 'success';
    exit;
}

//$user = $db->customQuery("SELECT * FROM users WHERE username='".$_SESSION['username']."'")->getRow();
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
            margin-top: 30px !important;
            color: white;
            font-weight: 500;
        }
        .navbar-brand {
            margin-right: 0.2rem !important;
        }
    </style>
</head>
<body>
<?php include('menu.php'); ?>
<div class="py-5 text-center">
    <div class="container">
        <div class="row" >
            <div class="mx-auto col-lg-6 col-10">
                <h1>Kullanici Tanimla</h1>
                <p class="mb-3 notification">Kullanici adi ve parola tanimlayarak kullanici ekleyiniz</p>
                 <div class="form-group"> <label for="form16">Kullanici Adi</label> <input type="text" class="form-control" id="username" placeholder="Kullanici Adi"> </div>
                <div class="form-row">
                    <div class="form-group col-md-6"> <label for="form19">Parola</label> <input type="password" class="form-control" id="password" placeholder="••••"> </div>
                    <div class="form-group col-md-6"> <label for="form20">Parola Tekrari</label> <input type="password" class="form-control" id="passwordagain" placeholder="••••"> </div>
                </div>
                <button id="savebtn" type="button" class="btn btn-primary form-control">Kaydet</button>
            </div>
        </div>
    </div>
</div>
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered ">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Kullanici Adi</th>
                            <th>Sil</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $users = $db->customQuery('Select * From users Order By id Desc')->getAll();
                            foreach ($users as $key => $u){
                        ?>
                        <tr>
                            <th><?=$key+1?></th>
                            <td><?=$u->username?></td>
                            <td><a class="btn btn-outline-danger" href="adduser.php?tip=sil&id=<?=$u->id?>">Sil</a></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
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
        //check password and password again if the values are equal
        $('#savebtn').on('click',function(){
            if($('#password').val()!=$('#passwordagain').val()){
                $('.notification').html('<div class="alert alert-danger" role="alert">Parolalar uyusmuyor!</div>');
            }else{
                $.ajax({
                    url:'adduser.php',
                    type:'POST',
                    data:{
                        username:$('#username').val(),
                        password:$('#password').val()
                    },
                    success:function(data){
                        if(data=='success'){
                            $('.notification').html('<div class="alert alert-success" role="alert">Kullanici basariyla eklendi</div>');
                            //settimeout with 2 seconds
                            setTimeout(function(){
                                window.location.href='adduser.php';
                            },2000);
                        }else{
                            $('.notification').html('<div class="alert alert-danger" role="alert">Kullanici eklenemedi</div>');
                        }
                    }
                });
            }
        });

    });
</script>
</body>

</html>