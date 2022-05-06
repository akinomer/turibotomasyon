<?php

include('vt.php');

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
curl_setopt($ch, CURLOPT_COOKIE, '__RequestVerificationToken=qzXhljx12x0tWCAfdiNbuDo7oXT_G1Sug3RKcoYq7KT5liHgiR_ReamoAOHiRrb8adLdipgykavNSe1SfxVG86SKww_YvCFX4Tin2UOa4Yw1; ASP.NET_SessionId=kmsdtjcw2buar2xmqi2z4yup; .ASPXAUTH=456254A4C851AED39D6CE60DE652D89320212E120960EA55BDA44204C14D1C05EC8461AB6E841F49B067FBE4266A936569DA2E3CE64ADD31472C28C90E0D33F2329352DD29AEC9E174DA43AF7CAA86931C14B053C9C5EC1A654035297E49C50BF54C84178A5CC9EB2A20EBF70031F1DC');
curl_setopt($ch, CURLOPT_POSTFIELDS, '__RequestVerificationToken=9xSf1B0PCTC4CnIAu_j0-ZdE89EZnYkjB02iLsEUUKmj9P_S87wm2Tbif-aADkmN7gcDeSaye2O7TDTcvymVEJ49PSQ84_oLmHidxzgFgw3WP6sG0TV_ngKTfjAkcWAZ0&UrunAlimSatimNo=0&Tarih=1.01.0001+00%3A00%3A00&AlimSatimTip=1&AliciSaticiKisiNo=114669&HesapNoIBAN=13374742&AraciKurumNo=DNZ&UrunKodu=9975&Fiyat=41&Miktar=200&X-Requested-With=XMLHttpRequest');

$response = curl_exec($ch);

curl_close($ch);