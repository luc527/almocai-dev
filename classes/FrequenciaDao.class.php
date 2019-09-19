<?php
require_once("autoload.php");

class FrequenciaDao {
  public static function Popula ($row) {
    $freq = new Frequencia;
    $freq->setCodigo($row['codigo']);
    $freq->setDescricao($row['descricao']);
    return $freq;
  }

  public static function SelectTodas () {
    $sql = "SELECT * FROM Frequencia";
    try {
      $bd = Conexao::conexao();
      $query = $bd->query($sql);
      $freqs = array();
      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($freqs, self::Popula($row) );
      }
    } catch (PDOException $e) {
      echo "<b>Erro (FrequenciaDao::SelectTodas): </b>".$e->getMessage();
    }
    return $freqs;
  }
}
?>