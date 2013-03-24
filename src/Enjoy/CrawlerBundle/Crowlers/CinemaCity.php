<?php

namespace Enjoy\CrawlerBundle\Crowlers;

use Enjoy\MainBundle\Entity\Crowled;

class CinemaCity
{
    /**
     * @var \Enjoy\MainBundle\Entity\Movie $movie
     */
    protected $movie;
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function saveMovies($facilityIds, $date)
    {
        $date = preg_replace("{-}", "/", $date);
        $crowledDate = preg_replace("{/}", "-", $date) . " 00:00:00";

        $inStatment = implode(',', $facilityIds);
        $conn = $this->container->get('database_connection');
        $sql = "SELECT facility.id FROM facility
                WHERE id IN({$inStatment})
                AND id NOT IN(SELECT facility_id FROM crowled WHERE DATE_FORMAT(crowled.date, '%d/%m/%Y') = '{$date}')
                ";



        $rows = $conn->query($sql)->fetchAll();

        $facilityIds = array();
        $facilities = array();
        if (count($rows) > 0)
        {
            foreach($rows as $item)
            {
                $facilityIds[] = $item['id'];
            }

            $repository = $this->container->get('doctrine')
                ->getRepository('EnjoyMainBundle:Facility');

            $facilities = $repository->findBy(
                array('id' => $facilityIds)
            );
        }


        foreach($facilities as $facility)
        {

            $this->crowl($facility, $date);

            $crowled = new Crowled();
            $crowled->setFacility($facility);
            $crowled->setDate(new \DateTime($crowledDate));

            $em = $this->container->get('doctrine')->getManager();
            $em->persist($crowled);
            $em->flush();

        }
    }

    public function crowl($facility, $date)
    {
        $html = $this->getHtmlPage($facility, $date);
        $this->handleMovies($html,$facility, $date);
    }

    public function getHtmlPage($facility, $date)
    {
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, "http://www.cinema-city.pl/scheduleInfoRows");

        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
        curl_setopt($tuCurl, CURLOPT_HEADER, 0);

        curl_setopt($tuCurl, CURLOPT_POST, 1);

        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($tuCurl, CURLOPT_POSTFIELDS, "locationId={$facility->getcrowlId()}&date={$date}&venueTypeId=0");

        curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);

        $tuData = curl_exec($tuCurl);
        curl_close($tuCurl);
        //var_dump($tuData);

        return $tuData;
    }

    public function handleMovies($html,$facility, $date)
    {
        $shedule = \phpQuery::newDocument($html);

        $rows = $shedule->find("tbody tr");

        foreach($rows as $row)
        {
            $name = pq($row)->find("td.featureName a")->text();
            $dType = 0;
            if (strstr($name, "3D"))
            {
                $name = trim(preg_replace("/3D/","",$name));
                $dType = 1;
            }

            $this->movie = $this->container->get("doctrine")
                          ->getRepository('EnjoyMainBundle:Movie')
                          ->findOneByName($name);

            if (null === $this->movie)
            {
                $this->movie = new \Enjoy\MainBundle\Entity\Movie();
                $this->movie->setName(trim($name));

                $featureCode = pq($row)->find("td.featureName a")->attr("data-feature_code");

                $html = $this->_movieInfo($featureCode);

                $this->_handleMovieInfo($html);
            }

            $lange = pq($row)->find(":nth-child(3)")->text();
            $lType = 0;
            if ($lange)
            {
                switch($lange)
                {
                    case "NAP": $lType = 1;
                            break;

                    case "DUB": $lType = 2;
                        break;
                }
            }

            $times = pq($row)->find(".presentationLink")->not(".expired");
            $movieDates = array();

            foreach($times as $time)
            {
                $tmpDate = $date;

                $movieDate = new \Enjoy\MainBundle\Entity\MovieDate();

                $tmpDate = preg_replace("{/}", "-", $tmpDate) . " " . trim($time->textContent) . ":00";

                $movieDate->setDate(new \DateTime($tmpDate));

                $this->movie->addDate($movieDate);
                $movieDate->setMovie($this->movie);
                $movieDate->setFacility($facility);
                $movieDate->setDType($dType);
                $movieDate->setLType($lType);
            }
            $em =$this->container->get('doctrine')->getManager();
            $em->persist($this->movie);
            $em->flush();
        }

        return true;
    }

    private function _movieInfo($featureCode)
    {
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, "http://www.cinema-city.pl/featureInfo?featureCode=".$featureCode."&isLocal=false&groupByDistributorCode=true");

        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
        curl_setopt($tuCurl, CURLOPT_REFERER, "www.cinema-city.pl");
        curl_setopt($tuCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
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

        $tidy = new \tidy();
        //$data = $tidy->repairString($data);
        //var_dump($data);
        /*var_dump($clean);
        echo "<hr>";
        $data = strstr($data, "<div");

        $data = "<html><body>" . $data . "</body></html>";*/

        $doc = \phpQuery::newDocument($data);

        $img = $doc->find(".feature_info_media img")->attr("src");
        if ($img)
        {
            $this->movie->setImgUrl($img);
        }

        $description = pq($doc)->find(".feature_info_synopsis p")->text();

        if ($description)
        {
            $this->movie->setDescription($description);
        }

        $featureInfos = $doc->find(".feature_info div.feature_info_row");
        $fields = array();
        foreach($featureInfos as $featureInfo)
        {
            $key = preg_replace("{:}", "", pq($featureInfo)->find(".pre_label")->text());
            $value = trim(pq($featureInfo)->find(".white")->text());
            $fields[$key] = $value;
        }

        if (count($fields) > 0)
        {
            $this->movie->setOriginalName($fields['Oryginalny tytuł']);
            $this->movie->setLength($fields['Długość']);
            $this->movie->setDirection($fields['Reżyseria']);
            $this->movie->setCast($fields['Obsada']);
        }
    }
}