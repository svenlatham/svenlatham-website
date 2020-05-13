<?php

# Parse the Global file into a series of smaller CSVs, and make up an index as we go
# Global file comes in via STDIN; we'll output to the output/ subdirectory.


$scans = array('GB','US','FR','IE','AU','DE','BE','NL','DK','NZ','SE','IT','PT','ES'); // Countries we want to know about
$index = array();

function getHash($input) {
    // Get some kind of hash that's unique, deterministic and okay for the file system
    $hash = hash('sha256', $input);
    return $hash;
}

function getFile($line) {
    global $index;
    $k = $line[0];
    if (!array_key_exists($k, $index)) {
        $index[$k] = array('file' => $k, 'country' => $line[1], 'sub_region_1' => array());
    }
    if ($line[2]) {
        // Sub-region 1 exists
        $k2 = getHash($line[2]);
        if (!array_key_exists($k2, $index[$k]['sub_region_1'])) {
            $filename = sprintf("%s-%s", $k, $k2);
            $index[$k]['sub_region_1'][$k2] = array('name' => $line[2], 'file' => $filename);
        }
        return $index[$k]['sub_region_1'][$k2]['file'];
    }
    return $k; // Roots are just the two-character country code
}



$fpcsv = fopen("Global_Mobility_Report.csv", "r");
while ($line = fgetcsv($fpcsv)) {
    // We're interested in country_region_code, country_region, sub_region_1 and sub_region_2
    // For our purposes, we don't care about sub_region_2 so will only consume data when it's blank
    if ($line[0] == 'country_region_code') { continue; }
    if (!in_array($line[0], $scans)) { continue; }
    if ($line[3]) { continue; } // We don't support sub_region_2 just yet...
    $file = getFile($line);
    $fp = fopen("output/{$file}.csv", 'a');
    fputcsv($fp, array_slice($line,4));
    fclose($fp);
}
fclose($fpcsv);
$fp = fopen("output/index.json", 'w');
fputs($fp, json_encode($index));
fclose($fp);