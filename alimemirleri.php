<?php

function alimEmirleri($cookie){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://platform.turib.com.tr/Raporlar/GetPiyasaSatimEmriList');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $headers = array();
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"98\", \"Google Chrome\";v=\"98\"';
    $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
    $headers[] = 'Dnt: 1';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36';
    $headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://platform.turib.com.tr/Raporlar/PiyasaEkrani';
    $headers[] = 'Accept-Language: tr,en-US;q=0.9,en;q=0.8,de;q=0.7';
    //$headers[] = 'Cookie:  __RequestVerificationToken=7oLtPha3kb_U-24-ACIhKumvzdn1_49h3N7KltpUT8JjY16zZVVNAYTwCqr4Czxn-2ZqMSvKwz9CCpZExzzea_6yXQwxvoRaonDhTLv_8k01; ASP.NET_SessionId=pbgdjbxdwy55g0ntmyiod4bv; .ASPXAUTH=5F0FB54B05E1074F5C073B7D66C219A9A3F464E3D35BFBC7C54451640A0AF556592900651FD1A1A3099BE2522DE9866FFD5116DC0AB282F07C49C56AE2B478A3D27F632C07EC0F49CF6F31F999414C321AE7AF4C5186E416CF12854165FE140B0A5C252A5E95C376A16699D70E3BA2AA';
    $headers[] = 'Cookie:  '.$cookie;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    return $result;

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

}



//file_put_contents('alimEmirleri.json', alimEmirleri());