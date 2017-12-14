<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 21:12
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ClientController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/client",name="client")
     */
    public function clientAction(){
        return $this->render('client/client.html.twig');
    }
}