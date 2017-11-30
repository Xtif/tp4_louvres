<?php
// src/OC/PlatformBundle/Email/ApplicationMailer.php
namespace FormBundle\Email;

use FormBundle\Entity\Reservation;

// Service d'envoie d'un email
class Email
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  public function __construct(\Swift_Mailer $mailer, $templating)
  {
    $this->mailer = $mailer;
    $this->templating = $templating;
  }

  public function envoyerEmail(\FormBundle\Entity\Reservation $reservation)
  {
    $message = new \Swift_Message('Musée du Louvres - Confirmation de réservation');
    $message
      ->setFrom('reservation@museeLouvres.com')
      ->setTo($reservation->getEmail())
      ->setBody(
        $this->templating->render('FormBundle::email.html.twig', array('reservation' => $reservation)), 'text/html')
    ;

    $this->mailer->send($message);
  }

}