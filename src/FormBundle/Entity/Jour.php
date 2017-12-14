<?php

namespace FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jour
 *
 * @ORM\Table(name="jour")
 * @ORM\Entity(repositoryClass="FormBundle\Repository\JourRepository")
 */
class Jour
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
     * @var \DateTime
     *
     * @ORM\Column(name="jour", type="date", unique=true)
     * @Assert\NotBlank(message = "Le jour est obligatoire")
     * @Assert\DateTime(message = "Veuillez entrer une date valide")
     */
    private $jour;

    /**
     * @ORM\OneToMany(targetEntity="FormBundle\Entity\Reservation", mappedBy="jour")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="FormBundle\Entity\Billet", mappedBy="jour")
     * @ORM\JoinColumn(nullable=false)
     */
    private $billets;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->billets = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set jour
     *
     * @param \DateTime $jour
     *
     * @return Jour
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Add billet
     *
     * @param \FormBundle\Entity\Billet $billet
     *
     * @return Jour
     */
    public function addBillet(\FormBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        // On lie le billet au jour
        $billet->setJour($this);

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

    /**
     * Add reservation
     *
     * @param \FormBundle\Entity\Reservation $reservation
     *
     * @return Jour
     */
    public function addReservation(\FormBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        // On lie la reservation au jour
        $reservation->setJour($this);

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \FormBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\FormBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }


}
