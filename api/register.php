<?php

date_default_timezone_set('Asia/Kolkata');

// print_r($_SERVER);
// echo "<br>\n";
// echo "<br>\n";
// print_r($_REQUEST);
// echo "<br>\n";
// echo "<br>\n";
// print_r($_COOKIE);
// echo "<br>\n";
// echo "<br>\n";
// print_r($_ENV);
// echo "<br>\n";
// echo "<br>\n";

$slot = $_REQUEST['slot'] - 1;
$team = $_REQUEST['team_name'];
$count = $_REQUEST['count'];
$players = $_REQUEST['players'];

$myfile = fopen("../register/registration_data.json", "r") or die("Unable to open file!");
$array = json_decode(fread($myfile, filesize("../register/registration_data.json")));
if ($array->data->available_slots[$slot]->available) {
    $array->data->available_slots[$slot]->available = false;
    $array->data->available_slots[$slot]->team = $team;
    $array->data->available_slots[$slot]->player_count = $count;
    $array->data->available_slots[$slot]->players = $players;
    $array->data->available_slots[$slot]->time = time();
} else {
    echo "already booked";
}
fclose($myfile);

$myfile = fopen("../register/registration_data.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($array));
fclose($myfile);
