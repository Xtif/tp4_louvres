$(document).ready(function() {

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
			if (heure > 14) { 
				$("[id$=_type_1]")[0].disabled = true;
				$("[id$=_type_1]").parent().css("color", "grey").css("opacity", 0.5);
				$("#billet-indisponible").attr("hidden", false);
			}
		}
	}



	// Affichage ou non du message de tarif réduit lorsque des billets sont déjà enregistrés
	function checkTarifReduit() {
		$("[type=checkbox]").each(function() {
			if ($(this).is(':checked')) {
		  	$(this).parent().parent().children("p").show();
		  } else {
		  	$(this).parent().parent().children("p").hide();
		  }
		})
	}



	/*************Gestion de l'ajout de nouveaux billets**************/
		var $container = $('div#form_billets'); // Recuperation de la div ou sont insérer les billets

		var index = $container.children().length; // Nombre de billets de la reservation

		var $separation = $('<hr>'); // On crée la barre de séparation entre billet

	  if (index == 0) { // Si il n'existe pas de billet, on affiche un formulaire quand meme
	    ajoutBillet($container);
	  } else {
	    // S'il existe déjà des billets, on ajoute un lien de suppression et le message du tarif reduit pour chacun d'entre eux
	    $container.children('div').each(function(i) { // Pour chaque formulaire de billet
	    	$(this).children('label').html('Billet n°' + (i+1)); // On modifie le titre
	    
	      var $message = $("<p class='tarif-reduit' id='tarif-reduit-" + index + "'>Un justificatif vous sera demandé pour accéder au musée.</p>"); // On crée le message pour le tarif réduit
	    	$(this).find("[class=checkbox]").append($message); // On insere le message apres la checkbox de chaque billet	    	

	    	if (i != 0) { // On ne met pas de lien de suppression sur le premier billet
	      	ajoutLienSuppression($(this)); // On met un lien de suppression sur tous les autres billets
	      }
	    });
	  }

	  checkTarifReduit(); // Verifie si le tarif est reduit sur les billets existants
	  
	  // On ajoute un nouveau formulaire à chaque clic sur le lien d'ajout de billet
	  $('#ajout_billet').click(function(e) {
	    ajoutBillet($container);
	    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
	    return false;
	  });

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
	      ajoutLienSuppression($prototype); // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
	    }

	    // console.log($prototype.last().last());
	    // $lastChild = ($prototype + ":last-child");
	    $prototype.find("[class=checkbox]").append($message); // On ajoute le message à la fin de la <div> du nouveau billet

	    $container.append($prototype); // On ajoute le prototype modifié à la fin de la balise <div>

	    checkDemiJournee(); // Desactive le billet journée si plus de 14h	    

	    //Datepicker choix date de naissance
			$(".date-naissance").datepicker({
				autoclose: true,
				language: 'fr',
				defaultViewDate: "01/01/1980",
				todayHighlight: false
		  });

	    index++; // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
	  }

	  // La fonction qui ajoute un lien de suppression du billet
	  function ajoutLienSuppression($prototype) {
	    var $lienSupression = $('<a href="#" class="float-right btn btn-danger">Supprimer ce billet</a>'); // Création du lien
	    var $separation = $('<hr>'); // On crée la barre de séparation entre billet

	    $prototype.prepend($lienSupression); // Ajout du lien
	    $prototype.prepend($separation); // On ajoute la barre de separation au début de la balise <div> du nouveau billet

	    // Ajout du listener sur le clic du lien pour effectivement supprimer le billet
	    $lienSupression.click(function(e) {
	      $prototype.remove();
				e.preventDefault(); // évite qu'un # apparaisse dans l'URL

				// Gestion du numero des billets lors d'une suppression
				$('label:contains(Billet n)').each(function(num) {
					var numero = num + 1; // Js commence à 0
					$(this).empty();
					$(this).text("Billet n°" + numero);
				});

				index--; // On décremente pour que le nouveau billet crée ait un numéro à la suite des autres
	      return false;
	    });
	  }
	/*************Fin gestion de l'ajout de nouveaux billets**************/

}); //End document ready