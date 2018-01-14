<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="est_livre", type="boolean", options={"default" = false})
     */
    private $estLivre;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_total", type="float")
     */
    private $montantTotal;

    /**
     * @ORM\OneToOne(targetEntity="Panier")
     * @ORM\JoinColumn(name="idPanier",referencedColumnName="id")
     */
    private $panier;

    /**
     * @ORM\OneToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="idClient",referencedColumnName="id")
     */
    private $client;

    /**
     * @return mixed
     */
    public function getPanier()
    {
        return $this->panier;
    }

    /**
     * @param mixed $panier
     */
    public function setPanier($panier)
    {
        $this->panier = $panier;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set estLivre
     *
     * @param boolean $estLivre
     *
     * @return Commande
     */
    public function setEstLivre($estLivre)
    {
        $this->estLivre = $estLivre;

        return $this;
    }

    /**
     * Get estLivre
     *
     * @return bool
     */
    public function getEstLivre()
    {
        return $this->estLivre;
    }

    /**
     * Set montantTotal
     *
     * @param float $montantTotal
     *
     * @return Commande
     */
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get montantTotal
     *
     * @return float
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }
}

