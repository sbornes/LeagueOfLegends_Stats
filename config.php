<?php
	$api_key = "RGAPI-0b1fd16c-5868-436e-84b3-3b00312f31a2";

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "league_site";

	$league_version = "7.23.1";

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
			1000 => "PROJECT: Hunters"
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
	)

	// Create connection
	// $conn = new mysqli($servername, $username, $password,$dbname);
  //
	// // Check connection
	// if ($conn->connect_error) {
	//     die("Connection failed: " . $conn->connect_error);
	// }

?>
