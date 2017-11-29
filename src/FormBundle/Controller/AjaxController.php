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

		$dateChoisie = $request->get('date');
		$dateChoisieString = (string) $dateChoisie;

		$dateChoisieExplode = explode("/", $dateChoisie);

		$jour = $dateChoisieExplode[0];
		$mois = $dateChoisieExplode[1];
		$annee = $dateChoisieExplode[2];

		$dateTimeChoisie = new \Datetime($annee ."-" . $mois . "-" . $jour);

		$jour = $this->getDoctrine()->getManager()->getRepository('FormBundle:Jour')->findOneBy(array('jour' => $dateTimeChoisie));
		if ($jour) {
			$nbreBilletsVendus = $jour->getBillets()->count();
			$nbreBilletsRestant = 1000 - $nbreBilletsVendus;
		} else {
			$nbreBilletsRestant = 1000;
		}

		return new JsonResponse(array('nbreBilletsRestant' => $nbreBilletsRestant));

	} //End function nbreBilletsRestantAction()



	public function supprimeBilletAction(Request $request) {

		// Récupération de la session et de l'entity manager
		$session = $request->getSession();
		$em = $this->getDoctrine()->getManager();

		// Récupération du billet à supprimer
		$billet_id = $request->get('billet_id');
		$billet = $em->getRepository('FormBundle:Billet')->findOneBy(array('id' => $billet_id));

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		$nouveauPrix = $reservation->getPrixTotal() - $billet->getPrixBillet();
		$reservation->setPrixTotal($nouveauPrix);

		$em->remove($billet);
		$em->flush();

		return new JsonResponse(array(
			'billet_id' => $billet_id, 
			'prix_total' => $nouveauPrix
		));

	} //End function nbreBilletsRestantAction()




	public function idBilletAction(Request $request) {

		$em = $this->getDoctrine()->getManager();

		$index_billet = $request->get('index');
		$reservation_id = $request->get('reservation_id');


		$reservation = $em->getRepository('FormBundle:Reservation')->findOneBy(array('id' => $reservation_id));
		$billets = $reservation->getBillets();
		$result = $billets[$index_billet]->getId();

		return new JsonResponse(array('result' => $result));

	} //End function nbreBilletsRestantAction()



	public function verificationPaiementAction(Request $request) {

		// Récupération de la session
		$session = $request->getSession();
		
		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		// Prix total
		$prix = $reservation->getPrixTotal()*100;

		// Appel du service de paiement
		$paiement = $this->container->get('form.paiement');
		$charge = $paiement->paiement($request, $prix);

		return new JsonResponse(array('result' => $charge));
	}




} //End class FormController