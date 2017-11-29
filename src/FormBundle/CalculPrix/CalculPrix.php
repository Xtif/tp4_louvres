<?php

namespace FormBundle\CalculPrix;

class CalculPrix 
{

  public function calculPrix(\FormBundle\Entity\Billet $billet) {

  	// Recuperation de reservation
  	$dateReservation = $billet->getJour()->getJour();

  	// Recuperation de la naissance
	  $dateNaissance = $billet->getDateNaissance();

	  // Calcul de l'age au moment de la visite
	  $diff = $dateReservation->diff($dateNaissance);
	  $age = $diff->y;

	  // Calcul du prix en fonction de l'age
	  if ($age < 4) {
	  	$prix = 0;
	  } else if ($age > 4 && $age < 12) {
	  	$prix = 8;
	  } else if ($billet->getTarifReduit()) {
	  	$prix = 10;
  	} else if ($age > 60) {
  		$prix = 12;
  	} else {
  		$prix = 16;
  	}

  	// Prix si demi-journée
  	if ($billet->getType() == 0) {
  		$prix = $prix/2;
  	}

  	// Attribution à l'objet
  	return $prix;
			  	
  } // End fonction CalculPrix

} // End class