<?php

namespace FormBundle\Paiement;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

// Execution du paiement
class Paiement 
{
  public function Paiement(Request $request, $prix) {

    $token_id = $request->get('token_id');

    \Stripe\Stripe::setApiKey('sk_test_Z8yLWaZqcsmYblyVjcEA9Bj4');
    $charge = \Stripe\Charge::create(array(
      'amount' => $prix, 
      'currency' => 'eur', 
      'source' => $token_id
    ));

    return new JsonResponse(array('result' => $charge));
  }

} // End class