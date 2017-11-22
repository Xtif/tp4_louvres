<?php

namespace FormBundle\Controller;

//Général
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Entité
use FormBundle\Entity\Billet;
use FormBundle\Entity\Jour;
use FormBundle\Entity\Reservation;

class AjaxController extends Controller
{

	public function nbreBilletsRestantAction(Request $request) {

		$var = $request->get('date');
		die(var_dump($var));
		// return $var;	

	} //End function nbreBilletsRestantAction()


} //End class FormController