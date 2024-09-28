document.addEventListener("DOMContentLoaded", function() {
  var alerts = document.querySelectorAll(".errors");
  var alertContainer = document.getElementById("alert-container");

  alerts.forEach(function(alert) {
    alertContainer.appendChild(alert);
  });
});	

// Remonter en haut de la page
function scrollToTop() {
window.scrollTo({
  top: 0,
  behavior: 'smooth' // Optionnel pour un défilement fluide
});
}

// Appeler la fonction pour remonter en haut de la page au clic sur le bouton de soumission
var submitButton = document.querySelector("input[name='solution-validation']");
	submitButton.addEventListener("click", function(event) {
	scrollToTop();
});


// Récupération du formulaire et on lui attache l'élément <button>, lorsque l'utilisateur clique sur le bouton, on soumet le formulaire.
var issueInputForm = document.getElementById('issueInputForm').addEventListener('submit', saveIssue);
var commentsInputForm = document.getElementById('comments').addEventListener('submit', saveIssue);


// Fonction qui permet de stocker les problèmes soumis.
function saveIssue(e){
	// Détecter quel type de formulaire on soumet : un problème ou une solution 
	var typeForm;
    var formElement = document.getElementById('issueInputForm');
    
    // Vérifie si l'événement submit est déclenché depuis le bouton "Soumettre"
    if (e.submitter && e.submitter.name === 'valider') {
        typeForm = 'probleme';
		
		// On récupère tous les champs de formulaire. 
		var issuePseudo = document.getElementById('issuePseudo').value;
		var issueEmail = document.getElementById('issueEmail').value;
		var issueSubject = document.getElementById('issueSubject').value;
		var issueDesc = document.getElementById('issueDescInput').value;
		var issueSeverity = document.getElementById('issueSeverityInput').value;
    } else{
		typeForm = 'solution';
		
		// On récupère tous les champs de formulaire. 
		var issuePseudo = document.getElementById('pseudo').value;
		var issueEmail = document.getElementById('email').value;		
		var solution = document.getElementById('solution').value;		
		
		var randomId = document.getElementsByName('ID');
		
		if (randomId.length > 0) {
		  var solutionTicketId = randomId [0].value;
		}	
	}	
	
	// Utilsation de "chance" qui est un générateur de chaînes aléatoires, de nombres, etc, destiné à réduire la monotonie.
	
	// On crée un id aléatoire pour le "post". chance.guid() : retourne un guide aléatoire.
	var issueId = chance.guid();
	
	// On définit le status du "post"
	var issueStatus = 'Ouvert';	   	
		
	// Création d'un objet issue.
	var issue = {
		// On définit les propriétés de l'objet
		id: issueId,
		pseudo: issuePseudo,
		email: issueEmail,
		solution: solution,
		subject : issueSubject,
		description: issueDesc,
		severity: issueSeverity,	
		typeForm : typeForm,
		solutionTicketId: solutionTicketId,
		status: issueStatus,		
	}	
	
	var myDatastr = JSON.stringify(issue);	
		
	// localStorage permet d'accéder à un objet local Storage, les données stockées dans le localStorage n'ont pas de délai d'expiration.	
	if(localStorage.getItem('issues') == null){
		// On crée un tableau "issues" (problèmes)
		var issues = [];
		// On ajoute les éléments au tableau, au clic sur le bouton "add", qui apparaîtront en-dessous du formulaire de suggestion des problèmes.
		issues.push(issue);
		// JSON.stringify() : convertir un objet JavaScript en chaîne.		
		localStorage.setItem('issues', JSON.stringify(issues));
	} else{
		// Sinon on analyse les données et elles deviennent un objet JavaScript.
		var issues = JSON.parse(localStorage.getItem('issues'));
		issues.push(issue);
		localStorage.setItem('issues', JSON.stringify(issues));
	}
	
	// On récupère le formulaire et dans cette fonction, on permet de le remettre à "0".
	document.getElementById('issueInputForm').reset();
	
	// On annule l'événement si il est annulable.
	e.preventDefault();
	
	$.ajax({
            url: "php/traitement.php",
            method: "post",
            data: {myData:myDatastr},
			dataType : 'html',		
            success: function(strMessage) {
                $("#message").html(strMessage);
				
				e.preventDefault();
				
				if(typeForm == 'probleme'){
					$.ajax({
						url: "php/update_tickets.php", // Remplacez par le chemin vers votre fichier PHP pour la mise à jour des tickets
						method: "post",
						dataType: 'html',
						success: function (response) {
							// Cacher la div						
							$('#issuesList').hide();
							// Afficher la div avec les données mises à jour
							$("#issuesListUpdated").html(response);
						},
						error: function (error) {
							console.log(error);
						}
					});
				} else{					
					$.ajax({
						url: "php/update_solutions.php", // Remplacez par le chemin vers votre fichier PHP pour la mise à jour des tickets
						method: "post",
						data: { ticketId: solutionTicketId },
						dataType: 'html',
						success: function (response) {
							// Cacher la div						
							$('#solutions').hide();
							// Afficher la div avec les données mises à jour
							$("#solutionUpdated").html(response);
						},
						error: function (error) {
							console.log(error);
						}
					});
				}
			},
            error: function(strMessage) {
				if((issuePseudo == "") || (issueEmail == '') || (issueSubject == '') ||(issueDesc == '') || (issueSeverity == '')){
					 $("#message").html("<div class='alert alert-danger text-center errors' role='alert'>Veuillez compléter le formulaire</div>");
				} else{
					$("#message").text(strMessage);
					console.log(strMessage);
					// $("#issueInputForm")[0].reset();    
				}                          
            }
    });
}

// Fonction qui permet de changer le status du problème soumis et le mettre en status "fermé".
function setStatusClosed(id, state){	
	var issue = {
		id: id,
		method: 'update',
		state: state,
	};
	
	var issueStr = JSON.stringify(issue);
	
	if (confirm('Êtes-vous sûr de vouloir modifier le statut de ce sujet ?')) {
		$.ajax({
			url: "php/delete.php",
			method: "post",
			data: { issueData: issueStr },
			dataType: 'html',
			success: function (strMessage) {
				$("#message").html(strMessage);
				
				$.ajax({
					url: "php/update_tickets.php", // Remplacez par le chemin vers votre fichier PHP pour la mise à jour des tickets
					method: "post",
					dataType: 'html',
					success: function (response) {
						// Cacher la div						
						$('#issuesList').hide();
						// Afficher la div avec les données mises à jour
						$("#issuesListUpdate").html(response);
					},
					error: function (error) {
						console.log(error);
					}
				});
			},
			error: function (xhr, status, error) {				
				$("#message").text("Erreur lors de la mise à jour du status du ticket.");
			}
		});
	}
}

// Fonction qui permet de supprimer un problème posté.
function deleteIssue(id, method) {	
	var issue = {
		id: id,
		method: 'supprimer',
	};
	
	var issueStr = JSON.stringify(issue);
	
	if (confirm('Êtes-vous sûr de vouloir supprimer ce sujet ?')) {
		$.ajax({
			url: "php/delete.php",
			method: "post",
			data: { issueData: issueStr },
			dataType: 'html',
			success: function (strMessage) {
				$("#message").html(strMessage);
				
				$.ajax({
					url: "php/update_tickets.php", // Remplacez par le chemin vers votre fichier PHP pour la mise à jour des tickets
					method: "post",
					dataType: 'html',
					success: function (response) {
						// Cacher la div						
						$('#issuesList').hide();
						// Afficher la div avec les données mises à jour
						$("#issuesListUpdate").html(response);
					},
					error: function (error) {
						console.log(error);
					}
				});
			},
			error: function (xhr, status, error) {				
				$("#message").text("Erreur lors de la suppression du ticke.");
			}
		});
	}
}

