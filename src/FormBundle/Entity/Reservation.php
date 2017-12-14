<?php

namespace FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\ManyToOne(targetEntity="FormBundle\Entity\Jour", inversedBy="reservations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "Le jour est obligatoire")
     * @Assert\Type("object", message = "Veuillez entrer une date valide")
     */
    private $jour;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_reservation", type="string", length=255)
     * @Assert\NotBlank(message = "Le nom est obligatoire")
     * @Assert\Type("string", message = "Votre nom ne peut contenir que des caractères alphanumériques")
     * @Assert\Length(max=255, maxMessage = "Votre nom doit comporter au maximum 255 caractères")
     */
    private $nom_reservation;    

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_reservation", type="string", length=255)
     * @Assert\NotBlank(message = "Le prénom est obligatoire")
     * @Assert\Type("string", message = "Votre prénom ne peut contenir que des caractères alphanumériques")
     * @Assert\Length(max=255, maxMessage = "Votre nom doit comporter au maximum 255 caractères")
     */
    private $prenom_reservation;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message = "L'email est obligatoire")
     * @Assert\Email(message = "Veuillez entrer un email valide", checkMX = true)
     * @Assert\Length(max=255, maxMessage = "Votre nom doit comporter au maximum 255 caractères")
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_total", type="integer")
     */
    private $prixTotal;

    /**
     * @ORM\OneToMany(targetEntity="FormBundle\Entity\Billet", mappedBy="reservation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $billets;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prixTotal = 0;
        $this->etat = 0;
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
     * Add billet
     *
     * @param \FormBundle\Entity\Billet $billet
     *
     * @return Reservation
     */
    public function addBillet(\FormBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;

        // On lie le billet à la réservation
        $billet->setReservation($this);

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
     * Set jour
     *
     * @param \FormBundle\Entity\Jour $jour
     *
     * @return Reservation
     */
    public function setJour(\FormBundle\Entity\Jour $jour = null)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \FormBundle\Entity\Jour
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Reservation
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     *
     * @return Reservation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return integer
     */
    public function getEtat()
    {
        return $this->etat;
    }
}
