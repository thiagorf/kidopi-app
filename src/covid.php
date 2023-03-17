<?php

$url = "https://dev.kidopilabs.com.br/exercicio/covid.php?pais="; 

$ch = curl_init($url + $_POST["country"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$res = json_decode($res);
curl_close($ch);
