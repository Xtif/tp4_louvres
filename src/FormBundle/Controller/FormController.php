<?php

namespace FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FormBundle\Entity\Billet;
use FormBundle\Entity\Jour;
use FormBundle\Entity\Reservation;

use FormBundle\FormType\JourType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FormController extends Controller
{

	public function reservationAction(Request $request) {

		// Création d'une réservation
		$reservation = new Reservation();

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

				return $this->redirectToRoute('info_billet', array('id' => $reservation->getId()));
			}

		// Si on arrive sur la page pour la première fois
		} else {
			return $this->render('FormBundle::info_reservation.html.twig', array('form' => $form->createView()));
		}

	} //End function reservationAction()





	public function billetAction($id, Request $request) {

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($id);

		// Création du billet
		$billet = new Billet();

		// Assignation des valeurs de la réservation au billet
		$billet->setReservation($reservation);
		$billet->setJour($reservation->getJour());

		// Création du formulaire
		$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $billet);

		// Création des champs du formulaire
		$formBuilder
			->add('nom',								TextType::class)
			->add('prenom',							TextType::class)
			->add('pays',								CountryType::class)
			->add('dateNaissance',			DateType::class)
			->add('type',								ChoiceType::class, array(
																			'choices' => array(
																					'Demi-journée (à partir de 14h)' => '0',
																					'Journée' => '1'), 
																			'multiple' => false, 
																			'expanded' => true))
			->add('tarifReduit',				CheckboxType::class, array('required' => false))
			->add('submit',							SubmitType::class);

		// On génère le formulaire
		$form = $formBuilder->getForm();
		
		if ($request->isMethod('POST')) {

			// On fait le lien Requete <=> Formulaire, la variable $reservation contientles valeurs entrées dans le formulaire
			$form->handleRequest($request);

			// Si les données sont correctes
			if ($form->isValid()) { 

				$em = $this->getDoctrine()->getManager();
				$em->persist($billet);
				$em->flush();

				return $this->redirectToRoute('info_recapitulatif', array('id' => $reservation->getId()));
			}

		} else {

			return $this->render('FormBundle::info_billet.html.twig', array('form' =>$form->createView()));

		}

	} //End function billetAction()





	public function recapitulatifAction($id) {

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($id);

		return $this->render('FormBundle::info_recapitulatif.html.twig', array('reservation' => $reservation));

	} //End function billetAction()




	public function paiementAction($id) {

		// Récupération de la réservation
		$reservation = $this->getDoctrine()->getManager()->getRepository('FormBundle:Reservation')->find($id);

		return $this->render('FormBundle::info_paiement.html.twig', array('reservation' => $reservation));

	} //End function billetAction()


} //End class FormController