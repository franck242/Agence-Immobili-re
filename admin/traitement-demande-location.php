<?php
require_once ("menu.php");
forcerUtilisateurConnecte();


$account_admin = $_SESSION['admin'];


?>

<!Doctype HTML>
<html>

<head>
	<title></title>
	<link rel="stylesheet" href="css/liste-comptes.css" />
	<script type="text/javascript" src="dashbord.js"></script>
</head>


<body>

 

  
   
   
   
   
	<div id="main">

		<div class="head">
			<div class="col-div-6">
				<span style="font-size:30px;cursor:pointer; color: white;" class="nav">☰ Menu</span>
				<span style="font-size:30px;cursor:pointer; color: white;" class="nav2">☰ Menu</span>
			</div>

			<div class="col-div-6">
				<!-- <div class="profile">

			<img src="images/user.png" class="pro-img" />
			<p>Manoj Adhikari <span>UI / UX DESIGNER</span></p>
		</div> -->
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>
		<br />

		<div class="col-div-3">
			<div class="box">
				<p>6<br /><span>Appartements</span></p>
				<i class="fa fa-users box-icon"></i>
			</div>
		</div>
		<div class="col-div-3">
			<div class="box">
				<p>88<br /><span>Clients</span></p>
				<i class="fa fa-list box-icon"></i>
			</div>
		</div>
		<div class="col-div-3">
			<div class="box">
				<p>99<br /><span>Orders</span></p>
				<i class="fa fa-shopping-bag box-icon"></i>
			</div>
		</div>
		<div class="col-div-3">
			<div class="box">
				<p>78<br /><span>Tasks</span></p>
				<i class="fa fa-tasks box-icon"></i>
			</div>
		</div>
		<div class="clearfix"></div>
		<br /><br />
		<div class="col-div-8">
			<div class="box-8">
				<div class="content-box">
					<p>traitement demande de location </p>
					<br />
					<table>
						<tr>
						    <th>photos</th>
							<th>date depart</th>
							<th>date retour</th>
							<th>civilite</th>
							<th>prenom</th>
							<th>nom</th>
							<th>email</th>
							<th>adresse</th>
                            <th>code_postal</th>
							<th>ville</th>
							<th>telephone</th>
							<th>animaux</th>
					
						</tr>

						<?php
						// 1) On se connecte à la BDD et on envoie une requête pour récupérer toute la table "users"
						
						require_once("connexion-bdd.php");
						$requete = $connexion->prepare("SELECT * FROM reservation");
						$requete->execute();
						$resultat = $requete->fetchAll(PDO::FETCH_ASSOC);

						// verifier les données existant dans un tableau associatif
						if (is_array($resultat) && count($resultat) > 0) {

							foreach ($resultat as $elt) { ?>


								<tr>

								<td> <?php
                                        $id_appartements = $elt['Id_appartements'];
										$requete= $connexion->prepare("SELECT photos FROM appartements where Id_appartements=$id_appartements");
										$requete->execute();
						                 $resultats = $requete->fetch();
						
                                        echo " <a href='traitement-demande-location.php?apt=$id_appartements'><img src='uploads-images/" . $resultats['photos'] . "' alt=''></a>"; ?></td>
										
                                    <td><?php echo $elt['date_depart']; ?></td>
                                    <td><?php echo $elt['date_retour']; ?></td>
									<td><?php echo $elt['civilite']; ?></td>
									<td><?php echo $elt['firstname']; ?></td>
									<td><?php echo $elt['lastname']; ?></td>
									<td><?php echo $elt['email']; ?></td>
									<td><?php echo $elt['adresse']; ?></td>
									<td><?php echo $elt['code_postal']; ?></td>
                                    <td><?php echo $elt['telephone']; ?></td>
                                    <td><?php echo $elt['animaux']; ?></td>
									<td> <form method="post" action="supprimer-comptes.php"> <!-- L'action est définie dans un fichier supprimer-appartement.php -->
                                            <input type="hidden" name="id_appartement" value="<?php echo $id_appartement; ?>"> <!-- Champ caché pour stocker l'ID de l'appartement -->
                                            <input type="submit" value="Supprimer"> <!-- Bouton de soumission pour supprimer l'appartement -->
                                        </form>
									</td>
								</tr>

							<?php }


						} else {
							echo "<tr> <td colspan=7> aucune donnée dans la base données </td></tr> ";
						}

						?>



					</table>
				</div>
			</div>
		</div>
		<!-- 
		<div class="col-div-4">
			<div class="box-4">
				<div class="content-box">
					<p>Total Sale <span>Sell All</span></p>

					<div class="circle-wrap">
						<div class="circle">
							<div class="mask full">
								<div class="fill"></div>
							</div>
							<div class="mask half">
								<div class="fill"></div>
							</div>
							<div class="inside-circle"> 70% </div>
						</div>
					</div>
				</div>
			</div>
		</div> -->

		<div class="clearfix"></div>

		<footer>
			<h2>franck-mabikas</h2>
		</footer>
	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>

		$(".nav").click(function () {
			$("#mySidenav").css('width', '70px');
			$("#main").css('margin-left', '70px');
			$(".prenom").css('visibility', 'hidden');
			$(".prenom span").css('visibility', 'visible');
			$(".prenom span").css('margin-left', '-10px');
			$(".menu-a").css('visibility', 'hidden');
			$(".menu").css('visibility', 'visible');
			$(".menu").css('margin-left', '-8px');
			$(".nav").css('display', 'none');
			$(".nav2").css('display', 'block');
		});

		$(".nav2").click(function () {
			$("#mySidenav").css('width', '300px');
			$("#main").css('margin-left', '300px');
			$(".prenom").css('visibility', 'visible');
			$(".menu-a").css('visibility', 'visible');
			$(".menu").css('visibility', 'visible');
			$(".nav").css('display', 'block');
			$(".nav2").css('display', 'none');
		});

	</script>

</body>


</html>