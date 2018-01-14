<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 18:53
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\PanierContient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommercantController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commercant", name="commercant")
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Commande::class);
        $repositoryPanier = $em->getRepository(PanierContient::class);

        $commandes = $repository->findBy(array(
            'estLivre' => false
        ));

        foreach ($commandes as $value){
            $panier = $repositoryPanier->findBy(array(
                'idPanier' => $value->getPanier()->getId()
            ));
        }

        return $this->render('commercant/index.html.twig',array('commandes'=>$commandes,'panier'=>$panier));
    }

    public function livrerAction($idCommande){

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Commande::class);

        $commande = $repository->find($idCommande);

        $commande->setEstLivrer(true);
        $em->flush();

        return $this->redirectToRoute('commercant');
    }
}