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

//Services
use FormBundle\CalculPrix\CalculPrix;

//Form
use FormBundle\FormType\JourType;
use FormBundle\FormType\BilletType;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class FormController extends Controller
{

	public function reservationAction(Request $request) {

		// On récupère la session
		$session = $request->getSession();

		if ($session->get('reservation_id')) { // Si l'utilisateur revient sur la page en cours de reservation
			$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));
		} else {
			// Création d'une réservation
			$reservation = new Reservation();
		}		

		// Création du formulaire
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $reservation);

		// Création des champs du formulaire
		$formBuilder
			->add('jour',								JourType::class)
			->add('nom_reservation',		TextType::class)
			->add('prenom_reservation',	TextType::class)
			->add('email',							EmailType::class)
			->add('submit',							SubmitType::class);


		// On génère le formulaire
		$form = $formBuilder->getForm();

		// Si le formulaire est soumis
		if ($request->isMethod('POST')) {

			// On fait le lien Requete <=> Formulaire, la variable $reservation contient les valeurs entrées dans le formulaire
			$form->handleRequest($request);

			// Si les données sont correctes
			if ($form->isValid()) { 

				$jour = $this->getDoctrine()->getManager()->getRepository('FormBundle:Jour')->findOneBy(array('jour' => $reservation->getJour()->getJour()));

				if ($jour != null) {
					$reservation->setJour($jour);				
				} 

				$em = $this->getDoctrine()->getManager();
				$em->persist($reservation);
				$em->flush();

				$session->set('reservation_id', $reservation->getId());

				return $this->redirectToRoute('info_billet');
			} else {
				return $this->render('FormBundle::info_reservation.html.twig', array('form' => $form->createView()));
			}

		// Si on arrive sur la page pour la première fois
		} else {
			return $this->render('FormBundle::info_reservation.html.twig', array('form' => $form->createView()));
		}

	} //End function reservationAction()





	public function billetAction(Request $request) {

		// Récupération de la session
		$session = $request->getSession();

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		// Création du formulaire
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $reservation);

		// Création des champs du formulaire
		$formBuilder
			->add('billets', CollectionType::class, array(
					'entry_type'		=> BilletType::class,
					'allow_add'			=> true,
					'allow_delete'	=> true))
			->add('submit',	 SubmitType::class);

		// On génère le formulaire
		$form = $formBuilder->getForm();
		
		if ($request->isMethod('POST')) {

			// On fait le lien Requete <=> Formulaire, la variable $reservation contient les valeurs entrées dans le formulaire
			$form->handleRequest($request);

			// Si les données sont correctes
			if ($form->isValid()) { 

				$em = $this->getDoctrine()->getManager();

				foreach ($reservation->getBillets() as $billet) {
					$billet->setReservation($reservation);
					$billet->setJour($reservation->getJour());
				}
				
				$em->persist($reservation);

				$em->flush();

				return $this->redirectToRoute('info_recapitulatif');
			}

		} else {

			return $this->render('FormBundle::info_billet.html.twig', array('form' => $form->createView(), 'reservation' => $reservation));

		}

	} //End function billetAction()





	public function recapitulatifAction(Request $request) {

		// Recuperation de l'entite manager
		$em = $this->getDoctrine()->getManager();

		// Récupération de la session
		$session = $request->getSession();

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));
		$billets_reservation = $reservation->getBillets();
		
		// Recuperation du service de calcul du prix des billets
		$calculPrix = $this->container->get('form.calculPrix');
		$prixTotal = 0;

		foreach ($billets_reservation as $billet) {
			$prix = $calculPrix->calculPrix($billet);
			$billet->setPrixBillet($prix);
			$prixTotal += $prix;
			$em->persist($billet);
		}
	
		$reservation->setPrixTotal($prixTotal);
		$em->persist($reservation);
		
		$em->flush();

		return $this->render('FormBundle::info_recapitulatif.html.twig', array('reservation' => $reservation));

	} //End function billetAction()




	public function paiementAction(Request $request) {

		// Récupération de la session
		$session = $request->getSession();
		
		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		return $this->render('FormBundle::info_paiement.html.twig', array('reservation' => $reservation));

	} //End function billetAction()










public function clearSessionAction() {

		$this->get('session')->clear();

		return $this->redirectToRoute('info_reservation');

} //End function billetAction()



} //End class FormController