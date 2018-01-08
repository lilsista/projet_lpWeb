<?php
/**
 * Created by PhpStorm.
 * User: colib
 * Date: 04/01/2018
 * Time: 22:08
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Panier;
use AppBundle\Entity\PanierContient;
use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PanierController extends Controller
{
    /**
     * @Route("/panier/{idPanier}",name="panier")
     * @param $idPanier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($idPanier){


        // j'affiche le panier
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PanierContient::class);

        $panierContient = $repository->findBy(
            ['idPanier' => $idPanier]
        );



        // le prix Total
        $prixTotal = 0;

        foreach ($panierContient as $value){
            $prixTotal = $prixTotal + ($value->getIdProduit()->getPrix()*$value->getQuantite());
        }


        return $this->render('panier/panier.html.twig',array('panier' => $panierContient, 'prixTotal'=> $prixTotal));
    }

    /**
     * @Route("/ajouterPanier/{id}/{idPanier}",name="ajouterPanier")
     * @param $id
     * @param $idPanier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function AjouterAction($id,$idPanier){

        $em = $this->getDoctrine()->getManager();
        $repositoryPanier = $em->getRepository(Panier::class);
        $repositoryProduit = $em->getRepository(Produit::class);
        $panier = $repositoryPanier->find($idPanier);
        $produit = $repositoryProduit->find($id);
        $quantite = 1;
        $panierContient = new PanierContient();
        /** @var Panier $panier */
        $panierContient->setIdPanier($panier);
        /** @var Produit $produit */
        $panierContient->setIdProduit($produit);
        $panierContient->setQuantite($quantite);

        $em->persist($panierContient);
        $em->flush();

        return $this->redirect($this->generateUrl('panier',array('idPanier' => $idPanier)));



    }

    /**
     * @Route("/quantite/{id}/{quantite}", name="quantite")
     * @param $id
     * @param $quantite
     */
    public function changeprixAction($id,$quantite){
        $em = $this->getDoctrine()->getManager();
        $repositoty = $em->getRepository(PanierContient::class);

        // on récupére le produit
        $panierContient = $repositoty->findOneBy(
            ['id' => $id]
        );

        $panier = $repositoty->findAll();

        // on modifi la quantité
       $panierContient->setQuantite($quantite);
       $em->flush();

       $quantiteModif = $panierContient->getQuantite();
       $prix = $panierContient->getIdProduit()->getPrix();
       $prixTotal = $quantiteModif*$prix;
       $reponse = new JsonResponse();
        return $reponse->setData(array('prixTotal'=> $prixTotal));

    }
}