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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/produits",name="produits")
     */
    public function AjouterProduitAction(Request $request){

        // créer un produit
        $produit = new Produit();

        //créer le formulaire lier a produit
        $formProduit = $this->createForm(ProduitType::class,$produit);

        $formProduit->handleRequest($request);

        if( $formProduit->isSubmitted() && $formProduit->isValid()){

            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
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

            return new Response('Ajout réussi');
        }

        return $this->render('produit/produits.html.twig',array('formProduit'=>$formProduit->createView()));

    }
    


}