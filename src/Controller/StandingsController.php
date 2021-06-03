<?php
namespace App\Controller;

use App\Service\DataCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Serializer\SerializerInterface;

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
        
        $teams = $this->data->getStandings();

        if (strcmp($teams[0], "800") == 0) {
            return new Response("Error acquiring data from source. Contact evanbaus@gmail.com");
        }

        return $this->render('homepage.html.twig', [
            'teams' => $teams
        ]);

    }

    /**
     * @Route("/team/{teamName}", name="team_page")
     */
    public function teampage($teamName)
    {
        $teamList = $this->data->returnTeamObject($teamName);

        dump($teamList);

        return $this->render('teampage.html.twig', [
            'name' => $teamName,
            'teamList' => $teamList
        ]);
    }

    /**
     * @Route("/expand/{teamName}", name="expand_team")
     * @Method("EXPAND")
     */
    public function expandTeamStats($teamName)
    {
        $teamToExpand = $this->data->returnTeamObject($teamName);
        // dump($teamToExpand); // cannot see on page. only within dev tools > network > previews
        // dump($teamToExpand->getTeamList());
        // return $this->returnJSONResponse($teamToExpand);
        //return new Response("teamToExpand", 204, [], $teamToExpand);
        // return new Response(null, 204);
        $json_data = array();
        $idx = 0;
        foreach($teamToExpand->getMLBTeams() as $mlbTeam){
            $json_temp = array(
                'name' => $mlbTeam->getTeamName(),
                'wins' => $mlbTeam->getTeamWins(),
                'losses' => $mlbTeam->getTeamLosses(),
                'gamesPlayed' => $mlbTeam->getTeamGamesPlayed(),
                'tier' => $mlbTeam->getTierLevel()
            );
            $json_data[$idx++] = $json_temp;
        }
        return new JsonResponse($json_data);
    }

    protected function returnJSONResponse($data, $statusCode = 200){
        $json = $this->get('serializer')->serialize($data, 'Symfony\Component\Serializer\Encoder\JsonEncoder');
        return new JsonResponse($json,$statusCode,[], true);
    }
}