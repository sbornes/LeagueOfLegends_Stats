<?php
    include "ChromePhp.php";

    function getSummonerInfo($summoner_name)
    {
        $stats['summoner'] = sqlGetSummoner($summoner_name);
        $stats['rank']['solo'] = sqlGetRankLeague($stats['summoner']['id'], 'ranked_solo_5x5');
        $stats['rank']['flex'] = sqlGetRankLeague($stats['summoner']['id'], 'ranked_flex_sr');

        return json_encode($stats);
    }

    function sqlGetSummoner($summoner_name)
    {
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

            return array('id' => $id, 'accountId' => $accountId,
        'profileiconId' => $profileIconId, 'name' => $name,
        'summonerLevel' => $summonerLevel, 'revisionDate' => $revisionDate,
        JSON_FORCE_OBJECT);
        }
    }

    function sqlGetRankLeague($summoner_id, $league)
    {
        include "config.php";

        $sql = "SELECT * FROM {$league} WHERE id='{$summoner_id}'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $result = $result->fetch_object();

            $id 						 = $result->id;
            $name 					 = $result->name;
            $leagueName 	   = $result->leagueName;
            $wins 	         = $result->wins;
            $losses 	       = $result->losses;
            $tier 	         = $result->tier;
            $rank 	         = $result->rank;
            $leaguePoints 	 = $result->leaguePoints;
            $veteran 	       = $result->veteran;
            $inactive 	     = $result->inactive;
            $freshBlood 	   = $result->freshBlood;
            $leagueId        = $result->leagueId;

            return array('id' => $id, 'name' => $name,
        'leagueName' => $leagueName, 'wins' => $wins,
        'losses' => $losses, 'tier' => $tier,
        'rank' => $rank, 'leaguePoints' => $leaguePoints,
        'veteran' => $veteran, 'inactive' => $inactive,
        'freshBlood' => $freshBlood, 'leagueId' => $leagueId,
        JSON_FORCE_OBJECT);
        }
    }

    function retrieveDataSummoner($summoner_name)
    {
        updateSummoner($summoner_name);
        updateSummonerRank($summoner_name);
    }

    function updateSummoner($summoner_name)
    {
        include "config.php";
        $sql = "SELECT accountId FROM summoners where name='{$summoner_name}';";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $json = getSummonerByAccount($result->fetch_object()->accountId);
        } else {
            $json = getSummonerByName($summoner_name);
        }

        $json_status = (isset($json->status->status_code) ? $json->status->status_code : 0);

        if (isset($json_status) && $json_status == 404) {
            return false;
        }


        $id 						= $json->id;
        $accountId 			= $json->accountId;
        $profileIconId 	= $json->profileIconId;
        $name 					= $json->name;
        $summonerLevel 	= $json->summonerLevel;
        $revisionDate 	= $json->revisionDate;

        $sql = "INSERT INTO summoners (id, accountId, profileIconId, name, summonerLevel, revisionDate)
		VALUES ({$id}, {$accountId}, {$profileIconId}, '{$name}', {$summonerLevel}, '{$revisionDate}')
		ON DUPLICATE KEY UPDATE id={$id}, profileIconId={$profileIconId}, name='{$name}', summonerLevel={$summonerLevel}, revisionDate='{$revisionDate}'";

        if ($conn->query($sql) === true) {
            return true;
        }

        return false;
    }

    function updateSummonerRank($summoner_name)
    {
        include "config.php";
        $sql = "SELECT id FROM summoners where name='{$summoner_name}';";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $json = getLeagueByAccount($result->fetch_object()->id);
        }

        $json_status = (isset($json->status->status_code) ? $json->status->status_code : 0);

        if (isset($json_status) && $json_status == 404) {
            return false;
        }

        foreach ($json as $value) { //foreach element in $arr

            $table 					= strtolower($value->queueType);
            $id 						= $value->playerOrTeamId;
            $name						= $value->playerOrTeamName;
            $leagueName 		= mysql_real_escape_string($value->leagueName);
            $wins 					= $value->wins;
            $losses 				= $value->losses;
            $tier 					= $value->tier;
            $rank 					= $value->rank;
            $leaguePoints 	= $value->leaguePoints;
            $veteran 				= $value->veteran ? 1 : 0;
            $inactive 			= $value->inactive ? 1 : 0;
            $freshBlood 		= $value->freshBlood ? 1 : 0;
            $leagueId 			= $value->leagueId;

            $sql = "INSERT INTO {$table} (id, name, leagueName, wins, losses, tier, rank, leaguePoints, veteran, inactive, freshBlood, leagueId)
			VALUES ({$id}, '{$name}', '{$leagueName}', {$wins}, {$losses}, '{$tier}', '{$rank}', {$leaguePoints}, {$veteran}, {$inactive}, {$freshBlood}, '{$leagueId}')
			ON DUPLICATE KEY UPDATE id={$id}, name='{$name}', leagueName='{$leagueName}', wins={$wins}, losses={$losses}, tier='{$tier}', rank='{$rank}', leaguePoints={$leaguePoints}, veteran={$veteran}, inactive={$inactive}, freshBlood={$freshBlood}, leagueId='{$leagueId}'";

            if ($conn->query($sql) == false) {
                echo "<br><br>Error: " . $sql . "<br><br>" . $conn->error;
            }
        }

        return true;
    }

    function getSummonerByName($summoner_name)
    {
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

    function getSummonerByAccount($summoner_id)
    {
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

    function getLeagueByAccount($summoner_id)
    {
        include "config.php";
        $url = "https://oc1.api.riotgames.com/lol/league/v3/positions/by-summoner/" . $summoner_id;
        // ChromePhp::log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        // ChromePhp::log($response);
        return json_decode($response);
    }

    function getMatchRecentByAccount($summoner_id)
    {
      include "config.php";
      $url = "https://oc1.api.riotgames.com/lol/match/v3/matchlists/by-account/" . $summoner_id . "/recent";
      // ChromePhp::log($url);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);
      // ChromePhp::log($response);
      return json_decode($response);
    }

    function getMatchRecentData($json)
    {
      $data = [];

      foreach($json->matches as $value)
      {
        include "config.php";
        $url = "https://oc1.api.riotgames.com/lol/match/v3/matches/" . $value->gameId;
        //ChromePhp::log($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        // ChromePhp::log($response);
        $data[] = json_decode($response);
      }

      return $data;
    }

    function getLatestVersion()
    {
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

    function getProfileIconUrl($icon_num)
    {
        return "http://ddragon.leagueoflegends.com/cdn/".getLatestVersion()."/img/profileicon/{$icon_num}.png";
    }
