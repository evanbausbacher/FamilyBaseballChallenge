<?php
namespace App\Controller;

use App\Service\DataCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StandingsController extends AbstractController
{

    private $data;

    function __construct()
    {
        $this->data = new DataCollection();
    }
    
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage() 
    {
        
        $teams = $this->data->createSortedFamilyStandings($this->data->getMLBDataJSON());

        // dump($teams);

        return $this->render('homepage.html.twig', [
            'teams' => $teams
        ]);

    }

    /**
     * @Route("/team/{teamName}", name="team_page")
     */
    public function teampage($teamName)
    {
        $teamList = $this->data->returnTeamList($teamName);

        dump($teamList);

        return $this->render('teampage.html.twig', [
            'name' => $teamName,
            'teamList' => $teamList
        ]);
    }
}