<?php

namespace Enjoy\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Enjoy\MainBundle\Entity\Crowled;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @var \Enjoy\MainBundle\Entity\Movie $movie
     */
    protected $movie;
    public function showAction($date)
    {
        $session = $this->getRequest()->getSession();
        $session->start();

        $request = $this->get('request');
        if ($request->getMethod() == 'POST')
        {
            $post = $request->request->get('form');



            unset($post['_token']);
            $facilityIds = array_keys($post);
            $session->set('last_facility_ids', $facilityIds);


        }
        else
        {
            $facilityIds = $session->get('last_facility_ids');
        }



        $cinemaCityCrawler = new \Enjoy\CrawlerBundle\Crowlers\CinemaCity($this->container);

        $cinemaCityCrawler->saveMovies($facilityIds, $date);

        $idsString = implode(',', $facilityIds);
        $dateArray = preg_split("{-}", $date);
        $startDate = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0] . "-" . " 00:00:00";
        $endDate = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0] . "-" . " 23:59:59";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT m, d FROM EnjoyMainBundle:Movie m
                                    JOIN m.dates d
                                    WHERE d.id IN(
                                      SELECT md.id FROM EnjoyMainBundle:MovieDate md
                                      JOIN md.facility f
                                      WHERE f.id IN({$idsString}) AND md.date >= '{$startDate}' AND md.date <= '{$endDate}'
                                    )");

        $movies = $query->getResult();

        $struct = array();
        $conn = $this->container->get('database_connection');

        foreach($movies as $movie)
        {
            $tmpArray = array();

            $sql = "
                SELECT f.locationName as 'name', f.id as 'id' FROM `movie_date`
                INNER JOIN facility f ON f.id = movie_date.facility_id
                WHERE facility_id IN({$idsString})
                AND movie_id = {$movie->getId()}
                AND `date` >= '{$startDate}' AND `date` <= '{$endDate}'
                GROUP BY facility_id HAVING COUNT(movie_date.id) > 0
            ";
            $rows = $conn->query($sql)->fetchAll();

            foreach($rows as $row)
            {
                $sql = "
                    SELECT `date`, d_type as 'd', l_type as 'l' FROM `movie_date`
                    WHERE facility_id = {$row['id']}
                    AND movie_id = {$movie->getId()}
                    AND `date` >= '{$startDate}' AND `date` <= '{$endDate}'
                    ORDER BY `date` ASC
                ";
                $subRows = $conn->query($sql)->fetchAll();
                $tmpArray = array();
                $tmpArray['name'] = $row['name'];
                foreach($subRows as $subRow)
                {
                    $tmpArray['dates'][] = array('date' => $subRow['date'], 'dType' => $subRow['d'], 'lType' => $subRow['l']);
                }

                $struct[$movie->getId()][$row['id']] = $tmpArray;
            }
        }


        $days = array();
        for($i=0; $i<6;$i++)
        {
            $day = new \DateTime('now');
            $days[] = $day->add(new \DateInterval('P'.$i.'D'));
        }

        return $this->render('EnjoyMainBundle:Default:show.html.twig', array('movies' => $movies, 'days' => $days, 'struct' => $struct, 'currentDay' => $date));
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
