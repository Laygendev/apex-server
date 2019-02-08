<?php

include 'vendor/autoload.php';

use RestService\Server;

Server::create('/')
    ->addGetRoute('players', function(){
        return array(
            array(
                'name' => 'Ninja',
                'kd' => 10.79,
                'kills' => 98680,
                'wins' => 5162,
                'matches' => 14309,
                'winrate' => 36.08,
                'score' => 5506866,
            )
        );
    })
    ->addPostRoute('update', function($pseudo) {
      return $pseudo;
    })
->run();