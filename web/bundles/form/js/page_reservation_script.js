$(document).ready(function() {

  function checkBilletsRestant(dateSelectionnee) { // On check dans la BDD le nbre de billets restant
    // Affichage du gif de chargement et envoi requête AJAX
    $('.ajax-loading').css('display', 'inline');
    $('.div-ajax-loading').css('display', 'inline');

    $.ajax({
      type: "GET",
      url: urlCheckBilletRestant,
      data: { "date": dateSelectionnee },
      dataType: "json",
      success: function(data) {
        if (data.nbreBilletsRestant > 0) {  
          $("#bouton-valider").removeAttr('disabled').removeClass('bg-secondary').addClass('bg-info');
          $("#messageBilletsRestant").css('color', 'green');
          $("#messageBilletsRestant").html("Il reste " + data.nbreBilletsRestant + " billet(s) disponible(s) pour cette date.");
        } else {
          $("#bouton-valider").attr('disabled', 'disabled').removeClass('bg-info').addClass('bg-secondary');
          $("#messageBilletsRestant").css('color', 'red');
          $("#messageBilletsRestant").html("Nous sommes désolé, il n'y a plus de billets pour cette date.");
        }
        $("#messageBilletsRestant").css('display', 'block');
        $('.ajax-loading').css('display', 'none');
        $('.div-ajax-loading').css('display', 'none');
      },
      error: function() {
        $("#messageBilletsRestant").html("Une erreur s'est produite, nous ne pouvons pas vous donner le nombre de billets restant pour cette date.");
      },
      // complete: function() {
      //  console.log("terminée");
      // }
    });
  }

	//Datepicker choix du jour de visite
	$(".datepicker").datepicker({
		autoclose: true,
		language: 'fr',
		startDate: "today",
		todayHighlight: true,
		datesDisabled: [
			'02/04/2018', 
			'10/05/2018', 
			'21/05/2018', 
			'22/04/2019', 
			'30/05/2019', 
			'10/06/2019'],
		beforeShowDay: function(date) {
      var month = date.getMonth()+1; // +1 parceque JS commence à 0
      var day = date.getDate();
      if (month == 1 && day == 1) {
      	return false;
      } else if (month == 5 && day == 1) {
      	return false;
      } else if (month == 5 && day == 8) {
      	return false;
      } else if (month == 7 && day == 14) {
      	return false;
      } else if (month == 8 && day == 15) {
      	return false;
      } else if (month == 11 && day == 1) {
      	return false;
      } else if (month == 11 && day == 11) {
      	return false;
      } else if (month == 12 && day == 25) {
      	return false;
      } else {
      	return true;
      }
    },
		daysOfWeekDisabled: '20'
  });

  if ($(".datepicker").val() !== "") { //Lorsqu'on arrive sur la page et qu'une date est deja renseigné
    var dateSelectionnee = $(".datepicker").val();
    checkBilletsRestant(dateSelectionnee);
  }

  $(".datepicker").on('changeDate', function() { // Lorsque la date change
    var dateSelectionnee = $(this).val();
    checkBilletsRestant(dateSelectionnee);
  });

}); //End document ready