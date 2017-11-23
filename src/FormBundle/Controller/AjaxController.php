<?php

namespace FormBundle\Controller;

//Général
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

//Entité
use FormBundle\Entity\Billet;
use FormBundle\Entity\Jour;
use FormBundle\Entity\Reservation;

class AjaxController extends Controller
{

	public function nbreBilletsRestantAction(Request $request) {

		$var = $request->get('date');
		$varjson = json_encode($var);
		
		// $test = new \Datetime("2017-12-05");
		// $test->setDate(2017, 12, 05);
		// $testJson = json_encode($test);
		$jour = $this->getDoctrine()->getManager()->getRepository('FormBundle:Jour')->findOneBy(array('id' => '20'));
		$jourjson = json_encode($jour);

		return new JsonResponse(array('data' => $jourjson));
		// return new Response($var);

	} //End function nbreBilletsRestantAction()


} //End class FormController