<?php
    include "ChromePhp.php";

    $cache_profile_icon = 'profile_icon/';
    $cache_summoner = 'summoner/';
    $cache_champion = 'champion/';
    $cache_champion_icon = 'champion_icon/';
    $cache_champion_mastery = 'champion_mastery/';
    $cache_league = 'league/';
    $cache_match_history = 'match_history/';
    $cache_match_history_data = 'match_history_data/';
    $cache_summoner_spell = 'summoner_spell/';
    $cache_summoner_spell_icon = 'summoner_spell_icon/';
    $cache_item = 'item/';
    $cache_item_icon = 'item_icon/';

    $cache_path = 'cache/';
    $version = getLatestVersion();
    $cache_path = $cache_path.$version.'/';

    if (!file_exists($cache_path)) {
      mkdir($cache_path, 0777, true);
    }

    if (!file_exists($cache_path.$cache_profile_icon)) {
      mkdir($cache_path.$cache_profile_icon, 0777, true);
    }

    if (!file_exists($cache_path.$cache_summoner)) {
      mkdir($cache_path.$cache_summoner, 0777, true);
    }

    if (!file_exists($cache_path.$cache_champion)) {
      mkdir($cache_path.$cache_champion, 0777, true);
    }

    if (!file_exists($cache_path.$cache_champion_icon)) {
      mkdir($cache_path.$cache_champion_icon, 0777, true);
    }

    if (!file_exists($cache_path.$cache_champion_mastery)) {
      mkdir($cache_path.$cache_champion_mastery, 0777, true);
    }

    if (!file_exists($cache_path.$cache_league)) {
      mkdir($cache_path.$cache_league, 0777, true);
    }

    if (!file_exists($cache_path.$cache_match_history)) {
      mkdir($cache_path.$cache_match_history, 0777, true);
    }

    if (!file_exists($cache_path.$cache_match_history_data)) {
      mkdir($cache_path.$cache_match_history_data, 0777, true);
    }

    if (!file_exists($cache_path.$cache_summoner_spell)) {
      mkdir($cache_path.$cache_summoner_spell, 0777, true);
    }

    if (!file_exists($cache_path.$cache_summoner_spell_icon)) {
      mkdir($cache_path.$cache_summoner_spell_icon, 0777, true);
    }

    if (!file_exists($cache_path.$cache_item)) {
      mkdir($cache_path.$cache_item, 0777, true);
    }

    if (!file_exists($cache_path.$cache_item_icon)) {
      mkdir($cache_path.$cache_item_icon, 0777, true);
    }



    function getSummonerInfo($summoner_name)
    {
        $stats['summoner'] = getSummonerByName($summoner_name);
        $rank = getLeagueByAccount($stats['summoner']->id);

        if(isset($rank[0]))
          if($rank[0]->queueType == "RANKED_SOLO_5x5")
            $stats['rank']['solo'] = $rank[0];
          else
            $stats['rank']['flex'] = $rank[0];

        if(isset($rank[1]))
          if($rank[1]->queueType == "RANKED_FLEX_SR")
            $stats['rank']['flex'] = $rank[1];
          else
            $stats['rank']['solo'] = $rank[1];

        getChampionMastery($stats['summoner']->id);

        return json_encode($stats, JSON_FORCE_OBJECT);
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
        //updateSummoner($summoner_name);
        //updateSummonerRank($summoner_name);

        // $player = getSummonerByName($summoner_name);
        //
        // getLeagueByAccount($player->id);
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

        global $cache_path;
        global $cache_summoner;

        $url = "https://oc1.api.riotgames.com/lol/summoner/v3/summoners/by-name/" . rawurlencode($summoner_name);

        $filename = $cache_path.$cache_summoner.md5($url);
        if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
        {
            return json_decode(file_get_contents($filename));
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        if(!isset($response->status_code))
          file_put_contents($filename, $response);
        return json_decode($response);
    }

    function getSummonerByAccount($summoner_id)
    {
        include "config.php";

        global $cache_path;
        global $cache_summoner;

        $url = "https://oc1.api.riotgames.com/lol/summoner/v3/summoners/by-account/" . $summoner_id;

        $filename = $cache_path.$cache_summoner.md5($url);
        if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
        {
            return json_decode(file_get_contents($filename));
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        file_put_contents($filename, $response);
        return json_decode($response);
    }

    function getLeagueByAccount($summoner_id)
    {
        include "config.php";

        global $cache_path;
        global $cache_league;

        $url = "https://oc1.api.riotgames.com/lol/league/v3/positions/by-summoner/" . $summoner_id;

        $filename = $cache_path.$cache_league.md5($url);
        if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
        {
            return json_decode(file_get_contents($filename));
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if(!isset($response->status_code))
          file_put_contents($filename, $response);

        return json_decode($response);
    }

    function getMatchRecentByAccount($summoner_id)
    {
      include "config.php";

      global $cache_path;
      global $cache_match_history;

      $url = "https://oc1.api.riotgames.com/lol/match/v3/matchlists/by-account/" . $summoner_id . "/recent";

      $filename = $cache_path.$cache_match_history.md5($url);
      if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
      {
          return json_decode(file_get_contents($filename));
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      if(!isset($response->status_code))
        file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getMatchRecentData($json)
    {
      $data = [];

      foreach($json->matches as $value)
      {
        include "config.php";

        global $cache_path;
        global $cache_match_history_data;

        $url = "https://oc1.api.riotgames.com/lol/match/v3/matches/" . $value->gameId;

        $filename = $cache_path.$cache_match_history_data.md5($url);
        if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
        {
            $data[] = json_decode(file_get_contents($filename));
        }
        else {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLINFO_HEADER_OUT, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $response = curl_exec($ch);
          curl_close($ch);
          file_put_contents($filename, $response);
          $data[] = json_decode($response);
        }
      }

      return $data;
    }

    function getChampionAll()
    {
      include "config.php";

      global $cache_path;
      global $cache_champion;

      $url = "https://oc1.api.riotgames.com/lol/static-data/v3/champions?locale=en_US&tags=all&dataById=false";

      $filename = $cache_path.$cache_champion.md5($url);
      if( file_exists($filename) )
      {
        $response = json_decode(file_get_contents($filename));
        if(!isset($response->status->message)) {
          return $response;
        }
        echo $response->status->message .' for champion: '.$champion_id;
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getChampionById($champion_id)
    {
      include "config.php";

      global $cache_path;
      global $cache_champion;

      $url = "https://oc1.api.riotgames.com/lol/static-data/v3/champions/" . $champion_id . "?locale=en_US&tags=image";

      $filename = $cache_path.$cache_champion.md5($url);
      if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
      {
        $response = json_decode(file_get_contents($filename));
        if(!isset($response->status->message)) {
          return $response;
        }
        echo $response->status->message .' for champion: '.$champion_id;
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getLatestVersion()
    {
        include "config.php";

        global $cache_path;

        $url = "https://oc1.api.riotgames.com/lol/static-data/v3/versions";

        $filename = $cache_path.md5($url);

        ChromePhp::log($filename);

        if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
        {
            $response = json_decode(file_get_contents($filename), true);
            return isset($response[0]) ? $response[0] : $league_version;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if(!isset($response->status_code))
          file_put_contents($filename, $response);

        $response = json_decode($response, true);
        return isset($response[0]) ? $response[0] : $league_version;
    }

    function getSummonerSpell($spell_id)
    {
      include "config.php";

      global $cache_path;
      global $cache_summoner_spell;

      $url = "https://oc1.api.riotgames.com/lol/static-data/v3/summoner-spells/" . $spell_id . "?locale=en_US&tags=image";
      https://oc1.api.riotgames.com/lol/static-data/v3/summoner-spells/11?locale=en_US&tags=image
      $filename = $cache_path.$cache_summoner_spell.md5($url);
      if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
      {
          return json_decode(file_get_contents($filename));
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getSummonerSpellAll()
    {
      include "config.php";

      global $cache_path;
      global $cache_summoner_spell;

      $url = "https://oc1.api.riotgames.com/lol/static-data/v3/summoner-spells?locale=en_US&dataById=false&tags=all";

      $filename = $cache_path.$cache_summoner_spell.md5($url);
      if( file_exists($filename) )
      {
          return json_decode(file_get_contents($filename));
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getChampionMastery($summoner_id)
    {
      include "config.php";

      global $cache_path;
      global $cache_champion_mastery;

      $url = "https://oc1.api.riotgames.com/lol/champion-mastery/v3/champion-masteries/by-summoner/" . $summoner_id;

      $filename = $cache_path.$cache_champion_mastery.md5($url);
      if( file_exists($filename) && ( time() - 84600 < filemtime($filename) ) )
      {
          return json_decode(file_get_contents($filename));
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }

    function getItemsAll()
    {
      include "config.php";

      global $cache_path;
      global $cache_item;

      $url = "https://oc1.api.riotgames.com/lol/static-data/v3/items?locale=en_US&tags=all";

      $filename = $cache_path.$cache_item.md5($url);
      if( file_exists($filename) )
      {
          return json_decode(file_get_contents($filename));
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Riot-Token: ' . $api_key, 'Accept: application/json'));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      file_put_contents($filename, $response);

      return json_decode($response);
    }


    function findChampionMastery($summoner_id, $champion_id)
    {
      global $cache_path;
      global $cache_champion_mastery;

      $url = "https://oc1.api.riotgames.com/lol/champion-mastery/v3/champion-masteries/by-summoner/" . $summoner_id;

      $filename = $cache_path.$cache_champion_mastery.md5($url);
      $data = json_decode(file_get_contents($filename));

      foreach($data as $d)
      {
        if($d->championId == $champion_id)
        {
            return json_encode($d, JSON_FORCE_OBJECT);
        }
      }

    }

    function getProfileIconUrl($icon_num)
    {
      global $version;
      global $cache_path;
      global $cache_profile_icon;

      $url = "http://ddragon.leagueoflegends.com/cdn/".$version."/img/profileicon/{$icon_num}.png";

      $ext = pathinfo($url, PATHINFO_EXTENSION);

      $filename = $cache_path.$cache_profile_icon.md5($url).'.'.$ext;
      if( file_exists($filename) )
      {
          return $filename;
      }

      file_put_contents($filename, file_get_contents($url));

      return $url;
    }

    function getChampionIconUrl($champion_name)
    {
        global $version;
        global $cache_path;
        global $cache_champion_icon;

        $url = "http://ddragon.leagueoflegends.com/cdn/".$version."/img/champion/".str_replace(' ','',$champion_name);

        $ext = pathinfo($url, PATHINFO_EXTENSION);

        $filename = $cache_path.$cache_champion_icon.md5($url).'.'.$ext;
        if( file_exists($filename) )
        {
            return $filename;
        }

        file_put_contents($filename, file_get_contents($url));

        return $url;
    }

    function getSummonerSpellIcon($summoner_spell)
    {
      global $version;
      global $cache_path;
      global $cache_summoner_spell_icon;

      $url = "http://ddragon.leagueoflegends.com/cdn/".$version."/img/spell/".str_replace(' ','',$summoner_spell);

      $ext = pathinfo($url, PATHINFO_EXTENSION);

      $filename = $cache_path.$cache_summoner_spell_icon.md5($url).'.'.$ext;
      if( file_exists($filename) )
      {
          return $filename;
      }

      file_put_contents($filename, file_get_contents($url));

      return $url;
    }

    function getItemIcon($item)
    {
      global $version;
      global $cache_path;
      global $cache_item_icon;

      $url = "http://ddragon.leagueoflegends.com/cdn/".$version."/img/item/".$item;

      $ext = pathinfo($url, PATHINFO_EXTENSION);

      $filename = $cache_path.$cache_item_icon.md5($url).'.'.$ext;
      if( file_exists($filename) )
      {
          return $filename;
      }

      file_put_contents($filename, file_get_contents($url));

      return $url;
    }

    function secondsToMinutes($t_seconds)
    {
        $minutes = floor($t_seconds / 60);
        $seconds = $t_seconds % 60;

        if ($minutes > 0) {
            echo "{$minutes}m ";
        }

        if ($seconds > 0) {
            echo "{$seconds}s ";
        }
    }

    function lastPlayed($epoch)
    {
      $dt_lastplayed = new DateTime(date("Y-m-d H:i:s", substr($epoch, 0, 10)));
      $dt_now = new DateTime(date("Y-m-d H:i:s", time()));

      $last_played = $dt_lastplayed->diff($dt_now);

      if($last_played -> y > 0)
        return $last_played->format('%y ' . ($last_played->y == 1 ? 'year' : 'years') .' ago');
      if($last_played -> m > 0)
        return $last_played->format('%m ' . ($last_played->m == 1 ? 'minute' : 'minutes') .' ago');
      if($last_played -> d > 0)
        return $last_played->format('%d ' . ($last_played->d == 1 ? 'day' : 'days') .' ago');
      if($last_played -> h > 0)
        return $last_played->format('%h ' . ($last_played->h == 1 ? 'hour' : 'hours') .' ago');
      if($last_played -> i > 0)
        return $last_played->format('%i ' . ($last_played->i == 1 ? 'minute' : 'minutes') .' ago');
      if($last_played -> s > 0)
        return $last_played->format('less than a minute ago');
      //$last_played = date_diff($dt_now, $dt_lastplayed);
      //echo date_diff($dt_now, $dt_lastplayed);
      //echo $last_played->format('%R%a days');
    }

    function getKDA($kills, $deaths, $assists)
    {
      return $deaths ? number_format(($kills+$assists)/($deaths), 2, '.', '') . ' KDA ' : 'Perfect';
    }

    function totalCS($stats)
    {
      return $stats->totalMinionsKilled + $stats->neutralMinionsKilled;
    }

    function formatItemDescription($item)
    {
      $description = $item->description;

      preg_match("/(?<=\<stats>).*?(?=\<\/stats>)/", $description, $stats);
      $stats = preg_replace("/\+/", "<span style='color: rgb(0, 255, 0);'>+</span>", $stats);
      if(isset($stats[0]))
        $description = preg_replace("/(?<=\<stats>).*?(?=\<\/stats>)/", $stats[0], $description);

      $description = preg_replace("/\.<br>/", ". <br><br>", $description);
      $description = preg_replace("/Speed<br><unique>/", "Speed<br><br><unique>", $description);
      $description = preg_replace("/Damage <br><unique>/", "Damage <br><br><unique>", $description);

      $description = preg_replace("/UNIQUE/", "<span style='color: rgb(247, 245, 26);'>UNIQUE</span>", $description);


    //  $description = preg_replace("/Passive:/", "Passive -", $description);

      $description = preg_replace("/<br><br><br>/", "<br><br>", $description);

      preg_match_all('/\@(.*?)\@/', $description, $matches, PREG_SET_ORDER);

      // echo print_r($matches);
      //
      // echo '<br><br>'.$matches[5][1].'<br><br>';
      foreach($matches as $index => $key)
      {
        //echo $matches[$index][0] .' -> ' . $matches[$index][1] . '<br>';
        $description = preg_replace('/\@(.*?)\@/', $item->effect->$matches[$index][1], $description, 1);
      }

      $description = preg_replace("/Killing monsters grants <font color=/", "<br><br>Killing monsters grants <font color=", $description);

      $description = $description . "<br><br><span style='color: rgb(251, 170, 11);'>Cost</span>: " . $item->gold->total . " (" . $item->gold->sell . ")";

      return $description;
    }
