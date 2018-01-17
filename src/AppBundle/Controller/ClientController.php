<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 21:12
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commercant/client",name="client")
     */
    public function clientAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Client::class);

        $clients = $repository->findAll();
        $listeClients = $this->get('knp_paginator')->paginate($clients,$request->get('page',1),6);

        return $this->render('client/client.html.twig',array(
            'clients' =>$listeClients
        ));
    }
}