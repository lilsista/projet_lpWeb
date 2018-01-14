<?php
/**
 * Created by PhpStorm.
 * User: colib
 * Date: 04/01/2018
 * Time: 22:08
 */

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Client;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Panier;
use AppBundle\Entity\PanierContient;
use AppBundle\Entity\Produit;
use AppBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PanierController extends Controller
{
    /**
     * @Route("/panier/{idPanier}",name="panier")
     * @param $idPanier
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($idPanier,Request $request){


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

        /** @var TYPE_NAME $bool */
        $bool = false;

        foreach ($panierContient as $value){
            if($value->isEstCommander() == $bool){
                $bool = false;
            }else{
                $bool = true;
            }
        }

        return $this->render('panier/panier.html.twig',array(
                'panier' => $panierContient,
                'prixTotal'=> $prixTotal,
                'idPanier'=> $idPanier,
                'estCommander' => $bool
        ));
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

        $panierContient->setIdPanier($panier);

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
     * @return JsonResponse
     */
    public function changeprixAction($id,$quantite){
        $em = $this->getDoctrine()->getManager();
        $repositoty = $em->getRepository(PanierContient::class);

        // on récupére le produit contenu dans le panier
        $panierContient = $repositoty->findOneBy(
            ['id' => $id]
        );


        // on modifi la quantité
       $panierContient->setQuantite($quantite);
       $em->flush();

       $quantiteModif = $panierContient->getQuantite();
       $prix = $panierContient->getIdProduit()->getPrix();
       $prixTotal = $quantiteModif*$prix;


       $reponse = new JsonResponse();
        return $reponse->setData(array('prixTotal'=> $prixTotal));

    }

    /**
     * @Route("/gestionCommande",name="gestionCommande")
     * @param Request $request
     * @return string
     */
    public function gestionCommandeAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        // on récupére les données
        $email = $request->get('email');
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $idPanier = $request->get('idpanier');

        // on vérifie si elle ne sont pas vide
        if(!empty($email) && !empty($nom) && !empty($prenom) && !(empty($idPanier))){


            $repositoryPanier = $em->getRepository(Panier::class);
            $repositoryClient = $em->getRepository(Client::class);
            $repositoryPanierContient = $em->getRepository(PanierContient::class);

            // on récupére les produits contenu dans le panier
            $produitPanier = $repositoryPanierContient->findBy(array(
                'idPanier' => $idPanier
            ));

            $client = $repositoryClient->findOneBy(array(
                "panier" => $idPanier
            ));
            $date = date_create(date('Y-m-d'));

            $panier = $repositoryPanier->find($idPanier);

            $quantiteMax = 0;
            foreach ($produitPanier as $value){
                $quantiteMax = $value->getIdProduit()->getQuantite();
                $quantiteMax = $quantiteMax - $value->getQuantite();
                if($quantiteMax<0){
                    $value->getIdProduit()->setQuantite(0);
                }else{
                    $value->getIdProduit()->setQuantite($quantiteMax);
                }

            }



            if ($client) {

                $commande = new Commande();

                $commande->setPanier($panier);
                $commande->setClient($client);
                $commande->setDate($date);
                $commande->setMontantTotal($request->get('montantTotal'));
                $commande->setEstLivre(false);

                $em->persist($commande);
                $em->flush();
            } else {


                $client = new Client();

                $client->setNom($nom);
                $client->setPanier($panier);
                $client->setPrenom($prenom);
                $client->setEmail($email);



                $commande = new Commande();

                $commande->setPanier($panier);
                $commande->setClient($client);
                $commande->setDate($date);
                $commande->setMontantTotal($request->get('montantTotal'));
                $commande->setEstLivre(false);

                $em->persist($client);
                $em->persist($commande);
                $em->flush();

            }

            foreach ($produitPanier as $value){
                $value->setEstCommander(true);
                $em->flush();
            }


        }
        return new Response('Vôtre commande est disponible dans la section "panier/Vos Commandes"');

    }



}