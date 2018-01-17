<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 21:16
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\CommandeProduit;
use AppBundle\Entity\PanierContient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $repositoryCommandeProduit = $em->getRepository(CommandeProduit::class);
        $commandes = $repository->findBy(array(
            "panier" => $id
        ));

        $idComm = $repositoryCommandeProduit->findAll();

        $idCommande = 0;
        foreach ($commandes as $commande){
            foreach ($idComm as $value){
                if($commande->getId() == $value->getIdCommande()){
                    $idCommande = $commande->getID();
                }
            }
        }

            $commandeClient = $repositoryCommandeProduit->findBy(array(
                'idCommande'=> $idCommande
            ));


        return $this->render('commande/commande.html.twig',array('commande'=>$commandeClient));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/commercant/commandes/trier", name="trierCommande")
     */
    public function trierCommandeAction(Request $request){

        $encoder = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serealiser = new Serializer($normalizers,$encoder);

        $element = $request->get('element');
       // $element = "alivrer";
        //$element = $item;
        $em = $this->getDoctrine()->getManager();
        $repositoryCommande = $em->getRepository(Commande::class);
        switch ($element){
            case "alivrer":
                $commande = $repositoryCommande->findOneBy(array('estLivre'=> false));
                break;
            case "livrer" :
                $commande = $repositoryCommande->findOneBy(array('estLivre'=> true ));
                break;
            case "date":
                $commande = $repositoryCommande->findBy(array(),array('date' =>'ASC'));
                break;
            case "client":
                $commande = $repositoryCommande->findBy(array(),array('client'=> 'ASC'));
                break;
        }

        $jsonContent = $serealiser->serialize(array('commande'=>$commande),'json');
        return new Response($jsonContent);
        //return $this->redirectToRoute('commandes',array('commandes' =>$commande));
    }



}