<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 20:50
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Produit;
use AppBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ProduitController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/produit",name="ajoutProduit")
     */
    public function AjouterProduitAction(Request $request){

        // créer un produit
        $produit = new Produit();

        //créer le formulaire lier a produit
        $formProduit = $this->createForm(ProduitType::class,$produit)->add('ajouter',SubmitType::class);;

        $formProduit->handleRequest($request);

        if( $formProduit->isSubmitted() && $formProduit->isValid()){

            // $file stores the uploaded PDF file
            /**@var UploadedFile $file */
            $file = $produit->getImage();


            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $produit->setImage($fileName);


            // on ajoute les données dans la base de donnée
            $data = $formProduit->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            $repository = $em->getRepository(Produit::class);
            $listeProduit = $repository->findBy(array('estCatalogue' => true),array('id'=> "DESC"));

            $produits = $this->get('knp_paginator')->paginate($listeProduit,$request->query->get('page',1),5);

            return $this->redirectToRoute('listeProduit',array('listeProduit' => $produits));
        }

        return $this->render('produit/produit.html.twig',array('formProduit'=>$formProduit->createView()));

    }

    /**
     * @Route("/produits",name="listeProduit")
     * @param Request $req
     * @return Response
     */
    public function listeProduitAction(Request $req){

        $repository = $this->getDoctrine()->getManager()->getRepository(Produit::class);
        $listeProduit = $repository->findBy(
            ['estCatalogue' => true],['id'=> "DESC"]
        );

        $produit = $this->get('knp_paginator')->paginate($listeProduit,$req->query->get('page',1),5);


        return $this->render('produit/produits.html.twig',array('listeProduit'=>$produit));

    }

    /**
     * @Route("/modifier/{id}",name="modifierProduit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function modifierProduitAction($id, Request $request){
        //on récupére les informations du produit dont l'id = $id
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);


        $formProduit = $this->createForm(ProduitType::class,$produit)
        ->add('modifier',SubmitType::class);

        $image = $produit->getImage();

        if ($formProduit->handleRequest($request)->isValid()) {

            // $file stores the uploaded PDF file

            if($formProduit['image']->getData() == null){
                $produit->setImage($image);
            }
            else{


                $file = $produit->getImage();


                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                // Update the 'brochure' property to store the PDF file name
                // instead of its contents
                $produit->setImage($fileName);
                // Inutile de persister ici, Doctrine connait déjà notre annonce
            }
            $em->flush();

        }

        return $this->render('produit/modifier.html.twig',array('produit'=>$produit,'formProduit'=>$formProduit->createView()));
    }

    /**
     * @Route("/supprimer/{id}",name="supprimer")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function supprimerAction($id,Request $request){


        // récupére le produit dont l'id est $id
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Produit::class);
        $produit = $repository->find($id);

        // je modifi la valeur de estCatalogue
        $produit->setEstCatalogue(false);

        $em->flush();

        $reponse = new JsonResponse();
        return $reponse->setData(array('idLigne' => $id));

    }



}