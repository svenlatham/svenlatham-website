#!/usr/bin/php
<?php

$countries = array(
    "FRA" => "FR",
    "GBR" => "UK",
    "NLD" => "NL",
    "ITA" => "IT",
    "DEU" => "DE",
    "ESP" => "ES",
    "DNK" => "DK",
    "AUS" => "AU",
    "AUT" => "AT",
    "SWE" => "SE",
    "NZL" => "NZ",
    "USA" => "US"
);

foreach ($countries as $country => $map) {
    $pointer = new DateTime("2020-03-01");
    $output = array(); // Key will be date; values will be an array of confirmed, deaths, stringency
    while ($pointer->format('Y-m-d') < date('Y-m-d')) {
        printf("Processing %s for %s\n", $pointer->format('Y-m-d'), $country);
        $k = $pointer->format('Y-m-d');
        $url = sprintf("https://covidtrackerapi.bsg.ox.ac.uk/api/v2/stringency/actions/%s/%s", $country, $k);
        $json = file_get_contents($url);
        $data = json_decode($json, true); // Slightly faster
        $row = array();
        $row["confirmed"] = $data['stringencyData']['confirmed'];
        $row["deaths"] = $data['stringencyData']['deaths'];
        $row["stringency"] = $data['stringencyData']['stringency'];
        $output[$k] = $row;
        $pointer->modify("+1 day");
        //sleep(1);
    }
    $fp = fopen("../data/stringency/{$map}.json", "w");
    fputs($fp, json_encode($output));
    fclose($fp);
}
