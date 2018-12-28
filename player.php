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

    // $info = json_decode(getSummonerInfo($player, $region_data["host"]));

    // if($info == false) {
    //   header('Location:index.php');
    //   return false;
    // }

    // $profileIcon = getProfileIconUrl($info->summoner->profileIconId);
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<!-- jQuery first, then Tether, then Bootstrap JS. -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <!-- Jquery redirect with Post Data -->
  <script src="https://cdn.rawgit.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>
  <!-- Font Awesome. -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<!-- Javascript -->
  <script src="js/jquery.circliful.min.js"></script>
  <script src="js/main.js"></script>

	<title>Sbornes | LoL</title>
  </head>
  <body class='background' id='player'>
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

    <section id="content">
      <section id="Info">
        <div class="container">
          <div class="mt-5 pt-2 pb-2 px-3 bg-mydark player-row row" id="lol-top-info">
            <div class="loader m-auto">
              <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
            </div>
          </div>
        </div>
      </section>

      <section id="match-history">
        <div class="container mt-3">
          <div class="row">
            <!-- START MATCH HISTORY COLUMN -->
            <div class="col-xs-12 col-md-9 order-2 pr-0" id="lol-right-column">
              <div class="loader text-center">
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
    </section>





<script>
    $( document ).ready(function() {
      $('#lol-right-column').find(".loader").show();
      $('#lol-top-info').find(".loader").show();

      load_top_info_data();

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

      function load_top_info_data() {
          var player = <?php echo json_encode($player); ?>;
          var region_data = <?php echo json_encode($region_data); ?>;

          $.ajax({
          type:'POST',
          url: "player_top_info_data.php",
          cache: false,
          data: {
            player: player, 
            region_data: region_data
          },
          success: function(html) {
            var jsonResult = JSON.parse(html);
  
            if(jsonResult.hasOwnProperty("status_code")) {
              load_error_info(jsonResult);
            } else {
              load_top_info(jsonResult);
            }
          },
        });
      }

      function load_error_info(data) {
        var player = <?php echo json_encode($player); ?>;
        var region_data = <?php echo json_encode($region_data); ?>;

        // $('#content').load("error_page.php", { data: data, player: player, region_data: region_data });

        $.redirect('index.php', {'data': data, 'player': player, 'region_data': region_data });

      }

      function load_top_info(data) {
          var player = <?php echo json_encode($player); ?>;
          var region_data = <?php echo json_encode($region_data); ?>;

          $.ajax({
          type:'POST',
          url: "player_top_info.php",
          cache: false,
          data: {
            player: player, 
            region_data: region_data,
            data : data
          },
          success: function(html) {
            $('#lol-top-info').append(html);

            $("#lol-top-info").each(function(i) {
              $(this).delay(100 * i).hide().fadeIn(500);
              console.log("fading: " + i + " " + this);
            });

            load_match_history();
            setupTooltip('#lol-top-info');

            $('#lol-top-info').find(".loader").hide();


          },
        });
      }

      function load_match_history() {
        var player = <?php echo json_encode($player); ?>;
        var region_data = <?php echo json_encode($region_data); ?>;

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
              
          });

          setupTooltip('#lol-right-column');

          }
        });
      }
</script>
</body>
</html>
