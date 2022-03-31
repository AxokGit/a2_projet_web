<!DOCTYPE html>

<html lang="fr">
	<head>
		<title>Connexion - CTS</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="http://ctsstatic.fr/assets/images/logo_petit.png">
		<link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/index.css">
		<meta name="description" content="Page d'accueil de CTS">
		<link rel="manifest" href="./manifest/manifest.json" />
		<meta name="theme-color" content="#3f51b5"/>
		<link rel="apple-touch-icon" href="http://ctsstatic.fr/assets/images/logo512.png">
	</head>
	<body>
		<div class="limiter">
			<div class="boite-login">
				<div id="zone-login" class="zone-login hidden">
					<form method="post" class="form-login validate">
						<div class="info_message">
							Erreur de connexion Internet, veuillez vous connecter
							<br>
							<a href="/favoris.php">Accès aux favoris</a>
						</div>

						<span class="form-title"><img src="http://ctsstatic.fr/assets/images/logo.png" alt="Cesi Ton Stage"></span>

						<div class="zone-input validate-input" data-validate="Saisissez un identifiant" style="margin-bottom: 16px;">
							<input class="input-forms" type="text" name="user" placeholder="Identifiant" autocapitalize="off" autocomplete="off">
							<span class="focus-input"></span>
						</div>

						<div class="zone-input validate-input" data-validate = "Saisissez un mot de passe">
							<input class="input-forms" id="passwd" type="password" 	me="pass" placeholder="Mot de passe">
							<span class="focus-input"></span>
						</div>

						<div style="text-align:right!important;padding-top: 13px;padding-bottom: 23px;">
							<a href="#" class="txt2">Identifiant / Mot de passe</a>
							<span class="txt1"> perdu ?</span>
						</div>

						<div class="boite-form-btn">
							<button class="form-btn">CONNEXION</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php require "controller/Script.php" ?>
		<script src="http://ctsstatic.fr/assets/js/index.js"></script>
	</body>
</html>
