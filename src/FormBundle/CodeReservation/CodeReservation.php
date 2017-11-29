<?php


namespace FormBundle\CodeReservation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CodeReservation
{
  //Création d'un code aléatoire à 5 charactères
  public function codeReservation() {

    $elements = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = str_shuffle($elements);
    $code = substr($code, 31);

    return $code;
			  	
  } // End fonction CalculPrix

} // End class