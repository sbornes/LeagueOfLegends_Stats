<?php
    include_once "config.php";
    include_once "functions.php";

    $player = $_POST["player"];
    $region_data = $_POST["region_data"];

    $info = json_decode($_POST["data"]);
?>


    <?php $profileIcon = getProfileIconUrl($info->summoner->profileIconId); ?>

    <div class="col-md-12 col-lg-5">
        <div class="player-icon d-lg-inline-block mx-auto">
            <img class="rounded-circle" src="<?php echo $profileIcon; ?>">
            <div class="<?php echo isset($info->rank->solo->tier) ?
            strtolower($info->rank->solo->tier) : ( isset($info->rank->flex->tier) ?
            strtolower($info->rank->flex->tier) : 'unranked' ); ?>-border">
            </div>
        </div>
        <div class="player-name pl-lg-5 mt-5 mt-lg-0 mb-4 d-lg-inline-block">
            <p class="text-center mb-0">
                <?php echo $info->summoner->name; ?> <span class="badge badge-primary">
                    <?php echo $info->summoner->summonerLevel; ?></span></p>
            <div class="player-region d-none d-lg-block text-muted mb-0">
                <small>#OCEANIA</small>
            </div>
        </div>

    </div>

    <?php if(isset($info->rank)) : ?>
        <div class="col-md-12 col-lg-7">
            <div class="player-rank mx-auto float-lg-right text-center">
                <?php if(isset($info->rank->solo)) : ?>
                <div class="player-rank-solo d-md-inline-block px-md-3 px-lg-2 px-xl-4o">
                    <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->solo->leagueName; ?><p class='m-0'><?php echo round(($info->rank->solo->wins/(($info->rank->solo->wins)+($info->rank->solo->losses)))*100);?>% Win rate</p><p class='m-0'><?php echo $info->rank->solo->wins; ?>W <?php echo $info->rank->solo->losses; ?>L </p>">
                        <!-- <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->solo->tier.'_'.$info->rank->solo->rank); ?>.png"> -->
                        <img class="player-rank-icon" src="assets/tier-icons-new/<?php echo ucfirst(strtolower($info->rank->solo->tier.'_Emblem')); ?>.png">
                    </div>
                    <div class="player-rank-text d-lg-inline-block align-middle">
                        <p class="p-queue text-muted"><small>SOLO/DUO</small></p>
                        <p class="p-rank lead">
                            <?php echo $info->rank->solo->tier . " " . $info->rank->solo->rank ?>
                        </p>
                        <p class="p-lp text-muted"><small>
                                <?php echo $info->rank->solo->leaguePoints . "LP"; ?></small></p>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(isset($info->rank->flex)) : ?>
                <div class="player-rank-flex d-md-inline-block px-md-3 px-lg-2 px-xl-4">
                    <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->flex->leagueName; ?><p class='m-0'><?php echo round(($info->rank->flex->wins/(($info->rank->flex->wins)+($info->rank->flex->losses)))*100);?>% Win rate</p><p class='m-0'><?php echo $info->rank->flex->wins; ?>W <?php echo $info->rank->flex->losses; ?>L </p>">
                        <!-- <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->flex->tier.'_'.$info->rank->flex->rank); ?>.png"> -->
                        <img class="player-rank-icon" src="assets/tier-icons-new/<?php echo ucfirst(strtolower($info->rank->flex->tier.'_Emblem')); ?>.png">
                    </div>
                    <div class="player-rank-text d-lg-inline-block align-middle">
                        <p class="p-queue text-muted"><small>FLEX 5V5</small></p>
                        <p class="p-rank lead">
                            <?php echo $info->rank->flex->tier . " " . $info->rank->flex->rank ?>
                        </p>
                        <p class="p-lp text-muted">
                            <small><?php echo $info->rank->flex->leaguePoints . "LP"; ?></small>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
