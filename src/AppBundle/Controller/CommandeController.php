<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 21:16
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\PanierContient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommandeController extends Controller
{
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commande/{id}",name="commandeClient")
     */
    public function commandeAction($id){

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Commande::class);
        $repositoryPanier = $em->getRepository(PanierContient::class);

        $commandeClient = $repository->findBy(array(
            'panier' => $id
        ));
        $panier = $repositoryPanier->findBy(array(
            'idPanier' => $id
        ));

        return $this->render('commande/commande.html.twig',array('commande'=>$commandeClient,'panier' =>$panier));
    }

}