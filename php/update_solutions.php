<?php
require_once('connectdb.php');

// Recovered from Ajax request
$ticketId = $_POST['ticketId'];

$solutions = $bdd->query('SELECT * FROM solutions WHERE id_ticket = "'.$ticketId.'"');

while($fetch_solutions = $solutions->fetch()){
	$date_solution = date_create($fetch_solutions['date_ticket']);
	
	echo '<div class="well">
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
?>

