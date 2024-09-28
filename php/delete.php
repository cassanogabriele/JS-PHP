<?php
require_once('connectdb.php');

if (isset($_POST['issueData'])) {
    // Désérialisation du tableau créé en JavaScript, contenant toutes les données du formulaire.
    $obj = json_decode($_POST['issueData'], true);

    // Création des variables du formulaire.
    $solutionId = $obj['id'];
	$solutionId = intval($solutionId);
    $method = $obj['method'];	
	$state = $obj['state'];	
	$newStatus = ($state == 'Ouvert') ? 'Ferme' : 'Ouvert';
	
	if ($method == 'supprimer') {
        $deleteQuery = $bdd->prepare('DELETE FROM tickets WHERE id = :id');
		$deleteQuery->bindParam(':id', $solutionId);
		$deleteQuery->execute();		

		if ($deleteQuery) {
			echo "<div class='alert alert-success text-center' role='alert'>Le ticket a été supprimé.</div>";	
		} else {
			echo "<div class='alert alert-danger text-center' role='alert'>Le ticket n'a pas été supprimé.</div>";
		}   
    } else{
		$updateQuery = $bdd->prepare('UPDATE tickets SET status = :status WHERE id = :id');
		$updateQuery->bindParam(':status', $newStatus);
		$updateQuery->bindParam(':id', $solutionId);
		$updateQuery->execute();
		
		if ($updateQuery) {
			echo "<div class='alert alert-success text-center' role='alert'>Le status du ticket a été mis à jour.</div>";	
		} else {
			echo "<div class='alert alert-danger text-center' role='alert'>Le ticket n'a pas mis à jour.</div>";
		}
	}    
}
?>

