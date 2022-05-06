<?php

if($_GET['tip']=='alarm'){
    $yakala = $db->customQuery('SELECT * FROM kriterler WHERE user_id='.$_GET['user_id'].' AND rnd='.$_GET['rnd'].' AND status=1 AND onay=1 LIMIT 1')->getRow();

    //echo 'SELECT * FROM kriterler WHERE user_id='.$user->id.' AND rnd='.$_GET['rnd'].' AND status=1 AND onay=1 LIMIT 1';
    //echo '<br>';
    echo json_encode($yakala);
    exit;
}

if($_GET['tip']=='onayla'){
    $db->table('kriterler')->where('id','=',$_GET['id'])->update(
    	[
    		'onay' => 2, 
    		'banka'=> $_GET['banka'],
    		'miktar' => $_GET['miktar_onay']
    		 ]);
    exit;
}

if($_GET['tip']=='reddet'){

    $db->table('kriterler')->where('id','=',$_GET['id'])->delete();
    exit;
}