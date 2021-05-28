<?php
namespace App\Controller;

use App\Service\DataCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StandingsController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage() 
    {
        $data = new DataCollection();
        $teams = $data->createSortedFamilyStandings($data->getMLBDataJSON());

        // dump($teams);

        return $this->render('homepage.html.twig', [
            'teams' => $teams
        ]);

    }
}