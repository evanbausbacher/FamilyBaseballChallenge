<?php

namespace App\Service;

class MLBTeam
{
    private $teamName;
    private $teamWins;
    private $teamLosses; 
    private $teamGamesPlayed;

    function __construct($teamName, $teamWins, $teamLosses, $teamGamesPlayed)
    {
        $this->teamName = $teamName;
        $this->teamWins = $teamWins;
        $this->teamLosses = $teamLosses;
        $this->teamGamesPlayed = $teamGamesPlayed;
    }

    function getTeamInfo(){
        $teamInfo = [$this->teamName, $this->teamWins, $this->teamLosses, $this->teamGamesPlayed];
        return $teamInfo;
    }
}