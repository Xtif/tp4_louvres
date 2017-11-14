<?php

namespace FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="FormBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_reservation", type="string", length=255)
     */
    private $nom_reservation;    

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_reservation", type="string", length=255)
     */
    private $prenom_reservation;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbre_billets", type="integer")
     */
    private $nbreBillets;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_total", type="integer")
     */
    private $prixTotal;

    /**
     * @ORM\OneToMany(targetEntity="FormBundle\Entity\Billet", mappedBy="reservation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $billets;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomReservation
     *
     * @param string $nomReservation
     *
     * @return Reservation
     */
    public function setNomReservation($nomReservation)
    {
        $this->nom_reservation = $nomReservation;

        return $this;
    }

    /**
     * Get nomReservation
     *
     * @return string
     */
    public function getNomReservation()
    {
        return $this->nom_reservation;
    }

    /**
     * Set prenomReservation
     *
     * @param string $prenomReservation
     *
     * @return Reservation
     */
    public function setPrenomReservation($prenomReservation)
    {
        $this->prenom_reservation = $prenomReservation;

        return $this;
    }

    /**
     * Get prenomReservation
     *
     * @return string
     */
    public function getPrenomReservation()
    {
        return $this->prenom_reservation;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nbreBillets
     *
     * @param integer $nbreBillets
     *
     * @return Reservation
     */
    public function setNbreBillets($nbreBillets)
    {
        $this->nbreBillets = $nbreBillets;

        return $this;
    }

    /**
     * Get nbreBillets
     *
     * @return int
     */
    public function getNbreBillets()
    {
        return $this->nbreBillets;
    }

    /**
     * Set prixTotal
     *
     * @param integer $prixTotal
     *
     * @return Reservation
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal
     *
     * @return int
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add billet
     *
     * @param \FormBundle\Entity\Billet $billet
     *
     * @return Reservation
     */
    public function addBillet(\FormBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        return $this;
    }

    /**
     * Remove billet
     *
     * @param \FormBundle\Entity\Billet $billet
     */
    public function removeBillet(\FormBundle\Entity\Billet $billet)
    {
        $this->billets->removeElement($billet);
    }

    /**
     * Get billets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillets()
    {
        return $this->billets;
    }
}
