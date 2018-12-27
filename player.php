<?php
    ini_set('max_execution_time', 300);

    include_once "config.php";
    include_once "functions.php";
    include_once "ChromePhp.php";

    $player = $_GET['player_name'];
    $region = $_GET['region'];

    if (!isset($player) || !isset($region)) {
        header('Location:index.php');
        return false;
    }

    $region_data = getRegionData($region);
    if(!$region_data) {
      header('Location:index.php');
      return false;
    }

    //retrieveDataSummoner($player);

    $info = json_decode(getSummonerInfo($player, $region_data["host"]));

    if($info == false) {
      header('Location:index.php');
      return false;
    }

    $profileIcon = getProfileIconUrl($info->summoner->profileIconId);
    // $recentMatch = getMatchRecentByAccount($info->summoner->accountId);
    // $recentMatchData = getMatchRecentData($recentMatch, $region_data["host"]);

    $GLOBALS['gWins'] = 0;
    //ChromePhp::log($recentMatch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Circliful -->
  <link href="css/jquery.circliful.css" rel="stylesheet" type="text/css" />
  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<!-- jQuery first, then Tether, then Bootstrap JS. -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <!-- Font Awesome. -->
  <script src="https://use.fontawesome.com/734ab645c0.js"></script>
	<!-- Javascript -->
  <script src="js/jquery.circliful.min.js"></script>
  <script src="js/main.js"></script>

	<title>Sbornes | LoL</title>
</head>
<body class='background'>
  <nav class="navbar navbar-expand-md bg-navdark">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/league">
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
          <div class="input-group-prepend">
          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="regionMenu" value=<?php echo $region ?>><?php echo $region ?></button>
            <div class="dropdown-menu">
            <a class="dropdown-item" href="#" data-code="NA" >North America</a>
              <a class="dropdown-item" href="#" data-code="EUNE">Europe Nordic & East</a>
              <a class="dropdown-item" href="#" data-code="EUW">Europe West</a>
              <a class="dropdown-item" href="#" data-code="OCE">Oceania</a>
              <a class="dropdown-item" href="#" data-code="LAN">LAN</a>
              <a class="dropdown-item" href="#" data-code="LAS">LAS</a>

              <div role="separator" class="dropdown-divider"></div>
          
              <a class="dropdown-item" href="#" data-code="KR"><span class="flag-icon flag-icon-kr"></span> Korea</a>
              <a class="dropdown-item" href="#" data-code="JP"><span class="flag-icon flag-icon-jp"></span> Japan</a>
              <a class="dropdown-item" href="#" data-code="BR"><span class="flag-icon flag-icon-br"></span> Brazil</a>
              <a class="dropdown-item" href="#" data-code="TR"><span class="flag-icon flag-icon-tr"></span> Turkey</a>
              <a class="dropdown-item" href="#" data-code="RU"><span class="flag-icon flag-icon-ru"></span> Russia</a>
            </div>
          </div>
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
    			<div class="player-name pl-lg-5 mt-5 mt-lg-0 mb-4 d-lg-inline-block">
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
              <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->solo->leagueName; ?><p class='m-0'><?php echo round(($info->rank->solo->wins/(($info->rank->solo->wins)+($info->rank->solo->losses)))*100);?>% Win rate</p><p class='m-0'><?php echo $info->rank->solo->wins; ?>W <?php echo $info->rank->solo->losses; ?>L </p>">
                <!-- <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->solo->tier.'_'.$info->rank->solo->rank); ?>.png"> -->
                  <img class="player-rank-icon" src="assets/tier-icons-new/<?php echo ucfirst(strtolower($info->rank->solo->tier.'_Emblem')); ?>.png">
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
              <div class="d-sm-inline-block align-middle" data-toggle="tooltip" title="<?php echo $info->rank->flex->leagueName; ?><p class='m-0'><?php echo round(($info->rank->flex->wins/(($info->rank->flex->wins)+($info->rank->flex->losses)))*100);?>% Win rate</p><p class='m-0'><?php echo $info->rank->flex->wins; ?>W <?php echo $info->rank->flex->losses; ?>L </p>">
                <!-- <img class="player-rank-icon" src="assets/tier-icons/<?php echo strtolower($info->rank->flex->tier.'_'.$info->rank->flex->rank); ?>.png"> -->
                <img class="player-rank-icon" src="assets/tier-icons-new/<?php echo ucfirst(strtolower($info->rank->flex->tier.'_Emblem')); ?>.png">
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
    <div class="container mt-3">
      <div class="row">
        <!-- START MATCH HISTORY COLUMN -->
        <div class="col-xs-12 col-md-9 order-2 pr-0" id="lol-right-column">
          <div class="loader">
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
          </div>
          
        </div>
        <!-- END MATCH HISTORY COLUMN -->
        <!-- START DATA COLUMN -->
        <div class="col-xs-12 col-md-3 order-1 bg-mydark" id="lol-left-column">
            <div id="winrate-circle"></div>
            <?php /* echo print_r(getRecentPlayedWith($recentMatch, $recentMatchData)); */ ?>
            <pre>  <?php /*echo print_r(getMostPlayedRole($recentMatch));*/ ?> </pre>
            <pre> WINS: <?php echo $gWins ?> </pre>
        </div>
        <!-- END DATA COLUMN -->
      </div>
    </div>
  </section>



<script>
    $( document ).ready(function() {
      $('#lol-right-column').find(".loader").show();
      var player = <?php echo json_encode($player); ?>;
      var region_data = <?php echo json_encode($region_data); ?>;
      // $('#lol-right-column').load("player_match_history.php", {info: data_info, recentMatch: data_match, recentMatchData: data_match_data, region: data_region }, function(responseTxt, statusTxt, xhr){
      //   if(statusTxt == "success")
      //     alert("External content loaded successfully!");
      //   if(statusTxt == "error")
      //     alert("Error: " + xhr.status + ": " + xhr.statusText);

      //   alert("hu");
      // });

      $.ajax({
        type:'POST',
        url: "player_match_history.php",
        cache: false,
        data: {
          player: player, 
          region_data: region_data
        },
        success: function(html) {
          $('#lol-right-column').append(html);
          console.log("succes");
          $('#lol-right-column').find(".loader").hide();

          $("#lol-right-column > ul > li[data-type='history-row']").each(function(i) {
            $(this).delay(100 * i).hide().fadeIn(500);
            console.log("fading: " + i + " " + this);
        });
        },
        complete: function(){
          $('#loading-image').hide();
        }
      });

 

      $("#winrate-circle").circliful({
        animation: 1,
                animationStep: 6,
                foregroundBorderWidth: 5,
                backgroundBorderWidth: 1,
                percent: '<?php echo($gWins/20*100);?>',
                foregroundColor: '#3498DB',
                text: 'Win Rate',
                textY: 130
              });
      });
</script>
</body>
</html>
