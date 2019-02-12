<?php

include 'vendor/autoload.php';

use RestService\Server;

Server::create('/')
    ->addGetRoute('player/(.*)', function($pseudo){
      return json_decode( file_get_contents( './Data/players/' . strtolower($pseudo) . '.json'), true );
    })
    ->addGetRoute('players/(.*)', function($order){
      $data = json_decode( file_get_contents( './Data/players.json'), true );

      switch ($order) {
        case 'wins':
          uasort( $data, function( $a, $b ) {
            if ( $a['wins'] == $b['wins'] ) {
              return 0;
            }

            return ($a['wins'] < $b['wins']) ? 1 : -1;
          } );
          break;
        case 'matches':
          uasort( $data, function( $a, $b ) {
            if ( $a['matches'] == $b['matches'] ) {
              return 0;
            }

            return ($a['matches'] < $b['matches']) ? 1 : -1;
          } );
          break;
        case 'kd':
          uasort( $data, function( $a, $b ) {
            if ( $a['kd'] == $b['kd'] ) {
              return 0;
            }

            return ($a['kd'] < $b['kd']) ? 1 : -1;
          } );
          break;
        case 'kills':
          uasort( $data, function( $a, $b ) {
            if ( $a['kills'] == $b['kills'] ) {
              return 0;
            }

            return ($a['kills'] < $b['kills']) ? 1 : -1;
          } );
          break;
        case 'winrate':
          uasort( $data, function( $a, $b ) {
            if ( $a['winrate'] == $b['winrate'] ) {
              return 0;
            }

            return ($a['winrate'] < $b['winrate']) ? 1 : -1;
          } );
          break;
        case 'damagedeals':
          uasort( $data, function( $a, $b ) {
            if ( $a['damagedeals'] == $b['damagedeals'] ) {
              return 0;
            }

            return ($a['damagedeals'] < $b['damagedeals']) ? 1 : -1;
          } );
          break;
        default:
          uasort( $data, function( $a, $b ) {
            if ( $a['wins'] == $b['wins'] ) {
              return 0;
            }

            return ($a['wins'] < $b['wins']) ? 1 : -1;
          } );
          break;
      }
      return $data;
    })
    ->addGetRoute('teams', function(){
      return json_decode( file_get_contents( './Data/teams.json'), true );
    })
    ->addGetRoute('team/(.*)', function($id_team){
      return json_decode( file_get_contents( './Data/teams/' . $id_team . '.json'), true );
    })
    ->addPostRoute('update', function($pseudo) {
      return update($pseudo);
    })
    ->addGetRoute('update/(.*)', function($pseudo) {
      return update($pseudo);
    })
    ->addPostRoute('subscribe', function($email) {
      $sanitized_c = filter_var($email, FILTER_SANITIZE_EMAIL);
      if (filter_var($sanitized_c, FILTER_VALIDATE_EMAIL)) {

        $emails = json_decode(file_get_contents('./Data/emails/email.json'), true);

        $emails[] = $sanitized_c;

        $f = fopen('./Data/emails/email.json', 'w+');
        fputs($f, json_encode( $emails ));
        fclose($f);
      } else {
        return false;
      }

      return true;
    })
->run();

function update($pseudo) {
  $sanitize_pseudo = strtolower($pseudo);

  if (! file_exists('./FakeData/players/' . $sanitize_pseudo . '.json')) {
    return false;
  }

  $playerData = json_decode(file_get_contents('./FakeData/players/' . $sanitize_pseudo . '.json'), true);
  $f = fopen('./Data/players/' . $sanitize_pseudo . '.json', 'w+');
  fputs($f, json_encode( $playerData ));
  fclose($f);

  $topData = json_decode(file_get_contents('./Data/players.json'), true);

  if ( ! isset( $topData[ $sanitize_pseudo ] ) ) {
    $topData[ $sanitize_pseudo ] = array(
      "name" => '',
      "kd" => 0,
      "kills" => 0,
      "wins" => 0,
      "matches" => 0,
      "winrate" => 0,
      "damagedeals" => 0
    );
  };

  $topData[ $sanitize_pseudo ]["name"] = ucfirst($sanitize_pseudo);
  $topData[ $sanitize_pseudo ]["kd"] = $playerData['kd'];
  $topData[ $sanitize_pseudo ]["kills"] = $playerData['kills'];
  $topData[ $sanitize_pseudo ]["wins"] = $playerData['wins'];
  $topData[ $sanitize_pseudo ]["matches"] = $playerData['matches'];
  $topData[ $sanitize_pseudo ]["winrate"] = $playerData['winrate'];
  $topData[ $sanitize_pseudo ]["damagedeals"] = $playerData['damagedeals'];

  uasort( $topData, function( $a, $b ) {
    if ( $a['wins'] == $b['wins'] ) {
      return 0;
    }

    return ($a['wins'] < $b['wins']) ? 1 : -1;
  } );

  $f = fopen('./Data/players.json', 'w+');
  fputs($f, json_encode( $topData, JSON_FORCE_OBJECT ));
  fclose($f);

  return true;
}

function addToTheTop() {

}
