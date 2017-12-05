<?php

namespace tests\FormBundle\Services;

use PHPUnit\Framework\TestCase;
use FormBundle\CalculPrix\CalculPrix;
use FormBundle\Entity\Billet;
use FormBundle\Entity\Jour;

class CalculPrixTest extends TestCase
{

	public function testCalculPrix() 
	{
		
		$jour = new \Datetime('2017-12-12');
		$dateNaissance = new \Datetime('1986-12-13');
		
		$billet = $this->getMockBuilder(Billet::class)
										->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->disableArgumentCloning()
                    ->disallowMockingUnknownTypes()
                    ->getMock();

    $jourVisite = $this->getMockBuilder(Jour::class)
										->disableOriginalConstructor()
                    ->disableOriginalClone()
                    ->disableArgumentCloning()
                    ->disallowMockingUnknownTypes()
                    ->getMock();

    $jourVisite->setJour($jour);
    $billet->setJour($jourVisite);
		$billet->setDateNaissance($dateNaissance);

		$test = new CalculPrix();
		$prix = $test->calculPrix($billet);

		$this->assertEquals(16, $prix);


	} // End of testReservationAction()


} // End class




