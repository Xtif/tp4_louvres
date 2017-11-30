// Renvoie le nbre de billets restant pour la date delectionnée depuis la BDD
function supprimeBillet(billet_id, event) { 
	event.preventDefault();
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
				$("#prix_total").html(data.prix_total);
				var i = 1;
				$(".numero-billet").each(function() {
					$(this).html(i);
					i++;
				});
        $('.ajax-loading').css('display', 'none');
        $('.div-ajax-loading').css('display', 'none');
			},
			error: function() {
				console.log('Une erreur s\'est produite !');
			},
			// complete: function() {
			// 	console.log("terminée");
			// }
		});
	}
}


$(document).ready(function() {

	// Checkout stripe
	var handler = StripeCheckout.configure({
		key: 'pk_test_35QqHcMprr2SPQiJjBpatGm6',
		image: '../../web/images/paris_louvre.png',
		locale: 'auto',
		token: function(token) {
			$.ajax({
				type: "GET",
				url: utlVerificationPaiement,
				data: { "token_id": token.id },
				dataType: "json",
				async: true,
				success: function(data) {				
					$(location).attr('href', urlValidationPaiement);
				},
				error: function() {
					console.log('Une erreur s\'est produite !');
				},
				// complete: function() {
				// 	console.log("terminée");
				// }
			}); //End Ajax
		}, //End token: function()
		allowRememberMe: false,
		email: reservationEmail
	}); //End handler function()


	// Click sur "Payer"
	$('#bouton-payer').click(function(e) {
		if (reservationPrixTotal > 0) { // Si une somme est à payer
			var prix = reservationPrixTotal * 100;

			// Open Checkout with further options:
			handler.open({
			  name: 'Musée du Louvres',
			  description: 'Informations de paiement',
			  zipCode: true,
			  currency: 'eur',
			  amount: prix
			});
			e.preventDefault();
		} else { // Si l'ensemble est gratuit
			$(location).attr('href', urlValidationPaiement);
		}
	}); // End function payer()


}); //End document ready