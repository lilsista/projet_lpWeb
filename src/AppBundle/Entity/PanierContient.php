<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PanierContient
 *
 * @ORM\Table(name="panier_contient")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PanierContientRepository")
 */
class PanierContient
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
     * @var int
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumn(name="idPanier", referencedColumnName="id")
     */
    private $idPanier;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="idProduit",referencedColumnName="id")
     */
    private $idProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estCommander", type="boolean",options={"default" = false})
     */
    private $est_commander;

    /**
     * @return bool
     */
    public function isEstCommander()
    {
        return $this->est_commander;
    }

    /**
     * @param bool $est_commander
     */
    public function setEstCommander($est_commander)
    {
        $this->est_commander = $est_commander;
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
     * Set idPanier
     *
     * @param integer $idPanier
     *
     * @return PanierContient
     */
    public function setIdPanier($idPanier)
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    /**
     * Get idPanier
     *
     * @return int
     */
    public function getIdPanier()
    {
        return $this->idPanier;
    }

    /**
     * Set idProduit
     *
     * @param integer $idProduit
     *
     * @return PanierContient
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    /**
     * Get idProduit
     *
     * @return int
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return PanierContient
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
}

