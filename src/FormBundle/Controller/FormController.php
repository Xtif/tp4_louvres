<?php

namespace FormBundle\Controller;

//Général
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

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

	// Fonction de gestion de la page de reservation
	public function reservationAction(Request $request) {

		// On récupère la session
		$session = $request->getSession();

		if ($session->get('reservation_id')) { // Si l'utilisateur revient sur la page en cours de reservation
			$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));
		} else { // Sinon on crée un reservation vide
			$reservation = new Reservation();
		}		

		// Création du formulaire
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $reservation);

		// Création des champs du formulaire
		$formBuilder
			->add('jour',							JourType::class)
			->add('nom_reservation',				TextType::class)
			->add('prenom_reservation',				TextType::class)
			->add('email',							EmailType::class)
			->add('submit',							SubmitType::class, array('attr' => ['value' => 'Valider']));

		// On génère le formulaire
		$form = $formBuilder->getForm();

		// Si le formulaire est soumis
		if ($request->isMethod('POST')) {

			// On fait le lien Requete <=> Formulaire, la variable $reservation contient les valeurs entrées dans le formulaire
			$form->handleRequest($request);

			// Si les données sont correctes
			if ($form->isValid()) { 

				// On récupère le jour
				$jour = $this->getDoctrine()->getManager()->getRepository('FormBundle:Jour')->findOneBy(array('jour' => $reservation->getJour()->getJour()));

				if ($jour != null) { // Si le jour existe déjà on l'assigne à la reservation
					$reservation->setJour($jour);				
				} 

				// On met le nom en majuscule
				$reservation->setNomReservation(strtoupper($reservation->getNomReservation()));

				// On crée le code aléatoire de reservation grâce au service
				$codeReservationService = $this->container->get('form.codeReservation');
				$codeReservation = $codeReservationService->codeReservation();

				// On verifie que ce code n'existe pas déjà 
				$codeExistant = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->findOneBy(array('code' => $codeReservation));
				
				// Tant que le code créé existe, on en crée un autre
				while ($codeExistant) {
					$codeReservation = $codeReservationService->codeReservation();
				}				

				// On assigne le code
				$reservation->setCode($codeReservation);

				// On recupère l'entity manager
				$em = $this->getDoctrine()->getManager();

				$em->persist($reservation);
				$em->flush();

				// On crée la variable de session pour la reservation
				$session->set('reservation_id', $reservation->getId());

				// On redirige vers la page suivante
				return $this->redirectToRoute('info_billet');
			} else { // Si les données ne sont pas valides
				return $this->render('FormBundle::info_reservation.html.twig', array('form' => $form->createView()));
			}

		} else { // Si on arrive sur la page pour la première fois
			return $this->render('FormBundle::info_reservation.html.twig', array('form' => $form->createView()));
		}
	} //End function reservationAction()


	// Fonction de gestion de la page des billets
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

		
		if ($request->isMethod('POST')) { // Si le formulaire est soumis
			
			// On récupère l'entity manager
			$em = $this->getDoctrine()->getManager();

			// On récupère les billets de la reservation
			$billets = $reservation->getBillets();

			foreach ($billets as $billet) {
				$em->remove($billet); // On supprime les billets précédemment enregistrés dans la BDD
			}
		
			// On fait le lien Requete <=> Formulaire, la variable $reservation contient les valeurs entrées dans le formulaire
			$form->handleRequest($request);

			// Si les données sont valides
			if ($form->isValid()) { 

				foreach ($reservation->getBillets() as $billet) { // Pour chaque billet
					$billet->setNom(strtoupper($billet->getNom())); // On met les noms en majuscule
					$billet->setReservation($reservation); // On assigne la reservation au billet
					$billet->setJour($reservation->getJour()); // On assigne le jour au billet
				}
				
				$em->persist($reservation);

				$em->flush();

				// On redirige vers la page suivante
				return $this->redirectToRoute('info_recapitulatif');
			} else { // Si les données ne sont pas valides
				return $this->render('FormBundle::info_billet.html.twig', array('form' => $form->createView(), 'reservation' => $reservation));
			}

		} else { // Si on arrive sur la page 
			return $this->render('FormBundle::info_billet.html.twig', array('form' => $form->createView(), 'reservation' => $reservation));
		}
	} //End function billetAction()


	// Fonction de gestion de la page de recapitulatif
	public function recapitulatifAction(Request $request) {

		// Recuperation de l'entite manager
		$em = $this->getDoctrine()->getManager();

		// Récupération de la session
		$session = $request->getSession();

		// Récupération de la réservation et des billets
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));
		$billets_reservation = $reservation->getBillets();
		
		// Recuperation du service de calcul du prix des billets
		$calculPrix = $this->container->get('form.calculPrix');
		$prixTotal = 0;

		foreach ($billets_reservation as $billet) { // Pour chaque billet
			$prix = $calculPrix->calculPrix($billet); // On calcul le prix
			$billet->setPrixBillet($prix); // On l'assigne au billet
			$prixTotal += $prix; // On incrémente le prix total
			$em->persist($billet); // On persiste le billet
		}
	
		$reservation->setPrixTotal($prixTotal); // On assigne le prix total à la reservation

		$em->persist($reservation);
		
		$em->flush();

		foreach ($billets_reservation as $billet) { // Pour chaque billet
			// Récuperation du nom complet à partir du code pays
      		$billet->setPays(Intl::getRegionBundle()->getCountryName($billet->getPays(), 'fr'));
		}

		// On crée la vue
		return $this->render('FormBundle::info_recapitulatif.html.twig', array('reservation' => $reservation));
	} //End function recapitulatifAction()


	// Fonction de gestion du paiement
	public function validationPaiementAction(Request $request) {

		//Recuperation de la session
		$session = $request->getSession();

		// Recuperation de l'entite manager
		$em = $this->getDoctrine()->getManager();

		//Recupération du service d'envoie d'email
		$sendEmail = $this->container->get('form.email');

		//Recupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($session->get('reservation_id'));

		//Envoie des billets par email
		$sendEmail->envoyerEmail($reservation);

		// Mise à l'état de payer de la reservation
		$reservation->setEtat(1);
		
		$em->persist($reservation);
		$em->flush();
    
    //création du message flash
    $session->getFlashBag()->add('validation', 'Votre paiement a été validé, vous allez recevoir un email contenant vos billets prochainement.');

    //Redirection vers la page de validation
		return $this->render('FormBundle::validation.html.twig');
	} //End function validationPaiementAction()


	// Fonction de suppression de la session 
	public function clearSessionAction() {

		$this->get('session')->clear();

		return $this->redirectToRoute('info_reservation');
	} //End function clearSessionAction()


	// Fonction de suppression des reservations non payé tous le lundi à 5h du matin 
	public function routineAction() {

		// Recuperation de l'entite manager
		$em = $this->getDoctrine()->getManager();

		//Recupération des reservation en état non validé
		$reservations = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->findBy(array('etat' => 0));

		foreach ($reservations as $reservation) {
			$billets = $reservation->getBillets();
			foreach ($billets as $billet) {
				$em->remove($billet);
			}
			$em->remove($reservation);
		}
		
		$em->flush();

		return new Response("OK");
	} //End function routineAction()



} //End class FormController