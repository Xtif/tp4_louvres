$(document).ready(function() {

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


  






// Check du nombre de billets restant pour le jour sélectionné
// function checkNbreBillet() {
// 	$.get("check_nombre_billets_restant")
// 	  .done(function(dataServeur) {
// 			alert('il reste' + dataServeur + 'billets pour cette date');
// 		})
// 		.fail(function() {
// 			alert('Une erreur s\'est produite');
// 		})
// 		.always(function() {
// 			alert('Requete Ajax terminée !');
// 		});
// }



	//Pour debugage
	$("body").click(function() {
		
	});

}); //End document ready

