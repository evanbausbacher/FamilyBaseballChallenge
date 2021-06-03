<?php

namespace App\Service;

class MLBTeam extends Team
{
    private $teamName;
    private $teamWins;
    private $teamLosses; 
    private $teamGamesPlayed;

    private $lastTen;
    private $location;
    private $streak;
    private $pointDifferential;

    private $teamLowerCase;

    private $tierLevel;

    function __construct($teamName, $teamWins, $teamLosses, $teamGamesPlayed, $tierLevel, $lastTen, $location, $streak, $pointDifferential)
    {
        $this->teamName = $teamName;
        $this->teamWins = $teamWins;
        $this->teamLosses = $teamLosses;
        $this->teamGamesPlayed = $teamGamesPlayed;
        $this->tierLevel = $tierLevel;
        $this->lastTen =  $lastTen;
        $this->location =  $location;
        $this->streak =  $streak;
        $this->pointDifferential = $pointDifferential;
        $this->teamLowerCase = strtolower($this->teamName);
        $this->plusMinus = ($this->pointDifferential > 0) ? 'pos' : 'neg';
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

    function getLocation(){
        return $this->location;
    }

    function getLastTen(){
        return $this->lastTen;
    }

    function getStreak(){
        return $this->streak;
    }

    function getPointDifferential(){
        return $this->pointDifferential;
    }
    function getTeamLowerCase(){
        return $this->teamLowerCase;
    }

    function getTeamInfo(){
        $teamInfo = [$this->teamName, $this->teamWins, $this->teamLosses, $this->teamGamesPlayed];
        return $teamInfo;
    }
}