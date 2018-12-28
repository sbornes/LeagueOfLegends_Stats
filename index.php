<?php 
    $data = isset($_POST["data"]) ? $_POST["data"] : null;
    $player_name = isset($_POST["player"]) ? $_POST["player"] : null;
    $region_data = isset($_POST["region_data"]) ? $_POST["region_data"] : null;

    switch($data["status_code"]) {
        case "404": {
            $heading = "<i class=\"fas fa-exclamation-circle fa-2x\"></i> <span class=\"pl-2\"><strong>Invalid Summoner Name </strong></span> ";
            $message = "Could not find user {$player_name} from {$region_data["name"]}.";
            break;
        }
        case "-1": {
          $heading = "<i class=\"fas fa-exclamation-circle fa-2x\"></i> <span class=\"pl-2\"><strong>Invalid Summoner Name </strong></span> ";
          $message = "Can not search for user {$player_name} invalid format.";
          break;
        }
        default: {
          $heading = "<i class=\"fas fa-exclamation-circle fa-2x\"></i> <span class=\"pl-2\"><strong>Uh Oh</strong></span> ";
          $message = "Status Code: {$data["status_code"]} <br>Status Message: {$data["message"]}.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <!-- jQuery first, then Tether, then Bootstrap JS. -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <!-- Font Awesome. -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="css/flag-icon.min.css">
	<!-- Javascript -->
  <script src="js/main.js"></script>

	<title>Sbornes | LoL</title>
</head>
<body class="d-flex align-items-end h-50 background" id="index">



	<section id="SearchBar" class="w-100">
		<div class="container">
    <?php if(isset($data)) : ?>
        <div class="row">
            <div class="col-12 col-md-6 m-auto">
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <h4 class="alert-heading d-flex align-items-center"><?php echo $heading; ?></h4>

                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <hr class="w-100">
                  <p class="mb-0"><span ><?php echo $message; ?></span></p>
                </div>
            </div>
        </div>
  <?php endif; ?>

			<div class="row">
				<div class="col-12 col-md-6 m-auto">
          <!-- <div id="formAlert" class="alert alert-warning hide">
            <strong>Warning!</strong> Invalid Summoner.
          </div> -->
					<div class="input-group ">
          <div class="input-group-prepend">
          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="regionMenu" value="OCE">OCE</button>
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
		</div>
	</section>
</body>
</html>
