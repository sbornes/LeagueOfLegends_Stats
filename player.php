<?php
    ini_set('max_execution_time', 300);

    include_once "functions.php";
    include_once "ChromePhp.php";

    $player = $_GET['player_name'];

    if (!isset($player)) {
        header('Location:index.php');
    }

    retrieveDataSummoner($player);

    $info = json_decode(getSummonerInfo($player));
    $profileIcon = getProfileIconUrl($info->summoner->profileiconId);
    $recentMatch = getMatchRecentByAccount($info->summoner->accountId);
    $recentMatchData = getMatchRecentData($recentMatch);

    //ChromePhp::log($recentMatch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- FontAwesome Glyphicon -->
  <!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<!-- jQuery first, then Tether, then Bootstrap JS. -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <!-- Font Awesome. -->
  <script src="https://use.fontawesome.com/734ab645c0.js"></script>
	<!-- Javascript -->
  <script src="js/main.js"></script>

	<title>Sbornes | LoL</title>
</head>
<body>

  <nav class="navbar navbar-expand-md bg-mydark">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/league_website">
  		<img src="assets/icon.png" width="30" height="30" class="d-inline-block align-top" alt="">
  	</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <!-- <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Disabled</a>
        </li> -->
      </ul>
      <div class="my-2 my-lg-0">
  			<div class="input-group ">
  					<input id="username" type="text" class="form-control" placeholder="Summoner...">
  					<span class="input-group-btn">
  						<button id="search-btn" class="btn btn-primary" type="button" onclick="userValid()"><i class="fa fa-search" aria-hidden="true"></i></button>
  					</span>
  				</div>
      </div>
    </div>
  </nav>

  <section id="Info">
  	<div class="container">
  		<div class="mt-5 pt-2 pb-2 px-3 bg-mydark player-row row">
        <div class="col-md-12 col-lg-5">
    			<div class="player-icon d-lg-inline-block mx-auto">
    				<img class="rounded-circle" src="<?php echo $profileIcon; ?>">
    				<div class="<?php echo isset($info->rank->solo->tier) ?
              strtolower($info->rank->solo->tier) : ( isset($info->rank->flex->tier) ?
              strtolower($info->rank->flex->tier) : 'unranked' ); ?>-border">
            </div>
    			</div>
    			<div class="player-name pl-lg-5 mt-4 mt-lg-0 mb-4 d-lg-inline-block">
            <p class="text-center mb-0"><?php echo $info->summoner->name; ?> <span class="badge badge-primary"><?php echo $info->summoner->summonerLevel; ?></span></p>
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
              <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->solo->leagueName; ?><p class='m-0'><?php echo "WinRatio: " . round(($info->rank->solo->wins/(($info->rank->solo->wins)+($info->rank->solo->losses)))*100);?>%</p><p class='m-0'><?php echo $info->rank->solo->wins; ?>W <?php echo $info->rank->solo->losses; ?>L </p>">
                <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->solo->tier.'_'.$info->rank->solo->rank); ?>.png">
              </div>
              <div class="player-rank-text d-lg-inline-block align-middle">
                <p class="p-queue text-muted"><small>SOLO/DUO</small></p>
                <p class="p-rank lead"><?php echo $info->rank->solo->tier . " " . $info->rank->solo->rank ?></p>
                <p class="p-lp text-muted"><small><?php echo $info->rank->solo->leaguePoints . "LP"; ?></small></p>
              </div>
            </div>
            <?php endif; ?>
            <?php if(isset($info->rank->flex)) : ?>
            <div class="player-rank-flex d-md-inline-block px-md-3 px-lg-2 px-xl-4">
              <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->flex->leagueName; ?><p class='m-0'><?php echo "WinRatio: " . round(($info->rank->flex->wins/(($info->rank->flex->wins)+($info->rank->flex->losses)))*100);?>%</p><p class='m-0'><?php echo $info->rank->flex->wins; ?>W <?php echo $info->rank->flex->losses; ?>L </p>">
                <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->flex->tier.'_'.$info->rank->flex->rank); ?>.png">
              </div>
              <div class="player-rank-text d-lg-inline-block align-middle">
                <p class="p-queue text-muted"><small>FLEX 5V5</small></p>
                <p class="p-rank lead"><?php echo $info->rank->flex->tier . " " . $info->rank->flex->rank ?></p>
                <p class="p-lp text-muted"><small><?php echo $info->rank->flex->leaguePoints . "LP"; ?></small></p>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
  		</div>
  	</div>
  </section>

  <section id="match-history">
    <div class="container mt-5">
      <ul class="list-group">
        <?php foreach ($recentMatch->matches as $index => $value) : ?>
        <li id="match-<?php echo $index ?>" class="list-group-item" data-summoner-id="<?php echo $info->summoner->accountId ?>" data-game-id="<?php echo $recentMatchData[$index]->gameId ?>">
          <?php foreach ($recentMatchData[$index]->participantIdentities as $players) : ?>
            <?php echo $players->player->summonerName ?>
          <?php endforeach ?>


          <br>
        </li>
        <?php endforeach ?>
      </ul>
    </div>
  </section>

</body>
</html>
