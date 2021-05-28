<?php
namespace App\Service;
include "Team.php";


class DataCollection
{
    function getMLBDataJSON(){
        if (! function_exists ( 'curl_version' )) {
            exit ( "Enable cURL in PHP" );
        }
        
        $ch = curl_init ();
        $timeout = 0; // 100; // set to zero for no timeout
        $myHITurl = "https://erikberg.com/mlb/standings.json";
        
        //$f = fopen("mlb_standings.json","w");
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
        //fwrite($f, $file_contents);
        curl_close ( $ch );
        //fclose($f);
        return json_decode($file_contents,true);
    }

    function createSortedFamilyStandings($json_data){
        $teamNames = array(
            "Evan", "Jack", "Mom", "Dad"
        );
        
        $Evan_team_list = ['Yankees', 'Indians', 'Rangers', 'Braves'];
        $Jack_team_list = ['Royals', 'Twins', 'Nationals', 'Phillies'];
        $Mom_team_list = ['Giants', 'Dodgers', 'Rays', 'Cubs'];
        $Dad_team_list = ['Angels', 'Astros', 'Pirates', 'White Sox'];
        
        $family_teams = [$Evan_team_list, $Jack_team_list, $Mom_team_list, $Dad_team_list];
        
        $teams = [];
        $standings = $json_data['standing'];
        for ($family_id = 0; $family_id < sizeof($family_teams); $family_id++) {
            $wins = 0;
            $losses = 0;
            $gamesPlayed = 0;
            for ($standing_index = 0; $standing_index < sizeof($json_data['standing']); $standing_index++) {
                if (in_array($standings[$standing_index]['last_name'], $family_teams[$family_id])) {
                    // printf($standings[$standing_index]['last_name'] . '-' . $standings[$standing_index]['won'] . PHP_EOL);
                    $wins += $standings[$standing_index]['won'];
                    $losses += $standings[$standing_index]['lost'];
                    $gamesPlayed += $standings[$standing_index]['games_played'];
                }
            }
            $team = new Team($teamNames[$family_id], $wins, $losses, $gamesPlayed);
            // print $team;
            array_push($teams, $team);
        }
        
        
        // function cmp(Team $teamA, Team $teamB){
        //     if ($teamA->getTeamWins() == $teamB->getTeamWins()){
        //         return $teamA->getTeamLosses() < $teamB->getTeamLosses();
        //     }
        //     return $teamA->getTeamWins() < $teamB->getTeamWins();
        // }
        
        // foreach ($teams as $team){
        //     echo $team;
        // }
        // echo "sorting\n";
        
        // usort($teams, "cmp");
        usort($teams, function (Team $teamA, Team $teamB){
            if ($teamA->getTeamWins() == $teamB->getTeamWins()){
                return $teamA->getTeamLosses() < $teamB->getTeamLosses();
            }
            return $teamA->getTeamWins() < $teamB->getTeamWins();
        });
        $teams[0]->setGamesBack('-');
        
        
        for ($team_id = 1; $team_id < sizeof($teams); $team_id++){
            $first_wins = $teams[0]->getTeamWins();
            $first_losses = $teams[0]->getTeamLosses();
        
            $team_wins = $teams[$team_id]->getTeamWins();
            $team_losses = $teams[$team_id]->getTeamLosses();
        
            $gamesBack = (abs($first_wins - $team_wins) + abs($first_losses - $team_losses)) / 2;
            // printf($teams[$team_id]->getTeamName() . ' ' . $gamesBack);
            $teams[$team_id]->setGamesBack($gamesBack);
        }

        return $teams;
    }
}


