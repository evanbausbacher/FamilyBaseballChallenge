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

    function getTeamName(){
        return $this->teamName;
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