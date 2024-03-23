<?php

namespace RegistroDePonto\Controllers;

require "./src/Controllers/RegistroDePontoController.php";
require "./src/External/MySqlConnection.php";

use RegistroDePonto\Controllers\RegistroDePontoController;
use RegistroDePonto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;

class RegistroDePontoControllerTest extends TestCase
{
    protected $registroDePontoController;
    protected $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->registroDePontoController = new RegistroDePontoController();
        $this->dbConnection = new MySqlConnection();
    }

    public function testCadastrarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111);

        $idRegistroDePonto = $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $registroDePontoExcluido = $this->registroDePontoController->excluir($this->dbConnection, $idRegistroDePonto);
        $this->assertTrue($registroDePontoExcluido);
    }

    public function testCadastrarRegistroDePontoComTipoDeRegistroJaExistente()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111);

        $idRegistroDePonto = $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        try {
            $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("Já existe um registro de ponto não finalizado com este tipo: inicio_expediente. Encerre-o antes de iniciar um novo registro.", $e->getMessage());
            $this->assertEquals(409, $e->getCode());
            $registroDePontoExcluido = $this->registroDePontoController->excluir($this->dbConnection, $idRegistroDePonto);
            $this->assertTrue($registroDePontoExcluido);
        }
    }

    public function testCadastrarRegistroDePontoComCamposFaltando()
    {
        $dadosRegistroDePonto = array('cpf' => '42157363823', 'tipo' => '', 'senha' => 'Postech@42157363823');

        try {
            $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo tipo é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testAtualizarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('cpf' => '42157363823', 'tipo' => 'inicio_expediente', 'id_colaborador' => 111);

        $idRegistroDePonto = $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $dadosRegistroDePonto = array('cpf' => '42157363823', 'tipo' => 'termino_expediente', 'id_colaborador' => 111);

        $resultado = $this->registroDePontoController->atualizar($this->dbConnection, $dadosRegistroDePonto);
        $this->assertTrue($resultado);

        $registroDePontoExcluido = $this->registroDePontoController->excluir($this->dbConnection, $idRegistroDePonto);
        $this->assertTrue($registroDePontoExcluido);
    }

    public function testAtualizarRegistroDePontoNaoExistente()
    {
        $dadosRegistroDePonto = array('cpf' => '42157363823', 'tipo' => 'termino_expediente', 'senha' => 'Postech@42157363823', 'id_colaborador' => 111);

        try {
            $this->registroDePontoController->atualizar($this->dbConnection, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um registro de ponto pendente de finalização.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testExcluirRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('cpf' => '42157363823', 'tipo' => 'inicio_expediente', 'senha' => 'Postech@42157363823', 'id_colaborador' => 111);

        $idRegistroDePonto = $this->registroDePontoController->cadastrar($this->dbConnection, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $registroDePontoExcluido = $this->registroDePontoController->excluir($this->dbConnection, $idRegistroDePonto);
        $this->assertTrue($registroDePontoExcluido);
    }
    public function testExcluirRegistroDePontoNaoExistente()
    {
        try {
            $this->registroDePontoController->excluir($this->dbConnection, 999999999999999999);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um registro de ponto com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
