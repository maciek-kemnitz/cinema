<?php

namespace Enjoy\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function show()
    {
        $movies = $this->getDoctrine()
                    ->getRepository('EnjoyMainBundle:Movie')
                    ->findAll();

        return $this->render('EnjoyMainBundle:Default:show.html.twig', array('movies' => $movies));
    }

    public function indexAction($name)
    {
        $date = date("d m Y");
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, "http://www.cinema-city.pl/scheduleInfoRows");

        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
        curl_setopt($tuCurl, CURLOPT_HEADER, 0);

        curl_setopt($tuCurl, CURLOPT_POST, 1);

        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($tuCurl, CURLOPT_POSTFIELDS, "locationId=1010306&date=11%2F03%2F2013&venueTypeId=0");

        curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);

        $tuData = curl_exec($tuCurl);
        curl_close($tuCurl);

        $response = $tuData;

        $shedule = \phpQuery::newDocument($response);

        $rows = $shedule->find("tbody tr");

        foreach($rows as $row)
        {
            $featureCode = pq($row)->find("td.featureName a")->attr("data-feature_code");

            $html = $this->_movieInfo($featureCode);

            $this->_handleMovieInfo($html);

            $date = date("d m Y");

            //var_dump($response);

            $name = pq($row)->find("td.featureName a")->text();
            echo "<p>".$name."</p>";

            $times = pq($row)->find(".presentationLink")->not(".expired");
            $movieDates = array();
            $movie = new \Enjoy\MainBundle\Entity\Movie();
            $movie->setName(trim($name));

            foreach($times as $time)
            {
                $tmpDate = $date;

                $movieDate = new \Enjoy\MainBundle\Entity\MovieDate();
                $tmpDate = preg_replace("[\s]", "-", $tmpDate) . " " . trim($time->textContent) . ":00";
                $movieDate->setDate(new \DateTime($tmpDate));

                $movie->addDate($movieDate);
                $movieDate->setMovie($movie);


            }
#            $em = $this->getDoctrine()->getManager();
#            $em->persist($movie);
#            $em->flush();
            exit();
        }


      /*  $doc = \phpQuery::newDocument($response);
        $names = $doc->find("td.featureName ");
        foreach($names as $name)
        {
            try
            {
                echo "<p>". pq($name)->find("a")->text()."</p>";
            }
            catch(\Exception $e)
            {
            }
        }*/

        return $this->render('EnjoyMainBundle:Default:index.html.twig', array('name' => $name));
    }

    private function _movieInfo($featureCode)
    {
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, "http://www.cinema-city.pl/featureInfo?featureCode=".$featureCode);
        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
        curl_setopt($tuCurl, CURLOPT_HEADER, 0);
        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
        curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);

        $tuData = curl_exec($tuCurl);
        curl_close($tuCurl);

        return $tuData;
    }

    private function _handleMovieInfo($data)
    {
        var_dump($data);
        $dupa = \phpQuery::newDocument($data);
        #\phpQuery::$debug = 1;
        echo "<hr>";
        var_dump($dupa->empty());
        echo "<hr>";
        $img = $dupa->find("div");
        var_dump($img);
        $imgSrc = pq($img)->attr("src");
        var_dump($imgSrc);
        echo $imgSrc;
    }
}
