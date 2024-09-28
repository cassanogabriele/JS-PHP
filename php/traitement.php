<?php
require_once('connectdb.php');

// Si le formulaire a été soumis
if(isset($_POST['myData'])){
	// Désérialisation du tableau créé en JavaScript, contenant toutes les données du formulaire.
	$obj = json_decode($_POST['myData'], true);
	
	// Création des variables du formulaire.
	$ticketId = $obj['id'];
	$pseudo = $obj['pseudo'];	
	$email = $obj['email'];	
	$subject = $obj['subject'];	
	$solution = $obj['solution'];	
	$problem = $obj['description'];	
	$severity = $obj['severity'];	
	$typeForm = $obj['typeForm'];
	$solutionTicketId = $obj['solutionTicketId'];	
	$status = $obj['status'];  
}

$select_tickets = $bdd->query("SELECT * FROM tickets WHERE pseudo='".$pseudo."' AND subject='".$subject."' AND problem='".$problem."'");
$existing_tickets = $select_tickets->fetch();

if($existing_tickets != 0){	
	echo '<div class="alert alert-danger" role="alert" id="error-msg">';
	echo ucfirst($pseudo). ", vous avez déjà envoyé cette demande.";
	echo '</div>';	
} else{	
	if (filter_var($pseudo, FILTER_VALIDATE_EMAIL)) {
		echo "<div class='alert alert-danger text-center errors-data' role='alert' data-nosubmit='true'>L'adresse e-mail n'est pas autorisée pour le pseudo.</div>";
		$noSubmit = true;
	}else if ($email == 'noreply@inetdomainportal.com') {
		echo "<div class='alert alert-danger text-center errors-data'' role='alert'>Cette adresse e-mail n'est pas autorisée pour le pseudo.</div>";
		$noSubmit = true;
	} else if (filter_var($subjecto, FILTER_VALIDATE_EMAIL)) {
		echo "<div class='alert alert-danger text-center errors-data'' role='alert'>L'adresse e-mail n'est pas autorisée pour le pseudo.</div>";
		$noSubmit = true;
	} else if (filter_var($problem, FILTER_VALIDATE_EMAIL)) {
		echo "<div class='alert alert-danger text-center errors-data'' role='alert'>L'adresse e-mail n'est pas autorisée pour le pseudo.</div>";
		$noSubmit = true;
	} else {
		// Vérification du contenu du message
		$forbiddenPatterns = array(
			'/TO RENEW/i',
			'/https?:\/\/\S+/i', // Correspond à n'importe quelle URL commençant par http:// ou https://
			'/\bLegal Disclaimer:/i' // Correspond au début de la phrase "Legal Disclaimer:"
			// Ajoutez d'autres motifs interdits au besoin
		);

		$isForbidden = false;
		
		foreach ($forbiddenPatterns as $pattern) {
			if (preg_match($pattern, $problem)) {
				$isForbidden = true;
				break;
			}
		}

		if ($isForbidden) {
			echo "<div class='alert alert-danger text-center' role='alert'>Le contenu du message n'est pas autorisé.</div>";
			$noSubmit = true;
		}
	}
}

if(!$noSubmit) {	
	if($typeForm == 'probleme'){
		if (!empty($pseudo) && !empty($email) && !empty($subject) && !empty($problem) && !empty($severity)) {
			$insert_tickets = $bdd->prepare('INSERT INTO tickets(ticket_id, pseudo, email, subject, problem, severity, status, date_ticket) VALUES(:ticket_id, :pseudo, :email, :subject, :problem, :severity, :status, NOW())')or die('Erreur SQL !'.mysql_error());

			$insert_tickets->execute(array(
				'ticket_id' => $ticketId,
				'pseudo' => $pseudo,
				'email' => $email,
				'subject' => $subject,
				'problem' => $problem,
				'severity' => $severity,
				'status' => $status				
			));
		}
	
		if($insert_tickets->rowCount() > 0){
			echo "<div class='alert alert-success text-center errors' role='alert'>Le ticket à été ajouté.</div>";		
		} else {
			echo "<div class='alert alert-danger text-center errors' role='alert'>Le ticket n'a pas été ajouté.</div>";		
		}
	} else{ 
		if (!empty($pseudo) && !empty($email) && !empty($solution)) {
			$insert_solutions = $bdd->prepare('INSERT INTO solutions(pseudo, email, solution, id_ticket) VALUES(:pseudo, :email, :solution, :id_ticket)')or die('Erreur SQL !'.mysql_error());

			$insert_solutions->execute(array(						
				'pseudo' => $pseudo,
				'email' => $email,
				'solution' => $solution, 	
				'id_ticket' => $solutionTicketId
			));		
			
			if($insert_solutions->rowCount() > 0){
				echo "<div class='alert alert-success text-center errors' role='alert'>La solution à été ajoutée.</div>";		
			} else {
				echo "<div class='alert alert-danger text-center errors' role='alert'>La solution n'a pas été ajoutée.</div>";		
			}	
		}		
	}	
}
?>
