<?php

namespace tests\FormBundle\CalculPrix;

use PHPUnit\Framework\TestCase;
use FormBundle\CalculPrix\CalculPrix;
use FormBundle\Entity\Billet;
use FormBundle\Entity\Jour;


class CalculPrixTest extends TestCase
{

	public $billet;
	public $jour;

	public function setUp() {
		// Mock des objets billet et jour de visite
		$this->billet = $this->getMockBuilder(Billet::class)
			->disableOriginalConstructor()
	    ->disableOriginalClone()
	    ->disableArgumentCloning()
	    ->disallowMockingUnknownTypes()
	    ->getMock();

	  $this->jour = $this->getMockBuilder(Jour::class)
			->disableOriginalConstructor()
	    ->disableOriginalClone()
	    ->disableArgumentCloning()
	    ->disallowMockingUnknownTypes()
	    ->getMock();
	}

	public function tearDown() {
		// Vidage mémoire
		$this->billet = null;
	  $this->jour = null;
	}

	/**
	 * @dataProvider donnees
	 */
	public function testCalculPrixBillet($dateNaissance, $demiJournee, $tarifReduit, $prixAttendu) 
	{
		// création du jour de visite
	  $this->jourVisite = new \Datetime('2018-01-03');

	  // Conversion de la date de naissance en objet Datetime
		$dateNaissance = new \Datetime($dateNaissance);

		// Assignation des données aux Mocks
	  $this->jour->expects($this->once())->method('getJour')->will($this->returnValue($this->jourVisite));
	  $this->billet->expects($this->once())->method('getJour')->will($this->returnValue($this->jour));
		$this->billet->expects($this->once())->method('getDateNaissance')->will($this->returnValue($dateNaissance));
		$this->billet->expects($this->once())->method('getType')->will($this->returnValue($demiJournee));
		$this->billet->expects($this->any())->method('getTarifReduit')->will($this->returnValue($tarifReduit));

		// Calcul du prix du billet
		$calcul = new CalculPrix();
		$prix = $calcul->calculPrix($this->billet);

		$this->assertEquals($prixAttendu, $prix);
	} 

	public function donnees() {
		return [
			['2015-01-01', 1, 0, 0], // Tarif enfant (< 4 ans), Journée, Plein tarif
			['2008-01-01', 1, 0, 8], // Tarif jeune (< 12 ans), Journée, Plein tarif
			['1988-01-01', 1, 0, 16], // Tarif normal (30 ans), Journée, Plein tarif
			['1953-01-01', 1, 0, 12], // Tarif senior (> 60 ans), Journée, Plein tarif
			['1988-01-01', 1, 1, 10], // Tarif normal (30 ans), Journée, Tarif réduit
			['1988-01-01', 0, 0, 8] // Tarif normal (30 ans), Demi-journée, Plein tarif
		];
	}



}
