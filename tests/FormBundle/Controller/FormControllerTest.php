<?php

namespace tests\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use FormBundle\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;


class FormControllerTest extends WebTestCase
{

	public function testReservationActionNouveauVisiteur() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$this->assertContains("Réservation de billets", $client->getResponse()->getContent());
  } // End function testReservationActionNouveauVisiteur()


  public function testReservationActionDonneesValides() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$form = $crawler->selectButton('Valider')->form();

		$values = $form->getPhpValues();

		$values['form']['billets'][0]['nom'] 						= 'Do';
		$values['form']['billets'][0]['prenom'] 				= 'John';
		$values['form']['billets'][0]['pays'] 					= 'FR';
		$values['form']['billets'][0]['dateNaissance'] 	= '13/12/1986';
		$values['form']['billets'][0]['type'] 					= '1'; // Billet journée

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

		$crawler = $client->followRedirect();

		$this->assertContains("Récapitulatif", $client->getResponse()->getContent());
		$this->assertContains("03/01/2018", $client->getResponse()->getContent());
		$this->assertContains("DO", $client->getResponse()->getContent());
		$this->assertContains("John", $client->getResponse()->getContent());
		$this->assertContains("France", $client->getResponse()->getContent());
		$this->assertContains("13/12/1986", $client->getResponse()->getContent());
		$this->assertContains("Journée", $client->getResponse()->getContent());
		$this->assertContains("Non", $client->getResponse()->getContent());
		$this->assertContains("16 €", $client->getResponse()->getContent());

		$crawler = $client->request('GET', '/validation_paiement');

		$this->assertContains("Votre paiement a été validé, vous allez recevoir un email contenant vos billets prochainement.", $client->getResponse()->getContent());
		
  } // End function testReservationActionDonneesValides()


  public function testReservationActionDonneesInvalides() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= 'jeudi'; // Date invalide
		$form['form[nom_reservation]'] 		= ''; // Nom manquant
		$form['form[prenom_reservation]'] = ''; // Prénom manquant
		$form['form[email]'] 							= 'john.dogmail.com'; // Email invalide

		$client->submit($form);

		$this->assertContains("Veuillez entrer une date valide", $client->getResponse()->getContent());
		$this->assertContains("Le nom est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Le prénom est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Veuillez entrer un email valide", $client->getResponse()->getContent());
  } // End function testReservationActionDonneesInvalides()


	public function testBilletActionSansId() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_billet');

		$crawler = $client->followRedirect();

		$this->assertSame(1, $crawler->filter('html:contains("Réservation de billets")')->count());

	} // End of testBilletActionSansId()


	public function testRecapitulatifActionSansId()
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/recapitulatif');

		$crawler = $client->followRedirect();

		$this->assertSame(1, $crawler->filter('html:contains("Réservation de billets")')->count());

	} // End of testRecapitulatifActionSansId()


	public function testBilletRetourReservation() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$link = $crawler->selectLink('Réservation')->link();

		$crawler = $client->click($link);

		$this->assertContains("Réservation de billets", $client->getResponse()->getContent());
		$this->assertContains("DO", $client->getResponse()->getContent());
		$this->assertContains("John", $client->getResponse()->getContent());
		$this->assertContains("3/1/2018", $client->getResponse()->getContent());
		$this->assertContains("john.do@gmail.com", $client->getResponse()->getContent());
  } // End function testBilletRetourReservation()


	public function testRecapitulatifRetourReservation() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$form = $crawler->selectButton('Valider')->form();

		$values = $form->getPhpValues();

		$values['form']['billets'][0]['nom'] 						= 'Do';
		$values['form']['billets'][0]['prenom'] 				= 'John';
		$values['form']['billets'][0]['pays'] 					= 'FR';
		$values['form']['billets'][0]['dateNaissance'] 	= '13/12/1986';
		$values['form']['billets'][0]['type'] 					= '1'; // Billet journée

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

		$crawler = $client->followRedirect();

		$link = $crawler->selectLink('Réservation')->link();

		$crawler = $client->click($link);

		$this->assertContains("Réservation de billets", $client->getResponse()->getContent());
		$this->assertContains("DO", $client->getResponse()->getContent());
		$this->assertContains("John", $client->getResponse()->getContent());
		$this->assertContains("3/1/2018", $client->getResponse()->getContent());
		$this->assertContains("john.do@gmail.com", $client->getResponse()->getContent());
  } // End function testRecapitulatifRetourReservation()


	public function testRecapitulatifRetourBillets() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$form = $crawler->selectButton('Valider')->form();

		$values = $form->getPhpValues();

		$values['form']['billets'][0]['nom'] 						= 'Do';
		$values['form']['billets'][0]['prenom'] 				= 'John';
		$values['form']['billets'][0]['pays'] 					= 'FR';
		$values['form']['billets'][0]['dateNaissance'] 	= '13/12/1986';
		$values['form']['billets'][0]['type'] 					= '1'; // Billet journée

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

		$crawler = $client->followRedirect();

		$link = $crawler->selectLink('Billets')->link();

		$crawler = $client->click($link);

		$this->assertContains("Informations sur vos billets", $client->getResponse()->getContent());
		$this->assertContains("DO", $client->getResponse()->getContent());
		$this->assertContains("John", $client->getResponse()->getContent());
		$this->assertContains("France", $client->getResponse()->getContent());
		$this->assertContains("13/12/1986", $client->getResponse()->getContent());
  } // End function testRecapitulatifRetourBillets()


	public function testBilletDonneesInvalides() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$form = $crawler->selectButton('Valider')->form();

		$values = $form->getPhpValues();

		$values['form']['billets'][0]['nom'] 						= '';
		$values['form']['billets'][0]['prenom'] 				= '';
		$values['form']['billets'][0]['pays'] 					= '';
		$values['form']['billets'][0]['dateNaissance'] 	= '';

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

		$this->assertContains("Le nom est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Le prénom est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Le pays est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Le date de naissance est obligatoire", $client->getResponse()->getContent());
		$this->assertContains("Le type est obligatoire", $client->getResponse()->getContent());
  } // End function testBilletDonneesInvalides()


  public function testClear() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$crawler = $client->request('GET', '/clear');

		$crawler = $client->followRedirect();

		$this->assertContains("Réservation de billets", $client->getResponse()->getContent());
		$this->assertCount(0, $crawler->filter('DO'));
  } // End function testClear()



	public function testBilletSuppressionBillet() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/info_reservation');

		$form = $crawler->selectButton('Valider')->form();

		$form['form[jour][jour]'] 				= '03/01/2018';
		$form['form[nom_reservation]'] 		= 'Do';
		$form['form[prenom_reservation]'] = 'John';
		$form['form[email]'] 							= 'john.do@gmail.com';

		$client->submit($form);

		$crawler = $client->followRedirect();
		
		$form = $crawler->selectButton('Valider')->form();

		$values = $form->getPhpValues();

		$values['form']['billets'][0]['nom'] 						= 'Do';
		$values['form']['billets'][0]['prenom'] 				= 'John';
		$values['form']['billets'][0]['pays'] 					= 'FR';
		$values['form']['billets'][0]['dateNaissance'] 	= '13/12/1986';
		$values['form']['billets'][0]['type'] 					= '1'; // Billet journée

		$values['form']['billets'][1]['nom'] 						= 'Foo';
		$values['form']['billets'][1]['prenom'] 				= 'Bar';
		$values['form']['billets'][1]['pays'] 					= 'FR';
		$values['form']['billets'][1]['dateNaissance'] 	= '18/01/1986';
		$values['form']['billets'][1]['type'] 					= '1'; // Billet journée

		$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

		$crawler = $client->followRedirect();

		$link = $crawler->selectLink('Supprimer')->eq(1)->link();

		$crawler = $client->click($link);

		$this->assertCount(0, $crawler->filter('Foo'));

  } // End function testBilletSuppressionBillet()


  public function testRoutine() 
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/routine');
		$crawler = $client->followRedirect();
		$this->assertContains("Réservation de billets", $client->getResponse()->getContent());
  } // End function testRoutine()


} // End class




