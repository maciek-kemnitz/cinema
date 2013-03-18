<?php

namespace Enjoy\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Enjoy\MainBundle\Entity\Crowled;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @var \Enjoy\MainBundle\Entity\Movie $movie
     */
    protected $movie;
    public function showAction($date)
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST')
        {
            $post = $request->request->get('form');
            unset($post['_token']);
        }

        $facilityIds = array_keys($post);

        $cinemaCityCrawler = new \Enjoy\CrawlerBundle\Crowlers\CinemaCity($this->container);

        $cinemaCityCrawler->saveMovies($facilityIds, $date);

        $idsString = implode(',', $facilityIds);
        $dateArray = preg_split("{-}", $date);
        $startDate = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0] . "-" . " 00:00:00";
        $endDate = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0] . "-" . " 23:59:59";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT m FROM EnjoyMainBundle:Movie m
                                    JOIN m.dates d
                                    JOIN d.facility f
                                    WHERE d.id IN(SELECT md.id FROM EnjoyMainBundle:MovieDate md WHERE f.id IN({$idsString}) AND md.date >= '{$startDate}' AND md.date <= '{$endDate}')");
        //var_dump($query->getSql());
        $movies = $query->getResult();

        return $this->render('EnjoyMainBundle:Default:show.html.twig', array('movies' => $movies));
    }

    public function indexAction(Request $request)
    {
        $facilityIds = array(1);
        $date = date("d/m/Y");

        $cinemaCityCrawler = new \Enjoy\CrawlerBundle\Crowlers\CinemaCity($this->container);

        $cinemaCityCrawler->saveMovies($facilityIds, $date);

        return $this->render('EnjoyMainBundle:Default:index.html.twig');

    }
}
