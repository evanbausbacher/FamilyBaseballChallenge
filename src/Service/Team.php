<?php

namespace App\Service;

class Team{
    public $teamName;
    private $teamWins;
    private $teamLosses;
    private $teamGamesPlayed;
    private $gamesBack;

    function __construct($teamName, $teamWins, $teamLosses, $teamGamesPlayed)
    {
        $this->teamName = $teamName;
        $this->teamWins = $teamWins;
        $this->teamLosses = $teamLosses;
        $this->teamGamesPlayed = $teamGamesPlayed;
        $this->gamesBack = 0;
    }

    function setTeamWins($wins){
        $this->teamWins = $wins;
    }

    function setTeamLosses($losses){
        $this->teamLosses = $losses;
    }

    function setTeamGamesPlayed($gamesPlayed){
        $this->teamGamesPlayed = $gamesPlayed;
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

    function setGamesBack($gamesBack){
        $this->gamesBack = $gamesBack;
    }

    function getGamesBack(){
        return $this->gamesBack;
    }

    function __toString()
    {
        return ( $this->getTeamName() .' ' .$this->getTeamWins() .' '. $this->getTeamLosses() .' '. $this->getTeamGamesPlayed() .' '. $this->getGamesBack() . PHP_EOL);
    }

    
}


?>