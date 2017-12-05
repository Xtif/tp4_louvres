<?php

namespace tests\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormControllerTest extends WebTestCase
{

	public function testReservationAction() 
	{
		$client = static::createClient();

		$crawler = $client->request('GET', '/tp4_louvres/web/app.php/info_reservation');

		$bouton = $crawler->selectButton('Valider');

		$form = $bouton->form();

		$validationFormulaire = $client->submit($form, array(
			'#form_jour_jour'					=> 	'12/12/2017',
			'#form_nom_reservation'		=>	'Robert',
			'#form_prenom_reservation'=>	'Paul',
			'#form_email'							=>	'paul.robert@gmail.com'
		));

		$this->assertTrue($client->getResponse()->isRedirect('/tp4_louvres/web/app.php/info_billet'));

	} // End of testReservationAction()


} // End class




