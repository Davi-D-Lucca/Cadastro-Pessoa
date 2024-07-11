<?php

class Pessoa
{
  private $pdo;
  public function __construct($dbname, $hostname, $usuario, $senha)
  {
    try {
      $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $hostname, $usuario, $senha);
    } catch (PDOException $e) {
      echo "Erro com banco de dados: " . $e->getMessage();
      exit();
    } catch (Exception $e) {
      echo "Erro generico: " . $e->getMessage();
      exit();
    }
  }

  //Buscar os dados e colocar no canto direito da tela
  public function buscarDados()
  {
    $res = array();
    $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  //Função para cadastro no Banco de Dados
  public function cadastrarPessoa($nome, $telefone, $email)
  {
    $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
    $cmd->bindValue(":e", $email);
    $cmd->execute();

    if ($cmd->rowCount() > 0) { //Email ja existe
      return false;
    } else { // Email não foi encontrado
      $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email) VALUES
      (:n,:t,:e)");
      $cmd->bindValue(":n", $nome);
      $cmd->bindValue(":t", $telefone);
      $cmd->bindValue(":e", $email);
      $cmd->execute();
      return true;
    }
  }
  // Função para excluir
  public function excluirPessoa($id)
  {
    $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
    $cmd->bindValue(":id", $id);
    $cmd->execute();
  }

  // Buscar dados de 1 pessoa
  public function buscarDadosPessoa($id)
  {
    $res = array();
    $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
    $cmd->bindValue(":id", $id);
    $cmd->execute();
    $res = $cmd->fetch(PDO::FETCH_ASSOC);
    return $res;
  }


  // Atualizar os dados

  public function atualizarDados($id, $nome, $telefone, $email)
  {
    $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
    $cmd->bindValue(":n", $nome);
    $cmd->bindValue(":t", $telefone);
    $cmd->bindValue(":e", $email);
    $cmd->bindValue(":id", $id);
    $cmd->execute();
  }
}
