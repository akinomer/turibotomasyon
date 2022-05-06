<?php

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start ". $cmd, "r")); ///MIN
    }
    else {
        exec($cmd . " > /dev/null &");
    }
}


include('vt.php');


while(1){

    $sorgu = $db->customQuery("SELECT Count(*) AS total, turibusername, user_id FROM turib_auth Where status=0 GROUP BY id")->getRow();
    if($sorgu->total >= 1){

        
   
        $sql = "SELECT COUNT(*) AS varmi, id, cookies FROM turib_auth WHERE status=0 AND turibusername='".$sorgu->turibusername."' GROUP BY id";
        $varmibak = $db->customQuery($sql)->getRow();

        if($varmibak->cookies == '' || $varmibak->cookies == null){

            $cmd = "python3 turibemirsorgu.py ".$varmibak->id." 0";
            echo $cmd;
            execInBackground($cmd);
        }else{

            $kriter = $db->customQuery("SELECT COUNT(*) AS total, id FROM kriterler WHERE status=0 AND user_id=".$sorgu->user_id." GROUP BY id")->getRow();

            //$cmd = "python3 turibemirsorgu.py ".$varmibak->id." 1 ".$kriter->id;
            $cmd = "php satinalimyap.php ".$varmibak->id." 1 ".$kriter->id;
            echo $cmd;
            //$db->customQuery("UPDATE turib_auth SET status=0 WHERE id=".$sorgu->id);
            execInBackground($cmd);
            //sleep(10);
            //exit;
            //$db->customQuery("UPDATE kriterler SET status=1 WHERE id=".$kriter->id);
        }

        $db->customQuery("UPDATE turib_auth SET status=1 WHERE id=".$varmibak->id);
        echo "\nEmir var, Alindi.\n";
        
     }else{
        echo "Emir Yok\n";
     }
     
    
    sleep(5);
}

