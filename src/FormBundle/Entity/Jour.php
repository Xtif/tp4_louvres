<?php

namespace FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="jour", type="datetime", unique=true)
     */
    private $jour;

    /**
     * @var int
     *
     * @ORM\Column(name="nbre_billets_jour", type="integer")
     */
    private $nbre_billets_jour;


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
     * Set nbreBilletsJour
     *
     * @param integer $nbreBilletsJour
     *
     * @return Jour
     */
    public function setNbreBilletsJour($nbreBilletsJour)
    {
        $this->nbre_billets_jour = $nbreBilletsJour;

        return $this;
    }

    /**
     * Get nbreBilletsJour
     *
     * @return integer
     */
    public function getNbreBilletsJour()
    {
        return $this->nbre_billets_jour;
    }
}
