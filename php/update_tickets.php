<?php
require_once('connectdb.php');

// Récupération des messages les plus récents
$current_requests = $bdd->query("SELECT * FROM tickets ORDER BY date_ticket DESC LIMIT 3");								

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
?>

