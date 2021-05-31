<?php
namespace App\Service;
include "Team.php";
include "MLBTeam.php";

class DataCollection
{

    // private $familyMLB = array(
    //     "Evan" => ['Yankees', 'Indians', 'Rangers', 'Braves'],
    //     "Jack" => ['Royals', 'Twins', 'Nationals', 'Phillies'],
    //     "Mom" => ['Giants', 'Dodgers', 'Rays', 'Cubs'],
    //     "Dad" => ['Angels', 'Astros', 'Pirates', 'White Sox']
    // );
    private $familyTeam;
    
    function __construct()
    {
        $this->familyTeams = array(
            new Team("Evan", 0, 0, 0,['Yankees', 'Indians', 'Rangers', 'Braves']),
            new Team("Jack", 0, 0, 0, ['Royals', 'Twins', 'Nationals', 'Phillies']), 
            new Team("Mom", 0, 0, 0, ['Giants', 'Dodgers', 'Rays', 'Cubs']),
            new Team("Dad", 0, 0, 0, ['Angels', 'Astros', 'Pirates', 'White Sox'])
        );
    }
    


    function getMLBDataJSON(){
        if (! function_exists ( 'curl_version' )) {
            exit ( "Enable cURL in PHP" );
        }
        
        $ch = curl_init ();
        $timeout = 0; // 100; // set to zero for no timeout
        $myHITurl = "https://erikberg.com/mlb/standings.json";
        
        curl_setopt ( $ch, CURLOPT_URL, $myHITurl );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_USERAGENT, "EvanBausbacherStudentUT" );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $file_contents = curl_exec ( $ch );
        if (curl_errno ( $ch )) {
            echo curl_error ( $ch );
            curl_close ( $ch );
            exit ();
        }
        curl_close ( $ch );
        return json_decode($file_contents,true);
    }

    function createSortedFamilyStandings($json_data){
        
        $standings = $json_data['standing'];

        foreach ($this->familyTeams as $familyTeam){
            $wins = 0;
            $losses = 0;
            $gamesPlayed = 0;
            for ($standing_index = 0; $standing_index < sizeof($json_data['standing']); $standing_index++) 
            {
                if (in_array($standings[$standing_index]['last_name'], $familyTeam->getTeamList()))
                {
                    // team belongs to a member of the family. accumulate the stats
                    $wins += $standings[$standing_index]['won'];
                    $losses += $standings[$standing_index]['lost'];
                    $gamesPlayed += $standings[$standing_index]['games_played'];

                    $mlbTeam = new MLBTeam($standings[$standing_index]['last_name'], $standings[$standing_index]['won'], $standings[$standing_index]['lost'], $standings[$standing_index]['games_played']);
                    $familyTeam->addMLBTeam($mlbTeam);
                }
            }
            $familyTeam->setTeamWins($wins);
            $familyTeam->setTeamLosses($losses);
            $familyTeam->setTeamGamesPlayed($gamesPlayed);

            // dump($familyTeam);
        } 
                
        usort($this->familyTeams, function (Team $teamA, Team $teamB){
            // sort by wins then by losses
            if ($teamA->getTeamWins() == $teamB->getTeamWins()){

                return $teamA->getTeamLosses() < $teamB->getTeamLosses();

            }

            return $teamA->getTeamWins() < $teamB->getTeamWins();
        });

        $this->familyTeams[0]->setGamesBack('-');
        
        // compute games back now that standings are sorted by wins and then by losses
        for ($team_id = 1; $team_id < sizeof($this->familyTeams); $team_id++){
            $first_wins = $this->familyTeams[0]->getTeamWins();
            $first_losses = $this->familyTeams[0]->getTeamLosses();
        
            $team_wins = $this->familyTeams[$team_id]->getTeamWins();
            $team_losses = $this->familyTeams[$team_id]->getTeamLosses();
        
            $gamesBack = (abs($first_wins - $team_wins) + abs($first_losses - $team_losses)) / 2;
            $this->familyTeams[$team_id]->setGamesBack($gamesBack);
        }

        return $this->familyTeams; // array of team objects sorted in order. 
    }

    function returnTeamList($teamName)
    {
        return $this->familyMLB[$teamName];
    }
}


