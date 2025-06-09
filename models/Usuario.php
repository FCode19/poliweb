<?php
class Usuario {
  public static function all() {
    $json = file_get_contents(__DIR__ . '/../storage/usuarios.json');
    return json_decode($json, true);
  }
}
