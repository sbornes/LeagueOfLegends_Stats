<?php
    include_once "config.php";
    include_once "functions.php";

    $player = $_POST["player"];
    $region_data = $_POST["region_data"];

    $data = getSummonerInfo($player, $region_data["host"]);

    echo json_encode($data)
?>
