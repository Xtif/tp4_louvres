<?php

namespace tests\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;


class AjaxControllerTest extends WebTestCase
{

	

	// public function testverificationPaiementAction() 
	// {
	// 	$client = static::createClient();

	// 	$crawler = $client->request('GET', '/info_reservation');

	// 	$form = $crawler->selectButton('Valider')->form();

	// 	$form['form[jour][jour]'] 				= '03/01/2018';
	// 	$form['form[nom_reservation]'] 		= 'Do';
	// 	$form['form[prenom_reservation]'] = 'John';
	// 	$form['form[email]'] 							= 'john.do@gmail.com';

	// 	$client->submit($form);

	// 	$crawler = $client->followRedirect();
		
	// 	$form = $crawler->selectButton('Valider')->form();

	// 	$values = $form->getPhpValues();

	// 	$values['form']['billets'][0]['nom'] 						= 'Do';
	// 	$values['form']['billets'][0]['prenom'] 				= 'John';
	// 	$values['form']['billets'][0]['pays'] 					= 'FR';
	// 	$values['form']['billets'][0]['dateNaissance'] 	= '13/12/1986';
	// 	$values['form']['billets'][0]['type'] 					= '1'; // Billet journée

	// 	$crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

	// 	$crawler = $client->followRedirect();

	// 	$crawler = $client->request('GET', '/verification_paiement');

	// 	echo $client->getResponse()->getContent();

	// 	// $this->assertContains("Votre paiement a été validé, vous allez recevoir un email contenant vos billets prochainement.", $client->getResponse()->getContent());
		
 //  } // End function testverificationPaiementAction()




} // End class




