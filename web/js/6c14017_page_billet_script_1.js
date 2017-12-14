// Affichage du gif de chargement et envoi requête AJAX
$('.ajax-loading').css('display', 'inline');
$('.div-ajax-loading').css('display', 'inline');

// Affiche/desaffiche le message du tarif réduit
function tarifReduitToggle(el) {
	$(el).parent().parent().children("p").toggle();
} // Fin tarifReduitToggle()

// Renvoie le nbre de billets restant pour la date delectionnée depuis la BDD
function supprimeBillet(billet_id) { 
	if (billet_id != null) {
	// Affichage du gif de chargement et envoi requête AJAX
    $('.ajax-loading').css('display', 'inline');
    $('.div-ajax-loading').css('display', 'inline');

		$.ajax({
			type: "GET",
			url: urlSupprimerBillet,
			data: { "billet_id": billet_id },
			dataType: "json",
			async: true,
			success: function(data) {				
				$("#" + data.billet_id).remove();
				$('.ajax-loading').css('display', 'none');
    		$('.div-ajax-loading').css('display', 'none');
			},
			error: function() {
				console.log('Une erreur s\'est produite dans la fonction supprimeBillet !');
			},
			// complete: function() {
			// 	console.log("terminée");
			// }
		});
	}
}
	
$(document).ready(function() {

	var nbreBilletsRestant = checkBilletsRestant($('#date-visite').html());

	function idBillet(index) { 
		return $.ajax({
			type: "GET",
			url: urlIdBillet,
			data: { "index": index , "reservation_id" : reservationId },
			dataType: "json",
			async: false,
			success: function(data) {				
				$('.ajax-loading').css('display', 'none');
	    	$('.div-ajax-loading').css('display', 'none');
	    	return data.result;
			},
			error: function() {
				console.log('Une erreur s\'est produite dans la fonction idbillet!');
			},
			// complete: function() {
			// 	console.log("terminée");
			// }
		});
	}

	/*************Gestion de l'ajout de nouveaux billets**************/
	var $container = $('div#form_billets'); // Recuperation de la div ou sont insérer les billets
	var index = $container.children().length; // Nombre de billets de la reservation
	var $separation = $('<hr>'); // On crée la barre de séparation entre billet


	if (index == 0) { // Si il n'existe pas de billet, on affiche un formulaire quand meme
	  ajoutBillet($container);
	} else {
	  // S'il existe déjà des billets, ajoute un lien de suppression et le message du tarif reduit pour chacun d'entre eux
	  $container.children('div').each(function(i) { // Pour chaque formulaire de billet
	  	$(this).children('label').html('Billet n°' + (i+1)); // Modification du titre
	    var $message = $("<p class='tarif-reduit' id='tarif-reduit-" + index + "'>Un justificatif vous sera demandé pour accéder au musée.</p>"); // Création du message pour le tarif réduit
	  	$(this).find("[class=checkbox]").append($message); // On insere le message apres la checkbox de chaque billet	    
	  	if (i != 0) { // On ne met pas de lien de suppression sur le premier billet
	  		$idBillet = idBillet(i).responseJSON.result; // On recupere l'id du billet crée
	  		ajoutLienSuppression($(this), $idBillet); // On ajoute le lien de suppression sur chaque billet 
	    }
	  });
	}

	initialiseDatepicker(); // Intialisation date picker
	checkTarifReduit(); // Verifie si le tarif est reduit sur les billets existants
	checkDemiJournee(); // Desactive le billet journée si plus de 14h	 

	$('.ajax-loading').css('display', 'none');
    $('.div-ajax-loading').css('display', 'none');
		  
	// Ajoute un nouveau formulaire à chaque clic sur le lien d'ajout de billet
	$('#ajout_billet').click(function(e) {
	  ajoutBillet($container);
	  e.preventDefault(); // évite qu'un # apparaisse dans l'URL
	  return false;
	});
	/*************Fin gestion de l'ajout de nouveaux billets**************/

		
	//Desactivation du billet journée apres 14h
	function checkDemiJournee() {
		var present = new Date(); //Recuperation de la date complete
		var jour = present.getDate(); //Recuperation du jour actuel
		var mois = present.getMonth()+1; //Recuperation du mois actuel (js commence à 0)
		var annee = present.getFullYear(); //Recuperation de l'année actuelle
		var presentFormat = jour + '/' + mois + '/' + annee; //Formattage de la date actuelle
		var dateVisite = $("#date-visite").html(); //Recupération de la date de visite
		var heure = present.getHours(); //Recuperation heure actuelle

		if (dateVisite == presentFormat) {
			if (heure > 13) { 
				$("[id$=_type_1]")[0].disabled = true;
				$("[id$=_type_1]").parent().css("color", "grey").css("opacity", 0.5);
				$("#billet-indisponible").attr("hidden", false);
			}
		}
	} // Fin checkDemiJournee()

	// Affichage ou non du message de tarif réduit lorsque des billets sont déjà enregistrés
	function checkTarifReduit() {
		$("[type=checkbox]").each(function() {
			if ($(this).is(':checked')) {
		  	$(this).parent().parent().children("p").show();
		  } else {
		  	$(this).parent().parent().children("p").hide();
		  }
		})
	} // Fin checkTarifReduit()

	// Initialise les datepicker
	function initialiseDatepicker() {
		//Datepicker choix date de naissance
		$(".date-naissance").datepicker({
			autoclose: true,
			language: 'fr',
			todayHighlight: false,
			endDate: 'today'
		 });
	} // Fin initialiseDatepicker()

	// Fonction d'ajout d'un formulaire de billet supplémentaire
	function ajoutBillet($container) {
	  // Dans le contenu de l'attribut « data-prototype », on remplace :
	  // - toutes les occurences (grâce à la regexp /*/g) "__name__label__" qu'il contient par le label du champ
	  // - toutes les occurences (grâce à la regexp /*/g) "__name__" qu'il contient par le numéro du champ
	  var template = $container.attr('data-prototype')
	    .replace(/__name__label__/g, 'Billet n°' + (index+1))
	    .replace(/__name__/g,        index)
	  ;

	  var $prototype = $(template); // On crée un objet jquery qui contient ce template (tout le text/code HTML de "data-prototype")
		   
	  var $message = $("<p class='tarif-reduit' id='tarif-reduit-" + index + "'>Un justificatif vous sera demandé pour accéder au musée.</p>"); // On crée le message pour le tarif réduit

	  if (index != 0) { // On ne met pas de bouton supprimer sur le 1er billet
	    ajoutLienSuppression($prototype, null); // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
	  }

	  $prototype.find("[class=checkbox]").append($message); // On ajoute le message à la fin de la <div> du nouveau billet

	  $container.append($prototype); // On ajoute le prototype modifié à la fin de la balise <div>

	  initialiseDatepicker(); // Intialisation date picker
	  checkDemiJournee(); // Desactive le billet journée si plus de 14h	  
	  checkTarifReduit(); // Verifie si le tarif est reduit  

	  nbreBilletsRestant--;
		billetMax(nbreBilletsRestant);

	  index++; // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
	} // Fin ajoutBillet()

	// La fonction qui ajoute un lien de suppression du billet
	function ajoutLienSuppression($prototype, billet_id) {
	  var $lienSupression = $('<a href="#" class="float-right btn btn-danger" onclick="supprimeBillet(' + billet_id + ')">Supprimer ce billet</a>'); // Création du lien
	  var $separation = $('<hr>'); // On crée la barre de séparation entre billet

	  $prototype.prepend($lienSupression); // Ajout du lien
	  $prototype.prepend($separation); // On ajoute la barre de separation au début de la balise <div> du nouveau billet

	  // Ajout du listener sur le clic du lien pour effectivement supprimer le billet
	  $lienSupression.click(function(e) {
	    $prototype.remove(); // On supprime le formulaire 
			e.preventDefault(); // évite qu'un # apparaisse dans l'URL

			// Gestion du numero des billets lors d'une suppression
			$('label:contains(Billet n)').each(function(num) {
				var numero = num + 1; // Js commence à 0
				$(this).empty();
				$(this).text("Billet n°" + numero);
			});

			nbreBilletsRestant++; // Un billet supplementaire est disponible
			billetMax(nbreBilletsRestant); // On affiche ou pas le message et le bouton "Ajouter"

			index--; // On décremente pour que le nouveau billet crée ait un numéro à la suite des autres
		  return false;
		});
	} // Fin ajoutLienSuppression()

	// Affiche/desaffiche le message du nombre de billets max atteint
	function billetMax(nbreBilletsRestant) {
		if (nbreBilletsRestant < 1) {	
			$("#ajout_billet").attr('disabled', 'disabled').removeClass('bg-success').addClass('bg-secondary');
			$("#messageAjout").html("Vous ne pouvez plus ajouter de billets car la limite de vente a été atteinte.");
		} else {
			$("#ajout_billet").removeAttr('disabled').removeClass('bg-secondary').addClass('bg-success');
			$("#messageAjout").html("");			
		}
	}

	// Renvoie le nbre de billets restant pour la date delectionnée depuis la BDD
	function checkBilletsRestant(dateSelectionnee) { 
		// Affichage du gif de chargement et envoi requête AJAX
    $('.ajax-loading').css('display', 'inline');
    $('.div-ajax-loading').css('display', 'inline');

		$.ajax({
			type: "GET",
			url: urlCheckBilletRestant,
			data: { "date": dateSelectionnee },
			dataType: "json",
			async: true,
			success: function(data) {				
				billetMax(data.nbreBilletsRestant);
				nbreBilletsRestant = data.nbreBilletsRestant;
				$('.ajax-loading').css('display', 'none');
    		$('.div-ajax-loading').css('display', 'none');
			},
			error: function() {
				$("#messageBilletsRestant").html("Une erreur s'est produite, nous ne pouvons pas vous donner le nombre de billets restant pour cette date.");
			},
			// complete: function() {
			// 	console.log("terminée");
			// }
		});
		return nbreBilletsRestant;
	}

}); //End document ready