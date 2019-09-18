<?php
require_once("Alimentacao.class.php");
require_once("Conexao.class.php");

class AlimentacaoDao {
  public static function Popula ($row) {
    $alim = new Alimentacao;
    $alim->setCodigo( $row['codigo'] );
    $alim->setDescricao( $row['descricao'] );
    return $alim;
  }

  public static function SelectTodas () {
    $sql = "SELECT * FROM Alimentacao";
    try {
      $bd = Conexao::conexao();
      $query = $bd->query($sql);
      $alims = array();
      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($alims, self::Popula($row) );
      }
      return $alims;
    } catch (PDOException $e) {
      echo "<b>Erro (AlimentacaoDao::SelectTodas): </b>".$e->getMessage();
    }
  }
}
?>