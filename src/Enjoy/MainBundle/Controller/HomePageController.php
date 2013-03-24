<?php

namespace Enjoy\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomePageController extends Controller
{
    public function indexAction()
    {
        $facilities = $this->getDoctrine()
            ->getRepository('EnjoyMainBundle:Facility')
            ->findAll();

        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData);
        foreach($facilities as $facility)
        {
            $form->add($facility->getId(), 'checkbox', array(
                'label' => $facility->getLocationName(),
                'required'  => false
            ));
        }





        $form = $form->getForm();

        return $this->render('EnjoyMainBundle:HomePage:home_page.html.twig', array("form" => $form->createView()));
    }


}
