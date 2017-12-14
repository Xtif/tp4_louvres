<?php

namespace tests\FormBundle\CodeReservation;

use PHPUnit\Framework\TestCase;
use FormBundle\CodeReservation\CodeReservation;

class CodeReservationTest extends TestCase
{
	public function testCodeReservation() 
	{
		// Calcul du prix du billet
		$codeClass = new CodeReservation();
		$code = $codeClass->codeReservation();

		$this->assertEquals(5, strlen($code));
	} 


}
