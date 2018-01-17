<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 18:53
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Client;
use AppBundle\Entity\Commande;
use AppBundle\Entity\PanierContient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class CommercantController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commercant", name="commercant")
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Commande::class);
        $repositoryClient = $em->getRepository(Client::class);

        //$client = $repository->findAll();

        $commandes = $repository->findBy(array(
            'estLivre' => false
        ));

        $listeCommande = $this->get('knp_paginator')->paginate($commandes,$request->query->get('page',1),6);

        /*foreach ($commandes as $value){
            $panier = $repositoryPanier->findBy(array(
                'idPanier' => $value->getPanier()->getId()
            ))
        }*/


        return $this->render('commercant/index.html.twig',array('commandes'=>$listeCommande));
    }

    /**
     * @Route("/commercant/commandes",name="commandes")
     */

    public function listeCommandesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Commande::class);
        $repositoryPanier = $em->getRepository(PanierContient::class);

        $commandes = $repository->findAll();
        $listeCommande = $this->get('knp_paginator')->paginate($commandes,$request->query->get('page',1),6);


        return $this->render('commande/listeCommande.html.twig',array(
            'commandes'=> $listeCommande
        ));
    }

    /**
     * @param $idCommande
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/commercant/livrer/{idCommande}",name="livrerCommande")
     */
    public function livrerAction($idCommande){

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Commande::class);

        $commandes = $repository->findOneBy(array(
            'estLivre' => false
        ));
        $commande = $repository->find($idCommande);

        $commande->setEstLivre(true);
        $em->flush();

        return $this->redirectToRoute('commercant',array('commande' =>$commandes));
    }
}