<?php

namespace App\Service;

class Team{

    // m_namingConvention for private instance fields cannot really be followed due to the getters/setters 
    // which are used at runtime to render the html within the twig templates. 
    private $teamName;
    private $teamWins;
    private $teamLosses;
    private $teamGamesPlayed;
    private $gamesBack;
    private $teamList;
    private $mlbTeams;

    function __construct($teamName, $teamWins, $teamLosses, $teamGamesPlayed, $teamList)
    {
        $this->teamName = $teamName;
        $this->teamWins = $teamWins;
        $this->teamLosses = $teamLosses;
        $this->teamGamesPlayed = $teamGamesPlayed;
        $this->gamesBack = 0;
        $this->teamList = $teamList;
        $this->mlbTeams = [];
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

    function setTeamList($teamList){
        $this->teamList = $teamList;
    }

    function getTeamList(){
        return $this->teamList;
    }

    function addMLBTeam($mlbTeam){
        array_push($this->mlbTeams, $mlbTeam);
    }

    function getMLBTeams(){
        return $this->mlbTeams;
    }

    function setMLBTeams($mlbTeamList){
        $this->mlbTeams = $mlbTeamList;
    }

    function __toString()
    {
        return ( $this->getTeamName() .' ' .$this->getTeamWins() .' '. $this->getTeamLosses() .' '. $this->getTeamGamesPlayed() .' '. $this->getGamesBack() . PHP_EOL);
    }

    
}


?>