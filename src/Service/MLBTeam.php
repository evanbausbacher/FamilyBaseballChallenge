<?php

namespace App\Service;

class MLBTeam extends Team
{
    private $teamName;
    private $teamWins;
    private $teamLosses; 
    private $teamGamesPlayed;
    private $tierLevel;

    function __construct($teamName, $teamWins, $teamLosses, $teamGamesPlayed, $tierLevel)
    {
        $this->teamName = $teamName;
        $this->teamWins = $teamWins;
        $this->teamLosses = $teamLosses;
        $this->teamGamesPlayed = $teamGamesPlayed;
        $this->tierLevel = $tierLevel;
    }

    function getTeamName(){
        return $this->teamName;
    }

    function getTierLevel(){
        return $this->tierLevel;
    }

    function getTeamWins(){
        return $this->teamWins;
    }
    function getTeamLosses(){
        return $this->teamLosses;
    }
    function getTeamGamesPlayed(){
        return $this->teamGamesPlayed;
    }

    function getTeamInfo(){
        $teamInfo = [$this->teamName, $this->teamWins, $this->teamLosses, $this->teamGamesPlayed];
        return $teamInfo;
    }
}