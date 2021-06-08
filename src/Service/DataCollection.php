<?php
namespace App\Service;

use Exception;
use Symfony\Component\Finder\Finder;


//include "Team.php";
//include "MLBTeam.php";

class DataCollection
{
    private $m_familyTeams;
    
    function __construct()
    {
        $this->m_familyTeams = array(
            new Team("Evan", 0, 0, 0,['Yankees', 'Indians', 'Rangers', 'Braves']),
            new Team("Jack", 0, 0, 0, ['Royals', 'Twins', 'Nationals', 'Phillies']), 
            new Team("Mom", 0, 0, 0, ['Giants', 'Dodgers', 'Rays', 'Cubs']),
            new Team("Dad", 0, 0, 0, ['Angels', 'Astros', 'Pirates', 'White Sox'])
        );

        $this->m_familyTeams = $this->createSortedFamilyStandings($this->getMLBDataJSON());
        // $this->m_familyTeams = $this->createSortedFamilyStandings($this->loadMLBDataJSON());
    }
    
    public function getStandings(){
        return $this->m_familyTeams;
    }

    private function getMLBDataJSON()
    {
        // Use cURL to access the api of current MLB standings and return a JSON encoded object with the data. 
        if (! function_exists ( 'curl_version' )) {
            exit ( "Enable cURL in PHP" );
        }
        
        $ch = curl_init ();
        $timeout = 0; // 100; // set to zero for no timeout
        $myHITurl = "https://erikberg.com/mlb/standings.json";
        
        curl_setopt ( $ch, CURLOPT_URL, $myHITurl );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        $userAgentString = 'evanbaus@gmail.com'; //$this->generateRandomUserAgentString(); 
        curl_setopt ( $ch, CURLOPT_USERAGENT, $userAgentString );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $file_contents = curl_exec ( $ch );
        if (curl_errno ( $ch )) {
            echo curl_error ( $ch );
            curl_close ( $ch );
            exit ();
        }
        curl_close ( $ch );
        // $file = fopen("updated_standings.json", "w");
        // fwrite($file, $file_contents);
        return json_decode($file_contents,true);
    }

    private function loadMLBDataJSON()
    {
        // temportary fix for reading json data from file system sunce API not working

        $file_contents = file_get_contents('C:\Users\evanb\Documents\ProtoLink\family_baseball\standings.json');
        
        return json_decode($file_contents, true);
    }

    private function createSortedFamilyStandings($jsonData){
        // added try/catch block with the possibility that the jsonData was null 
        // due to a failed API request, therefore this function should not execute. 
        try{
            $standings = $jsonData['standing'];
        }
        catch ( Exception ){
            return ['800']; // interior status code. 
        }
        

        foreach ($this->m_familyTeams as $familyTeam){
            $wins = 0;
            $losses = 0;
            $gamesPlayed = 0;
            
            for ($standingIndex = 0; $standingIndex < sizeof($jsonData['standing']); $standingIndex++) 
            {
                
                if (in_array($standings[$standingIndex]['last_name'], $familyTeam->getTeamList()))
                {
                    // team belongs to a member of the family. accumulate the stats
                    $wins += $standings[$standingIndex]['won'];
                    $losses += $standings[$standingIndex]['lost'];
                    $gamesPlayed += $standings[$standingIndex]['games_played'];
                    $tierLevel = $this->findTierLevel($standings[$standingIndex]['last_name']);
                    
                    $mlbTeam = new MLBTeam($standings[$standingIndex]['last_name'], $standings[$standingIndex]['won'], 
                        $standings[$standingIndex]['lost'], $standings[$standingIndex]['games_played'], $tierLevel, 
                        $standings[$standingIndex]['last_ten'], $standings[$standingIndex]['first_name'], $standings[$standingIndex]['streak'], 
                        $standings[$standingIndex]['point_differential']);
                    $familyTeam->addMLBTeam($mlbTeam);
                }
            
            }
            $familyTeam->setTeamWins($wins);
            $familyTeam->setTeamLosses($losses);
            $familyTeam->setTeamGamesPlayed($gamesPlayed);

            // cannot pass by reference to sorted function so having to get and then set after sorting. 
            $mlbTeamList = $familyTeam->getMLBTeams();
            $familyTeam->setMLBTeams($this->sortTeamsByWins($mlbTeamList));
            
            // dump($familyTeam);
        } 
        
        // sorting overall standings
        $this->m_familyTeams = $this->sortTeamsByWins($this->m_familyTeams);
        
        // games back based on first place, convention to set first place GB to '-'
        $this->m_familyTeams[0]->setGamesBack('-');
        
        // compute games back now that standings are sorted by wins and then by losses
        for ($team_id = 1; $team_id < sizeof($this->m_familyTeams); $team_id++){
            $first_wins = $this->m_familyTeams[0]->getTeamWins();
            $first_losses = $this->m_familyTeams[0]->getTeamLosses();
        
            $team_wins = $this->m_familyTeams[$team_id]->getTeamWins();
            $team_losses = $this->m_familyTeams[$team_id]->getTeamLosses();
        
            $gamesBack = (abs($first_wins - $team_wins) + abs($first_losses - $team_losses)) / 2;
            $this->m_familyTeams[$team_id]->setGamesBack($gamesBack);
        }

        return $this->m_familyTeams; // array of team objects sorted in order. 
    }

    // finds family member team object and returns
    public function returnTeamObject($teamName)
    {
        foreach ($this->m_familyTeams as $team){
            if ($team->getTeamName() == $teamName){
                return $team;
            }
        }
    }

    private function sortTeamsByWins($teamList){
        
        usort($teamList, function (Team $teamA, Team $teamB){
            // sort by wins then by losses
            if ($teamA->getTeamWins() == $teamB->getTeamWins()){

                return $teamA->getTeamLosses() > $teamB->getTeamLosses();

            }

            return $teamA->getTeamWins() < $teamB->getTeamWins();
        });
        
        return $teamList;
    }

    private function generateRandomUserAgentString($length=10){
        $stringBase = 'MLBStatGuru@';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $stringBase .= $randomString;
    }

    private function findTierLevel($teamName){
        $tier1 = ['Dodgers', 'Yankees', 'Astros', 'Twins'];
        $tier2 = ['White Sox', 'Braves', 'Rays', 'Nationals'];
        $tier3 = ['Phillies', 'Cubs', 'Angels', 'Indians'];
        $tier4 = ['Rangers', 'Royals', 'Giants', 'Pirates'];

        if (in_array($teamName, $tier1)) return 1;
        if (in_array($teamName, $tier2)) return 2;
        if (in_array($teamName, $tier3)) return 3;
        if (in_array($teamName, $tier4)) return 4;

        
    }
}


