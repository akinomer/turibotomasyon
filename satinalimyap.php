<?php

function kisiBilgiAl($cookie){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://platform.turib.com.tr/EmirAlim/GetEmirAlimEntry?UrunAlimSatimNo=0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Connection' => 'keep-alive',
        'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"',
        'Accept' => 'text/html, */*; q=0.01',
        'DNT' => '1',
        'X-Requested-With' => 'XMLHttpRequest',
        'sec-ch-ua-mobile' => '?0',
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36',
        'sec-ch-ua-platform' => '"macOS"',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-Mode' => 'cors',
        'Sec-Fetch-Dest' => 'empty',
        'Referer' => 'https://platform.turib.com.tr/EmirAlim',
        'Accept-Language' => 'tr',
        'Accept-Encoding' => 'gzip',
    ]);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    //ssl verifypeer false
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    return $response;

    curl_close($ch);
}

function getUrunListesi($cookie, $elus){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://platform.turib.com.tr/EmirAlim/GetUrunListesi');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Connection' => 'keep-alive',
        'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"',
        'Accept' => 'application/json, text/javascript, */*; q=0.01',
        'DNT' => '1',
        'X-Requested-With' => 'XMLHttpRequest',
        'sec-ch-ua-mobile' => '?0',
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36',
        'sec-ch-ua-platform' => '"macOS"',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-Mode' => 'cors',
        'Sec-Fetch-Dest' => 'empty',
        'Referer' => 'https://platform.turib.com.tr/EmirAlim',
        'Accept-Language' => 'tr',
        'Accept-Encoding' => 'gzip',
    ]);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    
    $rv = '';
    $cisin = json_decode($response, true);
    for($i = 0; $i < count($cisin); $i++){
        if($cisin[$i]['ElusKodu'] == $elus){
            $rv = $cisin[$i]['UrunKodu'];
            break;
        }
    }


    return $rv;

    curl_close($ch);
}


function alimYap($cookie, $requestVerificationToken, $aliciSaticiKisiNo, $hesapNoIBAN, $araciKurumNo, $urunKodu, $fiyat, $miktar, $tarih){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://platform.turib.com.tr/EmirAlim/SaveEntry?Length=8');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Connection' => 'keep-alive',
        'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"',
        'DNT' => '1',
        'sec-ch-ua-mobile' => '?0',
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36',
        'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept' => '*/*',
        'X-Requested-With' => 'XMLHttpRequest',
        'sec-ch-ua-platform' => '"macOS"',
        'Origin' => 'https://platform.turib.com.tr',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-Mode' => 'cors',
        'Sec-Fetch-Dest' => 'empty',
        'Referer' => 'https://platform.turib.com.tr/EmirAlim',
        'Accept-Language' => 'tr',
        'Accept-Encoding' => 'gzip',
    ]);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '__RequestVerificationToken='.$requestVerificationToken.'&UrunAlimSatimNo=0&Tarih='.$tarih.'&AlimSatimTip=1&AliciSaticiKisiNo='.$aliciSaticiKisiNo.'&HesapNoIBAN='.$hesapNoIBAN.'&AraciKurumNo='.$araciKurumNo.'&UrunKodu='.$urunKodu.'&Fiyat='.$fiyat.'&Miktar='.$miktar.'&X-Requested-With=XMLHttpRequest');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    return $response;
    curl_close($ch);
}


include('vt.php');
include('alimemirleri.php');
include('simplehtmldom_1_9_1/simple_html_dom.php');


$verilerigetir = $db->customQuery('Select * From turib_auth Where id='.$argv[1].' Order By id DESC LIMIT 1')->getRow();
$kuki = json_decode($verilerigetir->cookies,1);

$cookie = "";
for($i=0; $i < count($kuki); $i++){
    $cookie .= $kuki[$i]['name']."=".$kuki[$i]['value'].";";
}

$inputFiyat     = 0;
$inputMiktar    = 0;
$inputKalan     = 0;
$miktar         = 0;

// infinite loop with sleep time of 1 seconds
while(1){
    $sorgula = $db->customQuery('Select * From kriterler Where id='.$argv[3])->getRow();
    
    if($sorgula->status==10){
        echo "Bekle\n";
    }else{
        $userLoggedIn = $db->customQuery('Select COUNT(*) AS total From turib_auth Where id='.$argv[1].'')->getRow();
        if($userLoggedIn->total==0){
            goto bitir;
        }
        
        $db->customQuery("UPDATE kriterler SET status='1' WHERE id=".$argv[3]);

        $elus = $sorgula->elus;
        $fiyat = $sorgula->fiyat;

        $donendeger = json_decode(alimEmirleri($cookie),1)['data'];
        
        $arrFiyat = array();
        $arrMiktar = array();
        $arrKalan = array();
        $arrUrunKodu = array();

        //echo print_r($donendeger,1)."\n";

        for($i=0; $i<count($donendeger); $i++){

            
            if(strval($donendeger[$i]['ISIN_ELUS_KODU'])==strval($elus) && str_replace(',','.', $donendeger[$i]['Fiyat']) <= str_replace(',','.', $fiyat) && intval($donendeger[$i]['Kalan'])>0){
                echo 'heyooo';

                array_push($arrFiyat, $donendeger[$i]['Fiyat']);
                array_push($arrMiktar, $donendeger[$i]['Miktar']);
                array_push($arrKalan, $donendeger[$i]['Kalan']);
                array_push($arrUrunKodu, $donendeger[$i]['UrunKodu']);


                ($donendeger[$i]['ISIN_ELUS_KODU'] == $elus) ? print 'true' : print 'false';
                echo "\n";
                (str_replace(',','.',$donendeger[$i]['Fiyat']) <= str_replace(',','.',$fiyat)) ? print 'true' : print 'false';
                echo "\n";
                ($donendeger[$i]['Kalan'] > 0) ? print 'true' : print 'false';
                echo "\n******************************\n";
                
            }
            //echo $donendeger[$i]['ISIN_ELUS_KODU'].":".$elus."\n";
        }
        echo count($arrFiyat)."\n";
        
        
        if(count($arrFiyat)>0){
            $min_index = array_search(min($arrFiyat), $arrFiyat);
            $inputFiyat = $arrFiyat[$min_index];
            $inputMiktar = $arrMiktar[$min_index];
            $inputKalan = $arrKalan[$min_index];
            $db->customQuery("UPDATE kriterler SET onay='1', kalanmiktar='".$inputKalan."' ,bulunanfiyat='".$inputFiyat."', bulunanmiktar='".$inputMiktar."' WHERE id=" . $argv[3]);
            goto atlabir;
            break;
        }        

    }

    

    sleep(1);
}

atlabir:
echo 'Fiyat:'.$inputFiyat;
echo "\n";
echo 'Miktar:'.$inputMiktar;
echo "\n";
echo 'Kalan:'.$inputKalan;
echo "\n";



while(1){
    $onay = $db->customQuery("SELECT * FROM kriterler WHERE id=".$argv[3])->getRow();
    if($onay->onay == 3){
        $db->customQuery("DELETE FROM kriterler WHERE id=".$argv[3]);
        goto bitir;
    }
    if($onay->onay == 2){
        $miktar = $onay->miktar;
        goto atlaiki;
        break;
    }
        
    sleep(1);
}

atlaiki:

$html = str_get_html(kisiBilgiAl($cookie));

foreach($html->find('input#AliciSaticiKisiNo') as $e){
    $aliciSaticiKisiNo = $e->value;
}

foreach($html->find('input[name=__RequestVerificationToken]') as $e){
    $requestVerificationToken = $e->value;
}

foreach($html->find('input[name=Tarih]') as $e){
    $tarih = $e->value;
}
echo $aliciSaticiKisiNo;
echo "\n";
echo $requestVerificationToken;
echo "\n";
echo $tarih = '1.01.0001+00%3A00%3A00';
echo "\n";
echo $miktar;
echo "\n";



$urunKodu = getUrunListesi($cookie, $elus);
echo $urunKodu;
echo "\n";

$araciKurumNo = explode("-", $onay->banka)[0];
$hesapNoIBAN = explode("-", $onay->banka)[1];

$alimdeger =  alimYap($cookie, $requestVerificationToken, $aliciSaticiKisiNo, $hesapNoIBAN, $araciKurumNo, $urunKodu, $fiyat, $miktar, $tarih);
//file_put_contents('alim.txt', $alimdeger);

echo $alimdeger;
bitir:








