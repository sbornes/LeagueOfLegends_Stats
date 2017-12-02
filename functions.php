<?php
	include "ChromePhp.php";

	function getSummonerInfo($summoner_name) {
		include "config.php";

		$sql = "SELECT * FROM summoners WHERE name='{$summoner_name}'";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$result = $result->fetch_object();

			$id 						= $result->id;
			$accountId 			= $result->accountId;
			$profileIconId 	= $result->profileIconId;
			$name 					= $result->name;
			$summonerLevel 	= $result->summonerLevel;
			$revisionDate 	= $result->revisionDate;

			return json_encode(array('id' => $id, 'accountId' => $accountId, 'profileiconId' => $profileIconId, 'name' => $name, 'summonerLevel' => $summonerLevel, 'revisionDate' => $revisionDate
		), JSON_FORCE_OBJECT);
		}

		echo ' we failed';
		return null;
	}

	function validatePlayer($summoner_name) {
		include "config.php";
	  $sql = "SELECT accountId FROM summoners where name='{$summoner_name}';";

		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$json = getSummonerByAccount($result->fetch_object()->accountId);
		}
		else {
			$json = getSummonerByName($summoner_name);
		}

		$json_status = (isset($json->status->status_code) ? $json->status->status_code : 0);

		if(isset($json_status) && $json_status == 404) {
			return false;
		}


		$id 			= $json->id;
		$accountId 		= $json->accountId;
		$profileIconId 	= $json->profileIconId;
		$name 			= $json->name;
		$summonerLevel 	= $json->summonerLevel;
		$revisionDate 	= $json->revisionDate;

		$sql = "INSERT INTO summoners (id, accountId, profileIconId, name, summonerLevel, revisionDate)
		VALUES ({$id}, {$accountId}, {$profileIconId}, '{$name}', {$summonerLevel}, '{$revisionDate}')
		ON DUPLICATE KEY UPDATE id={$id}, profileIconId={$profileIconId}, name='{$name}', summonerLevel={$summonerLevel}, revisionDate='{$revisionDate}'";

		if ($conn->query($sql) === TRUE)
			return true;

		return false;
	}

	function getSummonerByName($summoner_name) {
		include "config.php";
		$url = "https://oc1.api.riotgames.com/lol/summoner/v3/summoners/by-name/" . rawurlencode($summoner_name);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response);
	}

	function getSummonerByAccount($summoner_id) {
		include "config.php";
		$url = "https://oc1.api.riotgames.com/lol/summoner/v3/summoners/by-account/" . $summoner_id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response);
	}

	function getLatestVersion() {
		include "config.php";
		$url = "https://oc1.api.riotgames.com/lol/static-data/v3/versions";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response, true);
		return isset($response[0]) ? $response[0] : $league_version;
	}

	function getProfileIconUrl($icon_num) {
		return "http://ddragon.leagueoflegends.com/cdn/".getLatestVersion()."/img/profileicon/{$icon_num}.png";
	}
?>
