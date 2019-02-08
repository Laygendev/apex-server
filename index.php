<?php

include 'vendor/autoload.php';

use RestService\Server;

Server::create('/')
    ->addGetRoute('players', function(){
      return json_decode( file_get_contents( './Data/players.json'), true );
    })
    ->addPostRoute('update', function($pseudo) {
      $content = json_decode( file_get_contents( './Data/players.json'), true );

      if ( ! isset( $content[ $pseudo ] ) ) {
        $content[ $pseudo ] = array(
          "name" => $pseudo,
          "kd" => 10.79,
          "kills" => 98680,
          "wins" => 5162,
          "matches" => 14309,
          "winrate" => 36.08,
          "score" => 5506866
        );
      }

      $f = fopen('./Data/players.json', 'w+');

      fputs($f, json_encode( $content )); // On écrit le nouveau nombre de pages vues

      fclose($f);

      return true;
    })
->run();
