<?php
	$api_key = "RGAPI-50b15b9e-822a-4b26-b43c-56ea9b3786c1";

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "league_site";

	$league_version = "8.24.1";

	$mutlikill_const = array(
		2 => "Double Kill",
		3 => "Triple Kill",
		4 => "Quadra Kill",
		5 => "Penta Kill",
		6 => "Hexa Kill"
	);

	$gamemodes_const = array(
	    0 => "Custom",
	    70 => "One for All",
			72 => "1v1 Snowdown Showdown",
			73 => "2v2 Snowdown Showdown",
			75 => "6v6 Hexakill",
			76 => "Ultra Rapid Fire",
			78 => "Mirrored One for All",
			83 => "Co-op vs AI Ultra Rapid Fire",
			96 => "Ascension",
			98 => "6v6 Hexakill",
			100 => "5v5 ARAM",
			300 => "King Poro",
			310 => "Nemesis",
			313 => "Black Market Brawlers",
			317 => "Definitely Not Dominion",
			318 => "All Random URF",
			325 => "All Random",
			400 => "Normals", 				// "5v5 Draft Pick",
			420 => "Ranked Solo",			// "5v5 Ranked Solo",
			430 => "Normals", 				// "5v5 Blind Pick",
			440 => "Ranked Flex", 		// "5v5 Ranked Flex",
			450 => "ARAM",						// "5v5 ARAM",
			460 => "3v3 Normals",			// "3v3 Blind Pick",
			470 => "3v3 Ranked Flex",
			600 => "Blood Hunt Assassin",
			610 => "Dark Star",
			800 => "Co-op vs. AI Intermediate Bot",
			810 => "Co-op vs. AI Intro Bot",
			820 => "Co-op vs. AI Beginner Bot",
			830 => "Co-op vs. AI Intro Bot",
			840 => "Co-op vs. AI Beginner Bot",
			850 => "Co-op vs. AI Intermediate Bot",
			940 => "Nexus Siege",
			950 => "Doom Bots games /w difficulty voting",
			960 => "Doom Bots",
			980 => "Star Guardian Invasion: Normal",
			990 => "Star Guardian Invasion: Onslaught",
			1000 => "PROJECT: Hunters",
			1010 => "Snow ARURF games"
	);

	$summoner_spells_const = array(
		34 => "SummonerSiegeChampSelect2",
		12 => "SummonerTeleport",
		33 => "SummonerSiegeChampSelect1",
		3 => "SummonerExhaust",
		21 => "SummonerBarrier",
		13 => "SummonerMana",
		11 => "SummonerSmite",
		4 => "SummonerFlash",
		32 => "SummonerSnowball",
		14 => "SummonerDot",
		36 => "SummonerDarkStarChampSelect2",
		35 => "SummonerDarkStarChampSelect1",
		30 => "SummonerPoroRecall",
		6 => "SummonerHaste",
		7 => "SummonerHeal",
		31 => "SummonerPoroThrow",
		1 => "SummonerBoost"
	);

	$valid_regions = array(
		"NA" => array (
			"platform" => ["NA1", "NA2"],
			"host" => "na1.api.riotgames.com",
			"language" => "en_US",
			"name" => "North America",
			"code" => "NA"
		),
		"EUNE" => array (
			"platform" => ["EUN1"],
			"host" => "eun1.api.riotgames.com",
			"language" => "en_US",
			"name" => "Europe Nordic & East",
			"code" => "EUNE"
		),
		"EUW" => array (
			"platform" => ["EUW1"],
			"host" => "euw1.api.riotgames.com",
			"language" => "en_US",
			"name" => "Europe West",
			"code" => "EUW"
		),
		"OCE" => array (
			"platform" => ["OC1"],
			"host" => "oc1.api.riotgames.com",
			"language" => "en_AU",
			"name" => "Oceania",
			"code" => "OCE"
		),
		"LAN" => array (
			"platform" => ["LA1"],
			"host" => "la1.api.riotgames.com",
			"language" => "en_AU",
			"name" => "Latin America North",
			"code" => "LAN"
		),
		"LAS" => array (
			"platform" => ["LA2"],
			"host" => "la2.api.riotgames.com",
			"language" => "en_AU",
			"name" => "Latin America South",
			"code" => "LAS"
		),
		"KR" => array (
			"platform" => ["KR"],
			"host" => "kr.api.riotgames.com",
			"language" => "ko_KR",
			"name" => "Korea",
			"code" => "KR"
		),
		"JP" => array (
			"platform" => ["JP1"],
			"host" => "jp1.api.riotgames.com",
			"language" => "ja_JP",
			"name" => "Japan",
			"code" => "JP"
		),
		"BR" => array (
			"platform" => ["BR1"],
			"host" => "br1.api.riotgames.com",
			"language" => "pt_BR",
			"name" => "Brazil"
		),
		"TR" => array (
			"platform" => ["TR1"],
			"host" => "tr1.api.riotgames.com",
			"language" => "tr_TR",
			"name" => "Turkey",
			"code" => "TR"
		),
		"RU" => array (
			"platform" => ["RU"],
			"host" => "ru.api.riotgames.com",
			"language" => "tr_TR",
			"name" => "Russia",
			"code" => "ru_RU"
		),
	);
	

	// Create connection
	// $conn = new mysqli($servername, $username, $password,$dbname);
  //
	// // Check connection
	// if ($conn->connect_error) {
	//     die("Connection failed: " . $conn->connect_error);
	// }

?>
