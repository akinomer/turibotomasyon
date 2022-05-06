<?php
    include('vt.php');
    if($_POST){

        $sql = 'Select COUNT(*) AS total, id From users Where username="'.$_POST['username'].'" And password="'.$_POST['password'].'" GROUP BY id';
        
        $users = $db->customQuery($sql)->getRow();

        if($users->total > 0){

            $rnd = rand(100000, 999999);
            
            $data = [
              'rnd' => $rnd
            ];

            $db->customQuery("Update users Set rnd='".$rnd."' Where id=".$users->id);

            //echo "Update users Set rnd='".$rnd."' Where id=".$users->id;

            $_SESSION['login-'.$rnd] = true;
            $_SESSION['username-'.$rnd] = $_POST['username'];
            echo "success-".$rnd;

        }else{
            echo "error-000";
        }
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="theme.css" type="text/css">
    <style>
        .logo{
            filter: brightness(0);
            width: 100%;
        }
    </style>
    <!--<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
      window.OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
        OneSignal.init({
          appId: "69e82c00-75c9-4b5d-8f85-b7eece5fb0b4",
          safari_web_id: "",
          notifyButton: {
            enable: true,
          },
          subdomainName: "213-248-131",
        });
      });
    </script>-->
</head>
<body>
  <div class="py-5 text-center" style="background-image: url('https://static.pingendo.com/cover-bubble-dark.svg');background-size:cover;">
    <div class="container">
      <div class="row">
        <div class="mx-auto col-md-6 col-10 bg-white p-5">
            <!--<img class="logo" src="https://www.innovaart.com.tr/turib/wp-content/uploads/2021/03/beyazlogo28.png">-->
          <h1 class="mb-4">Giris</h1>
          <form>
            <div class="form-group"> <input type="text" class="form-control" placeholder="Kullanici Adiniz" id="username"> </div>
            <div class="form-group mb-3"> <input type="password" class="form-control" placeholder="Parolaniz" id="password"> <small class="form-text text-muted text-right">
              </small> </div> <button type="button" id="loginbtn" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        //get value of username and password
        $("#loginbtn").click(function(){
            var username = $("#username").val();
            var password = $("#password").val();
            //get data from database
            $.post("index.php",{username:username,password:password},function(data){
              console.log(data);
                var rnd = data.split('-')[1];
                var status = data.split('-')[0];
                if(status == "success"){
                    localStorage.setItem("rnd", rnd);
                    window.location.href = "panel.php?rnd="+rnd;
                }else{
                    alert("Kullanici Adi veya Parola Yanlis");
                }
            });
        });
    });
</script>
</body>

</html>