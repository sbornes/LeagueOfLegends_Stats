<?php
    include_once "config.php";
    include_once "functions.php";

    $region_data = $_POST["region_data"];
    $info = json_decode(getSummonerInfo($_POST["player"], $region_data["host"]));
    $profileIcon = getProfileIconUrl($info->summoner->profileIconId);
    $recentMatch = getMatchRecentByAccount($info->summoner->accountId);
    $recentMatchData = getMatchRecentData($recentMatch, $region_data["host"]);

?>


<ul id="match-history" class="list-group">
    <?php foreach ($recentMatch->matches as $index => $value) : ?>
        <?php foreach ($recentMatchData[$index]->participantIdentities as $players) : ?>
        <?php if($players->player->summonerId == $info->summoner->id) { $GLOBALS['pId'] = $players->participantId; } ?>
        <?php endforeach ?>
        <?php foreach ($recentMatchData[$index]->participants as $players) : ?>
        <?php if($players->stats->participantId == $pId) : ?>
        <li data-type="history-row" id="match-<?php echo $index ?>" class="list-group-item mb-2 text-left list-group-item-<?php echo $players->stats->win ? "primary" : "danger"; /*if($players->stats->win) $gWins++;*/ ?>" data-summoner-id="<?php echo $info->summoner->id ?>" data-game-id="<?php echo $recentMatchData[$index]->gameId ?>">
            <div class="d-md-none d-flex w-100 justify-content-between">
            <p class="mb-0 ellipsis font-weight-bold small"><?php echo isset($gamemodes_const[$recentMatchData[$index]->queueId]) ? $gamemodes_const[$recentMatchData[$index]->queueId] : "Unknown" ?> <span class="mb-0 ellipsis text-muted">- <?php echo lastPlayed($recentMatchData[$index]->gameCreation) ?></span> </p>
            <small><?php echo secondsToMinutes($recentMatchData[$index]->gameDuration); ?></small>

            </div>
            <hr class="d-md-none d-block hr-small-device mb-3"/>
            <div class="game-info d-none d-md-inline-block align-middle small text-center mr-3">
            <p class="mb-0 ellipsis font-weight-bold"><?php echo isset($gamemodes_const[$recentMatchData[$index]->queueId]) ? $gamemodes_const[$recentMatchData[$index]->queueId] : "Unknown"; ?></p>
            <p class="mb-0 ellipsis text-muted"><?php echo lastPlayed($recentMatchData[$index]->gameCreation) ?></p>
            <hr/>
            <p class="mb-0 ellipsis font-weight-bold text-uppercase"><?php echo ($players->stats->win ? "Victory" : "Defeat"); ?></p>
            <p class="mb-0 ellipsis"><?php echo secondsToMinutes($recentMatchData[$index]->gameDuration); ?></p>
            </div>

            <?php 
            $championInfoAll = getChampionAll();
            $championId = $players->championId;
            $championInfoPre = array_search("{$championId}", array_column($championInfoAll["data"], "key"));
            $championInfoName = array_keys($championInfoAll["data"])[$championInfoPre]; 
            $championInfo = json_decode(json_encode($championInfoAll["data"][$championInfoName]), FALSE);
            $playersInMatch = json_decode(getPlayersInMatch($recentMatchData[$index])); 

            $summonerSpellAll = getSummonerSpellAll();
            $summonerSpellInfo1 = GetSummonerSpellSpecific($summonerSpellAll, $players->spell1Id); 
            $summonerSpellInfo2 = GetSummonerSpellSpecific($summonerSpellAll, $players->spell2Id); 
            $championMasterInfo = json_decode(findChampionMastery($info->summoner->id, $championId));
            $itemAll = getItemsAll(); 
            $item0 = $players->stats->item0; 
            $item1 = $players->stats->item1; 
            $item2 = $players->stats->item2;
            $item3 = $players->stats->item3;
            $item4 = $players->stats->item4;
            $item5 = $players->stats->item5; 
            $item6 = $players->stats->item6; 
            ?>

            <div class="stat-info-1 d-inline-block align-middle">
            <div class="d-inline-block align-top">
                <img class="match-history-champion-icon rounded-circle" src="<?php echo getChampionIconUrl($championInfo->image->full); ?>" data-champion-id="<?php $championId; ?>" data-toggle="tooltip" title="<p class='text-left'><span class='tooltip-champion'><?php echo $championInfo->name; ?></span> <span class='text-muted'><?php echo $championInfo->title;?></span></p><p class='mb-0 text-left'>Level: <span class='text-muted'><?php echo $championMasterInfo->championLevel; ?></span></p><p class='mb-0 text-left'>Points: <span class='text-muted'><?php echo $championMasterInfo->championPoints; ?> / <?php echo ($championMasterInfo->championPoints) + ($championMasterInfo->championPointsUntilNextLevel); ?></span></p>">
                <div class="mastery-icon mastery-icon-<?php echo $championMasterInfo->championLevel; ?>"></div>
                <div class="champion-icon-gold-ring"></div>
                <!-- <img class="match-history-mastery-icon rounded-circle border-<?php echo $players->stats->win ? "win" : "loss"; ?>" style="background-color: <?php echo $players->stats->win ? "#b8daff" : "#f5c6cb"; ?>;" src="assets/champion-master-icons/<?php echo $championMasterInfo->championLevel; ?>.png" data-toggle="tooltip" title="<p class='text-left'><span class='tooltip-champion'><?php echo $championInfo->name; ?></span> <span class='text-muted'><?php echo $championInfo->title;?></span></p><p class='mb-0 text-left'>Level: <span class='text-muted'><?php echo $championMasterInfo->championLevel; ?></span></p><p class='mb-0 text-left'>Points: <span class='text-muted'><?php echo $championMasterInfo->championPoints; ?> / <?php echo ($championMasterInfo->championPoints) + ($championMasterInfo->championPointsUntilNextLevel); ?></span></p>"> -->
            </div>
            <div class="stat-summoner-spell d-inline-block align-middle ml-2">
                <div class="match-history-summoner-icon d-inline-block">
                <img class="rounded-circle d-block mb-2" src="<?php echo getSummonerSpellIcon($summonerSpellInfo1->image->full); ?>" data-summoner-spell-id="<?php echo $summonerSpellInfo1->id; ?>" data-toggle="tooltip" title="<p class='text-left tooltip-summoner-spell'><?php echo $summonerSpellInfo1->name; ?></p><p class='m-0 text-left'><?php echo $summonerSpellInfo1->description;?></p>">
                <img class="rounded-circle d-block" src="<?php echo getSummonerSpellIcon($summonerSpellInfo2->image->full); ?>" data-summoner-spell-id="<?php echo $summonerSpellInfo2->id; ?>" data-toggle="tooltip" title="<p class='text-left tooltip-summoner-spell'><?php echo $summonerSpellInfo2->name; ?></p><p class='m-0 text-left'><?php echo $summonerSpellInfo2->description;?></p>">
                </div>

                <div class="match-history-rune-icon d-inline-block">
                <img class="rounded-circle d-block mb-2" src="assets/perkStyle/<?php echo $players->stats->perkPrimaryStyle; ?>.png">
                <img class="rounded-circle d-block" src="assets/perkStyle/<?php echo $players->stats->perkSubStyle; ?>.png">
                </div>
            </div>
            </div>

            <div class="kill-stats-1 d-inline-block align-middle text-center mx-2">
            <p class="mb-0"><?php echo $players->stats->kills . " / " . $players->stats->deaths . " / " . $players->stats->assists ?></p>
            <p class="mb-0 text-muted small text-uppercase"><?php echo getKDA($players->stats->kills, $players->stats->deaths, $players->stats->assists); ?> </p>
            <?php if($players->stats->largestMultiKill > 1) : ?>
                <p class="mb-0 text-muted small text-uppercase"><span class="badge badge-pill badge-primary"><?php echo $mutlikill_const[$players->stats->largestMultiKill]; ?></span></p>
            <?php endif ?>
            </div>

            <div class="kill-stats-2 d-inline-block align-middle text-center mx-2">
            <p class="mb-0 text-uppercase">Level <?php echo $players->stats->champLevel ?></p>
            <p class="mb-0 text-muted small text-uppercase"><?php echo totalCS($players->stats); ?> cs</p>
            </div>

            <div class="items-1 d-inline-block align-middle text-center ml-2">
            <div id="row-1">

            <?php if(isset($itemAll->data->$item0)) : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item0->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item0->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item0); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>

            <?php if(isset($itemAll->data->$item1)) : ?>
                <div class="item d-inline-block"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item1->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item1->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item1); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>

            <?php if(isset($itemAll->data->$item2)) : ?>
                <div class="item d-inline-block"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item2->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item2->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item2); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>
            </div>
            <div id="row-2">
            <?php if(isset($itemAll->data->$item3)) : ?>
                <div class="item d-inline-block"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item3->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item3->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item3); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>

            <?php if(isset($itemAll->data->$item4)) : ?>
                <div class="item d-inline-block"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item4->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item4->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item4); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>

            <?php if(isset($itemAll->data->$item5)) : ?>
                <div class="item d-inline-block"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item5->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item5->name; ?></p><p class='m-0 text-left'><?php echo formatItemDescription($itemAll->data->$item5); ?></p>"></div>
            <?php else : ?>
                <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>
            </div>
            </div>
            <div class="items-2 d-inline-block align-middle text-center mr-2">
            <?php if(isset($itemAll->data->$item6)) : ?>
            <div class="item d-block-inline"><img class="rounded" src="<?php echo getItemIcon($itemAll->data->$item6->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 text-left tooltip-item'><?php echo $itemAll->data->$item6->name; ?></p><p class='m-0 text-left'><?php echo $itemAll->data->$item6->description; ?></p>"></div>
            <?php else : ?>
            <div class="item d-inline-block mb-1"><img class="rounded" src="assets/no-item.png"></div>
            <?php endif ?>
            </div>

            <div class="players-in-match-1 d-inline-block align-middle mx-2">
            <div class="blue-team d-inline-block">
            <?php foreach($playersInMatch->blue->player as $matchPlayers) : ?>
                
            <?php
                $championId = $matchPlayers->champid;
                $championInfoPre = array_search("{$championId}", array_column($championInfoAll["data"], "key"));
                $championInfoName = array_keys($championInfoAll["data"])[$championInfoPre]; 
                $championInfo = json_decode(json_encode($championInfoAll["data"][$championInfoName]), FALSE);
            ?>
            <div class="match-history-player-other ellipsis"> <img class="match-history-champion-icon-other" src="<?php echo getChampionIconUrl($championInfo->image->full); ?>"  data-toggle="tooltip" title="<p class='mb-0 tooltip-champion'><?php echo $championInfo->name; ?></p>"><span class="match-history-player-text small"><a href="player.php?player_name=<?php echo $matchPlayers->summonerName; ?>&region=<?php echo $region_data["code"]?>" class="match-history-player-link"> <?php echo $matchPlayers->summonerName; ?> </a> </span> </div>
            <?php endforeach; ?>
            </div>
            <div class="red-team d-inline-block">
            <?php foreach($playersInMatch->red->player as $matchPlayers) : ?>
            <?php
                $championId = $matchPlayers->champid;
                $championInfoPre = array_search("{$championId}", array_column($championInfoAll["data"], "key"));
                $championInfoName = array_keys($championInfoAll["data"])[$championInfoPre]; 
                $championInfo = json_decode(json_encode($championInfoAll["data"][$championInfoName]), FALSE);
            ?>
                <div class="match-history-player-other ellipsis"> <img class="match-history-champion-icon-other" src="<?php echo getChampionIconUrl($championInfo->image->full); ?>" data-toggle="tooltip" title="<p class='mb-0 tooltip-champion'><?php echo $championInfo->name; ?></p>"><span class="match-history-player-text small"><a href="player.php?player_name=<?php echo $matchPlayers->summonerName; ?>&region=<?php echo $region_data["code"]?>" class="match-history-player-link"> <?php echo $matchPlayers->summonerName; ?> </a> </span> </div>
            <?php endforeach; ?>
            </div>
            </div>
        </li>
        <?php endif ?>
        <?php endforeach ?>
    <?php endforeach ?>
    </ul>