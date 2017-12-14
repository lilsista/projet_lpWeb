<?php
/**
 * Created by PhpStorm.
 * User: lilsista
 * Date: 13/12/2017
 * Time: 18:53
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommercantController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/commercant", name="commercant")
     */
    public function indexAction(){
        return $this->render('commercant/index.html.twig');
    }
}