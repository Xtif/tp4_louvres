<?php

namespace FormBundle\CodeReservation;

class CodeReservation
{
  //Création d'un code aléatoire à 5 charactères
  public function codeReservation() {

    $elements = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 
    $code = str_shuffle($elements); // Mélange des caractères
    $code = substr($code, 31); // Garde unqiuement les 5 derniers caractères

    return $code; // Retour du code
			  	
  } // End fonction codeReservation

} // End class