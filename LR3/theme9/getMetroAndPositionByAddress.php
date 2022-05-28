<?php

$metro = " не найдено";
$token = file_get_contents('config.txt');
$address = $_GET['address'];
$parameters = array(
  'apikey' => $token,
  'geocode' => $address,
  'format' => 'json'
);
$buffer = file_get_contents("https://geocode-maps.yandex.ru/1.x/?" . http_build_query($parameters));
$responceGeocode = json_decode($buffer, true);

if($responceGeocode['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'] < 1){
    $responceGeocode = [
        "data" => "Not found"
    ];
    echo json_encode($responceGeocode, JSON_UNESCAPED_UNICODE);
    die();
} else {
    $parameters = array(
        'apikey' => $token,
        'geocode' => $responceGeocode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'],
        'kind' => 'metro',
        'format' => 'json'
    );
    $metroRequest = file_get_contents("https://geocode-maps.yandex.ru/1.x/?" . http_build_query($parameters));
    $metroResponse = json_decode($metroRequest, true);
    if($metroResponse['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'] >= 1){
        $metroName = $metroResponse['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
        $metro = $metroName;
    }
    $responseFinal = [
        "result" => [
            "address" => $responceGeocode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['formatted'],
            "position" => $responceGeocode['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'],
            "metro" => $metro
        ]
    ];
}
echo json_encode($responseFinal, JSON_UNESCAPED_UNICODE);
