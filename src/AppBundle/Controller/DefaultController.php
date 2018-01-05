<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Panier;
use AppBundle\Entity\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request,AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Produit::class);
        $listeProduit = $repository->findBy(
            ['estCatalogue'=> true]
        );
        $produit = $this->get('knp_paginator')->paginate($listeProduit,$request->query->get('page',1),5);

        $sessionUser = $this->get('session');
        $idUser = $sessionUser->getID();

        // on verifie si le panier exite dans la base de donnée

        $repositoryPanier = $em->getRepository(Panier::class);
        $panier = $repositoryPanier->findOneBy(
            ['idClient' => $idUser]
        );

        if($panier == null){
            // je creer un nouveau panier
            $panier = new Panier();

            $panier->setIdClient($idUser);
            $em->persist($panier);
            $em->flush();

        }

        return $this->render('default/index.html.twig', array('last_username' => $lastUsername,
            'error'=> $error,'listeProduit' => $produit,'panier' => $panier));
    }

    /**
     * @Route("/trier/{item}",name="trier")
     * @param $item
     * @return Response
     */
    public function trierAction($item){

        $encoder = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serealiser = new Serializer($normalizers,$encoder);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Produit::class);

        if($item == "Prix"){
            $trier = $repository->findBy(
                ["estCatalogue" => true],['prix' => 'ASC']
            );
        }elseif ($item =="Désignation"){
            $trier = $repository->findBy(
                ["estCatalogue" => true],['designation' => 'ASC']
            );
        }elseif ($item == "Disponibilité"){
            $trier = $repository->findBy(
                ["estCatalogue" => true],['quantite' => 'ASC']
            );
        }else{
            return new Response("cette page n'exite pas");
        }



        $jsonContent = $serealiser->serialize($trier,'json');

        return new Response($jsonContent);


    }


}
