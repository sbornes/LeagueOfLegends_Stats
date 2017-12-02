<?php
	include_once "functions.php";
	include_once "ChromePhp.php";

	$player = $_GET['player_name'];

	if(!isset($player) || !validatePlayer($player)){
	    header('Location:index.php');
	}

	$info = json_decode(getSummonerInfo($player));
	$profileIcon = getProfileIconUrl($info->profileiconId);


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">

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
    <form class="form-inline my-2 my-lg-0">
			<div class="input-group ">
					<input id="username" type="text" class="form-control" placeholder="Summoner...">
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button" onclick="userValid()"><i class="fa fa-search" aria-hidden="true"></i></button>
					</span>
				</div>
    </form>
  </div>
</nav>

	<section id="Info">
		<div class="container">
			<div class="mt-5 p-4 bg-mydark player-row row">
				<div class="player-icon">
					<img class="rounded-circle" src="<?php echo $profileIcon; ?>">
					<div class="platinum-border"> </div>
					<div class="player-level bg-dark border border-primary rounded p-2 px-4"> <?php echo $info->summonerLevel; ?> </div>
				</div>

				<div class="player-name pl-5">
					<?php echo $info->name; ?>
				</div>

			</div>
		</div>

	</section>

	<!-- jQuery first, then Tether, then Bootstrap JS. -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <!-- Font Awesome. -->
    <script src="https://use.fontawesome.com/734ab645c0.js"></script>
</body>
</html>
