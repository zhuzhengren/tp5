<?php

namespace app\wx\common;


class Weather {
    var $FILENAME = __DIR__."/City.json";

    var $URL = "http://t.weather.sojson.com/api/weather/city/";
    
    public function getCityCodeByName($name){
        if(file_exists($this->FILENAME)){
            $fp = fopen($this->FILENAME,"r");
            $cityList = json_decode(fread($fp, filesize($this->FILENAME)),true);
            //dump($cityList);
            foreach ($cityList as $city){
               if($city['city_name'] == $name){
                   return $city['city_code'];
               }
           }
        }
        return "101010100";
    }
   
    public function getWeather($name){
        $cu = curl_init();
        
        $url = $this->URL.$this->getCityCodeByName($name);
       // $url = $this->URL."101130901";
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($cu);
        if (curl_errno($cu)) {
            dump(curl_error($cu));
        }
        return $res;
        $weather = json_decode($res, true);
        
        return $weather;
    }
}
