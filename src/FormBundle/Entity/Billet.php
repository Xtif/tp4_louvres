<?php

namespace FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="FormBundle\Repository\BilletRepository")
 */
class Billet
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
     * @ORM\ManyToOne(targetEntity="FormBundle\Entity\Reservation", inversedBy="billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

    /**
     * @ORM\ManyToOne(targetEntity="FormBundle\Entity\Jour", inversedBy="billets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jour;

    /**
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean")
     * @Assert\NotBlank(message = "Le type de billet est obligatoire")
     * @Assert\Type("bool", message = "Veuillez entrer un type de billet valide")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message = "Le nom est obligatoire")
     * @Assert\Type("string", message = "Votre nom ne peut contenir que des caractères alphanumériques")
     * @Assert\Length(max=255, maxMessage = "Votre nom doit comporter au maximum 255 caractères")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(message = "Le prénom est obligatoire")
     * @Assert\Type("string", message = "Votre prénom ne peut contenir que des caractères alphanumériques")
     * @Assert\Length(max=255, maxMessage = "Votre prénom doit comporter au maximum 255 caractères")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     * @Assert\NotBlank(message = "Le pays est obligatoire")
     * @Assert\Country(message = "Veuillez entrer un pays valide")
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date")
     * @Assert\NotBlank(message = "Le date de naissance est obligatoire")
     * @Assert\Datetime(message = "Veuillez entrer une date de naissance valide")
     */
    private $dateNaissance;

    /**
     * @var bool
     *
     * @ORM\Column(name="tarif_reduit", type="boolean")
     * @Assert\Type("bool", message = "Veuillez entrer un tarif valide valide")
     */
    private $tarifReduit;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_billet", type="integer")
     */
    private $prixBillet;

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prixBillet = 0;
        $this->tarifReduit = false;
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
     * Set jourBillet
     *
     * @param \DateTime $jourBillet
     *
     * @return Billet
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jourBillet
     *
     * @return \DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set type
     *
     * @param boolean $type
     *
     * @return Billet
     */
    public function setType($type)
    {   

        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return bool
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Billet
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Billet
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Billet
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return bool
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set prixBillet
     *
     * @param integer $prixBillet
     *
     * @return Billet
     */
    public function setPrixBillet($prixBillet)
    {
        $this->prixBillet = $prixBillet;

        return $this;
    }

    /**
     * Get prixBillet
     *
     * @return int
     */
    public function getPrixBillet()
    {
        return $this->prixBillet;
    }

    /**
     * Set reservation
     *
     * @param \FormBundle\Entity\Reservation $reservation
     *
     * @return Billet
     */
    public function setReservation(\FormBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \FormBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
