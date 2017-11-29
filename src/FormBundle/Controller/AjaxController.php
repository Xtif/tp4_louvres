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

	// Renvoie le nombre de billets restant
	public function nbreBilletsRestantAction(Request $request) {

		$dateChoisie = $request->get('date'); // Recuperation de la date de visite
		$dateChoisieString = (string) $dateChoisie; // Transformation en string
		$dateChoisieExplode = explode("/", $dateChoisie); // Decoupage de la date

		// recuperation du jour, mois et année
		$jour = $dateChoisieExplode[0]; 
		$mois = $dateChoisieExplode[1];
		$annee = $dateChoisieExplode[2];

		// création du Datetime au format
		$dateTimeChoisie = new \Datetime($annee ."-" . $mois . "-" . $jour);

		// Recuperation du jour de visite
		$jour = $this->getDoctrine()->getManager()->getRepository('FormBundle:Jour')->findOneBy(array('jour' => $dateTimeChoisie));

		if ($jour) { // Si le jour est présent dans la BDD
			$nbreBilletsVendus = $jour->getBillets()->count(); 
			$nbreBilletsRestant = 1000 - $nbreBilletsVendus; 
		} else { // Si ce jour n'a jamais été reservé
			$nbreBilletsRestant = 1000; 
		}

		return new JsonResponse(array('nbreBilletsRestant' => $nbreBilletsRestant));
	} //End function nbreBilletsRestantAction()


	// Supprime un billet de la BDD
	public function supprimeBilletAction(Request $request) {

		// Récupération de la session et de l'entity manager
		$session = $request->getSession();
		$em = $this->getDoctrine()->getManager();

		// Récupération du billet à supprimer
		$billet_id = $request->get('billet_id');
		$billet = $em->getRepository('FormBundle:Billet')->findOneBy(array('id' => $billet_id));

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		// Calcul du nouveau prix total
		$nouveauPrix = $reservation->getPrixTotal() - $billet->getPrixBillet();
		$reservation->setPrixTotal($nouveauPrix);

		// Suppression du billet
		$em->remove($billet);

		$em->flush();

		return new JsonResponse(array(
			'billet_id' => $billet_id, 
			'prix_total' => $nouveauPrix
		));
	} //End function nbreBilletsRestantAction()


	// Renvoie l'id d'un billet à partir de son index (son ordre dans la BDD)
	public function idBilletAction(Request $request) {

		// Recupeartion de l'entity manager
		$em = $this->getDoctrine()->getManager();

		// Recupération de l'index du billet et del'id de la reservation
		$index_billet = $request->get('index');
		$reservation_id = $request->get('reservation_id');

		// Recupération de la reservation et des billets associés
		$reservation = $em->getRepository('FormBundle:Reservation')->findOneBy(array('id' => $reservation_id));
		$billets = $reservation->getBillets();

		// récupération de l'id du billet
		$result = $billets[$index_billet]->getId();

		return new JsonResponse(array('result' => $result));
	} //End function nbreBilletsRestantAction()


	// Execution et verification du paiement
	public function verificationPaiementAction(Request $request) {

		// Récupération de la session
		$session = $request->getSession();
		
		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		// Mise au format décimal pour le paiement stripe
		$prix = $reservation->getPrixTotal()*100; 

		// Appel du service de paiement
		$paiement = $this->container->get('form.paiement');

		// Execution du paiement
		$charge = $paiement->paiement($request, $prix);

		return new JsonResponse(array('result' => $charge));
	} // End function verificationPaiementAction()


} //End class FormController