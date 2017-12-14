<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 21:16
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommandeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commande",name="commande")
     */
    public function commandeAction(){
        return $this->render('commande/commande.html.twig');
    }

}