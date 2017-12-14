<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 20:50
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProduitController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/produits",name="produits")
     */
    public function listeProduitAction(){

        return $this->render('produit/produits.html.twig');
    }

}