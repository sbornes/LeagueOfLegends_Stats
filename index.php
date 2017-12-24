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
<body class="d-flex align-items-center h-100">
	<section id="SearchBar" class="w-100">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6 m-auto">
          <!-- <div id="formAlert" class="alert alert-warning hide">
            <strong>Warning!</strong> Invalid Summoner.
          </div> -->
					<div class="input-group ">
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
