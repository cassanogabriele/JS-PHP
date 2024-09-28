<?php
session_start();
require_once('./php/connectdb.php');
//session_destroy();
?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8"> 
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Problèmes informatiques</title>			
			<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
			<link rel="stylesheet" href="https://bootswatch.com/3/darkly/bootstrap.min.css">
			<link rel="stylesheet" href="css/style.css">	
		</head>		
			
		<body>				
			<div class="container">
				<div class="alert alert-danger" role="alert">
				&#9888; Ce site est un site de démonstration. Je possède les données que j'ai encodée et toute autre informations non encodée, via l'application, 
				par mes soins, est à la responsabilité de l'utilisateur qui l'aura encodée, ces informations sont stockées en base de données. Je sais donc 
				prouver que je ne les ai pas publiées !				</div>
				
								
				<h1 id="title">Discussions de solutions <small id="sub-title">pour les développeurs</small></h1>
				
				<blockquote class="blockquote" id="presentation">
				  <p class="mb-0">Ce forum est destinés à tous les développeurs qui cherchent des solutions à leurs problèmes.</p>
				  <footer class="blockquote-footer"><span id="slogan">Les échanges entre développeurs <cite title="Source Title">permettront de résoudre vos soucis.</cite></span></footer>
				</blockquote>
				
				<div class="card mb-3" id="first-card">
					<h3 class="card-header" id="title-card">Poser vos questions</h3>
				
  				     <div class="card-body">
						<h5 class="card-title" style="color:black !important;">Tout type de sujet :</h5>
						
						<blockquote class="blockquote" id="problems">
							<h6 class="card-subtitle text-muted">Dépannage, programmation web ou logiciel, configuration, référencement, ...</h6>
						</blockquote>
					</div>
			
					<div class="card" id="empty-div">
					  <div class="card-body">						
						
					  </div>
					</div>					

					<div class="card">
					  <div class="card-body">						
						<p class="card-text" id="card-text">Indiquez votre soucis avec un maximum de précision afin d'être aidé rapidement</p>						
					  </div>
					</div>	
				</div>		
				
				<div id="alert-container"></div>
				
				<div class="jumbotron ds">
					<blockquote class="blockquote" id="presentation">
						<p class="mb-0">Entrez votre problème</p>						
					</blockquote>
				
					<p id="message"></p>
					
					<form id="issueInputForm" action="index.php" method="post">					
						<div class="form-group">							
							<label for="issuePseudo">Pseudo</label>
							
							<input type="text" class="form-control" id="issuePseudo" name="issuePseudo" placeholder="Veuillez entrer votre pseudo">
						
							<br/>
							
							<?php 		
							// Affichage d'un message d'erreur si l'utilisateur entre de mauvaises données dans le formulaire.
							if(isset($_POST['valider']) && isset($_POST['issuePseudo']) && empty($_POST['issuePseudo'])){
								echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un pseudo</p></div>';			
							} 						
							?>							
						</div>					
						
						<div class="form-group">
							<label for="issueEmail">Email</label>
							<input class="form-control" type="email" name="issueEmail" id="issueEmail" placeholder="Veuillez entrer votre pseudo"/>
							
							<br/>
							
							<?php 						
							if(isset($_POST['valider']) && isset($_POST['issueEmail']) && empty($_POST['issueEmail'])){
								echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un email</p></div>';			
							}							
							?>							
						</div>
						
						<div class="form-group">
							<label for="issueSubject">Sujet</label>
							<input type="text" class="form-control" name="issueSubject" id="issueSubject" placeholder="Entrez le sujet de votre problème">						
							
							<br/>
							
							<?php 							
							if(isset($_POST['valider']) && isset($_POST['issueSubject']) && empty($_POST['issueSubject'])){
								echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un sujet</p></div>';			
							}							
							?>						
						</div>
						
						<div class="form-group">
							<label for="issueDesc">Description du problème</label>
							<textarea class="form-control" name="issueDescInput" id="issueDescInput" rows="3" placeholder="Décrivez votre problème"></textarea>
							
							<br/>
							
							<?php 							
							if(isset($_POST['valider']) && isset($_POST['issueDescInput']) && empty($_POST['issueDescInput'])){
								echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un description de votre problème</p></div>';			
							}							
							?>						
						</div>
						
						<div class="form-group">
							<label for="issueSeverity">Importance</label>
							
							<select id="issueSeverityInput" name="issueSeverityInput" class="form-control">
								<option value="Faible">Faible</option>
								<option value="Moyenne">Moyenne</option>
								<option value="Haute">Haute</option>
							</select>
							
							<br/>
							
							<?php 								
							if(isset($_POST['valider']) && isset($_POST['issueSeverityInput']) && (!empty($_POST['issueSeverityInput'] == 'Low'))){
								echo '<div class="alert alert-danger" role="alert"><p class="center"><p class="center">Veuillez sélectionner une importance</p></div>';			
							}							
							?>	
						</div>	

						<input type="hidden" value="">
						
						<input type="submit" name="valider" class="btn btn-primary center-block buttons" value="Soumettre">
					</form>
				</div>
				
				<h3 id="title_current">Demande(s) courante(s)</h3>
				
				<div id="issuesListUpdated">
				
				</div>
				
				<?php 
				// Récupération des messages les plus récents
				$current_requests = $bdd->query("SELECT * FROM tickets ORDER BY date_ticket DESC LIMIT 3");								
					
				echo "<div id='issuesList'>";
				
				while ($row = $current_requests->fetch()) {
					echo '<div class="well ds">
						<h4>Issue ID: '.$row['ticket_id'].' </h4>
						<p style="margin-bottom:30px;margin-top:25px;"><span class="label label-info" style="font-size:18px;">'.$row['status'].'</span></p>
						<h4>Pseudo : '.$row['pseudo'].' </h4>
						<h4>Email : '.$row['email'].' </h4>
						<p>
							<blockquote class="blockquote"> 
								<p class="mb-0" style="font-size:18px;">'.$row['subject'].'</p>
							</blockquote>
						</p>
						<p>
							<blockquote class="blockquote"> 
								<p class="mb-0" style="font-size:18px;">'.$row['pseudo'].' a écrit : '.$row['description'].'</p>
							</blockquote>
						</p>
						<p style="margin-top:20px;font-size:18px;"><span class="glyphicon glyphicon-time"></span>'.$row['severity'].'</p>	
						<p>
							<form method="post" id="close">
								<a href="#" onclick="setStatusClosed(\''.$row['id'].'\', \''.$row['status'].'\')" class="'.($row['status'] == 'Ouvert' ? 'btn btn-warning' : 'btn btn-success').'" style="font-size:18px;">
									'.($row['status'] == 'Ouvert' ? 'Fermer' : 'Ouvrir').'
								</a>
								&nbsp;
								<a href="#" onclick="deleteIssue(\''.$row['id'].'\')" class="btn btn-danger" style="font-size:18px;">Supprimer</a>
							</form>
						</p>
					</div>';
				}
				
				echo "</div>";
				?>				
				
				<div class="jumbotron ds">						
					<?php 
					setlocale(LC_TIME, 'fr_FR.utf8','fra');
					
					// Paramètres de pagination
					$itemsPerPage = 2;
					$page = isset($_GET['page']) ? $_GET['page'] : 1;
					$offset = ($page - 1) * $itemsPerPage;
					
					// Calculer le nombre total de pages	
					$count = $bdd->query('SELECT COUNT(*) AS total FROM tickets');
					
					while($fetch_count = $count->fetch()){
						$totalItems = $fetch_count['total'];
					}
				
					$totalPages = ceil($totalItems / $itemsPerPage);					
					
					//$totalPages = ceil($totalItems / $itemsPerPage);
				  
					$tickets = $bdd->query("SELECT * FROM tickets LIMIT $offset, $itemsPerPage");										
					
					while($fetch_tickets = $tickets->fetch()){
						$date = date_create($fetch_tickets['date_ticket']);					
						
						echo "<div>";
						echo '<div class="well ds">	
								<p id="date-css"style=""><span style="margin-left:5px;">'.date_format($date, 'd/m/Y').'</span></p>
								<div class="alert alert-primary titles"role="alert">Demande soumise</div>
								<p class="lead">Demande n° : '.$fetch_tickets['ticket_id'].'</p>
								<hr>
								<p class="lead">Pseudo : '.$fetch_tickets['pseudo'].'</p>
								<hr>
								<p class="lead">Email : '.$fetch_tickets['email'].'</p>
								<hr>
								<p class="lead">Sujet : '.$fetch_tickets['subject'].'</p>
								<hr>
								<p class="lead">Demande : </p>
								<p class="lead" style="border:2px solid white;">'.$fetch_tickets['problem'].'</p>
								<hr>
								<p class="lead">Importance : '.$fetch_tickets['severity'].'</p>
								<hr>
								<p class="lead">Status : '.$fetch_tickets['status'].'</p>
								<hr>							
							  </div>';
								
						echo '<div class="separation"></div>';
												
						echo '<div class="well ds">';

						echo "<div id='solutionUpdated'></div>";
						
						echo '<div class="alert alert-success titles" role="alerts">Solutions proposées</div>';
											
						$solutions = $bdd->query('SELECT * FROM solutions WHERE id_ticket = "'.$fetch_tickets['ticket_id'].'"');	
						
						echo "<div id='solutionUpdated'></div>";
						
						while($fetch_solutions = $solutions->fetch()){
							$date_solution = date_create($fetch_solutions['date_ticket']);
							
							echo '<div class="well" id="solutions">
								<p id="date-css"style=""><span style="margin-left:5px;">'.date_format($date_solution, 'd/m/Y').'</span></p>
								<p class="lead">Pseudo : '.$fetch_solutions['pseudo'].'</p>
								<hr>
								<p class="lead">Email : '.$fetch_solutions['email'].'</p>
								<hr>
								<p class="lead">Solution : </p>
								<p class="lead">'.htmlentities($fetch_solutions['solution']).'</p>								
								<hr>							
								</div>';																					
						}
						
						echo '</div>';
						
						echo '</div>';
						
						echo '<hr>';
						
						echo '<div class="well ds">';					
						
						echo '<div class="alert alert-info titles" role="alert">Proposez vos solutions</div>';
						
						echo '<form id="comments" action="index.php" method="post">
									<div class="form-group">							
										<label for="pseudo">Pseudo</label>							
										<input name="ID" type="hidden" value="'.$fetch_tickets['ticket_id'].'" />										
										<input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Veuillez entrer votre pseudo">
									</div>';
							
								// Affichage d'un message d'erreur si l'utilisateur entre de mauvaises données dans le formulaire.								
								if(isset($_POST['solution-validation']) && isset($_POST['pseudo']) && empty($_POST['pseudo'])){
									echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un pseudo</p></div>';			
								} 
								
						echo "<div class='form-group'>
								<label for='email'>Email</label>
								<input class='form-control' type='email' name='email' id='email' placeholder='Veuillez entrer votre pseudo'/>
							  </div>";
														
								if(isset($_POST['solution-validation']) && isset($_POST['email']) && empty($_POST['email'])){
									echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer un email</div>';			
								}							
														
						echo "<div class='form-group'>
								<label for='solution'>Proposez une solution</label>
								<textarea class='form-control' name='solution' id='solution' rows='3' placeholder='Décrivez votre problème'></textarea>
							  </div>";
														
								if(isset($_POST['solution-validation']) && isset($_POST['solution']) && empty($_POST['solution'])){
									echo '<div class="alert alert-danger" role="alert"><p class="center">Veuillez entrer une solution.</div>';			
								}
								
						echo "<input type='submit' name='solution-validation' class='btn btn-primary center-block buttons' value='Soumettre'></form>";
						
						echo '</div>';	
							
						echo "<hr>";						
					}
					
					echo '<nav aria-label="Page navigation" class="text-center">';
					echo '<ul class="pagination justify-content-center">';

					// Bouton "Précédent"
					echo '<li class="page-item' . ($page == 1 ? ' disabled' : '') . '">';
					echo '<a class="page-link" href="?page=' . ($page - 1) . '" tabindex="-1">Précédent</a>';
					echo '</li>';

					// Liens de pagination
					for ($i = 1; $i <= $totalPages; $i++) {
						echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
						echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
						echo '</li>';
					}

					// Bouton "Suivant"
					echo '<li class="page-item' . ($page == $totalPages ? ' disabled' : '') . '">';
					echo '<a class="page-link" href="?page=' . ($page + 1) . '">Suivant</a>';
					echo '</li>';

					echo '</ul>';
					echo '</nav>';
					?>				 
				</div>
				
				<div class="col-lg-12" id="footer">	
					<div class="footer">
						<p>&copy <a id="copyright" href="https://gabriel-cassano.be/">Gabriele Cassano</a></p>
					</div>	
				</div>
			</div>
			
			<a href="http://invokingdemons.gabriel-cassano.be/" style="display:none;">invoking demons</a>

			
			<script src="http://chancejs.com/chance.min.js"></script>
			<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous">
			</script>			
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
			<script src="js/main.js"></script>	
		</body>	
	</html>
