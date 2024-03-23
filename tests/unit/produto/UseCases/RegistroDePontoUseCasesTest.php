<?php

use RegistroDePonto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use RegistroDePonto\Gateways\RegistroDePontoGateway;
use RegistroDePonto\UseCases\RegistroDePontoUseCases;

class RegistroDePontoUseCasesTest extends TestCase
{
    private $dbConnection;
    private $RegistroDePontoGateway;
    private $RegistroDePontoUseCases;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = new MySqlConnection;
        $this->RegistroDePontoGateway = new RegistroDePontoGateway($this->dbConnection);
        $this->RegistroDePontoUseCases = new RegistroDePontoUseCases();
    }
    public function testCadastrarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111);
        $idRegistroDePonto = $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);
        $registroDePontoExcluido = $this->RegistroDePontoUseCases->excluir($this->RegistroDePontoGateway, $idRegistroDePonto);
        $this->assertTrue($registroDePontoExcluido);
    }

    public function testCadastrarRegistroDePontoComTipoFaltando()
    {
        $dadosRegistroDePonto = array('tipo' => '', 'id_colaborador' => 111);
        try {
            $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo tipo é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCadastrarRegistroDePontoComIdColaboradorFaltando()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => '');
        try {
            $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo id_colaborador é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCadastrarRegistroDePontoJaExistente()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111);

        $idRegistroDePonto = $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        try {
            $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("Já existe um registro de ponto não finalizado com este tipo: inicio_expediente. Encerre-o antes de iniciar um novo registro.", $e->getMessage());
            $this->assertEquals(409, $e->getCode());
            $registroDePontoExcluido = $this->RegistroDePontoUseCases->excluir($this->RegistroDePontoGateway,  $idRegistroDePonto);
            $this->assertTrue($registroDePontoExcluido);
        }
    }

    public function testAtualizarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111, 'cpf' => "12345678912");

        $idRegistroDePonto = $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $novosDadosRegistroDePonto = array('tipo' => 'termino_expediente', 'id_colaborador' => 111, 'cpf' => "12345678912");

        $resultado = $this->RegistroDePontoUseCases->atualizar($this->RegistroDePontoGateway, $novosDadosRegistroDePonto);

        $this->assertTrue($resultado);

        $registroDePontoExcluido = $this->RegistroDePontoUseCases->excluir($this->RegistroDePontoGateway, $idRegistroDePonto);
        $this->assertTrue($registroDePontoExcluido);
    }

    public function testAtualizarRegistroDePontoComIdColaboradorFaltando()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111, 'cpf' => '12345678912');
        $idRegistroDePonto = $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $novosDadosRegistroDePonto = array('tipo' => 'termino_expediente', 'id_colaborador' => "", 'cpf' => '12345678912');

        try {
            $this->RegistroDePontoUseCases->atualizar($this->RegistroDePontoGateway, $novosDadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo id_colaborador é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $registroDePontoExcluido = $this->RegistroDePontoUseCases->excluir($this->RegistroDePontoGateway,  $idRegistroDePonto);
            $this->assertTrue($registroDePontoExcluido);
        }
    }

    public function testAtualizarRegistroDePontoComTipoFaltando()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111, 'cpf' => '12345678912');
        $idRegistroDePonto = $this->RegistroDePontoUseCases->cadastrar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        try {
            $novosDadosRegistroDePonto = array('tipo' => '', 'id_colaborador' => 111, 'cpf' => '12345678912');
            $this->RegistroDePontoUseCases->atualizar($this->RegistroDePontoGateway, $novosDadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo tipo é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $registroDePontoExcluido = $this->RegistroDePontoUseCases->excluir($this->RegistroDePontoGateway,  $idRegistroDePonto);
            $this->assertTrue($registroDePontoExcluido);
        }
    }

    public function testAtualizarRegistroDePontoComRegistroDePontoNaoEncontrado()
    {
        $dadosRegistroDePonto = array('tipo' => 'termino_expediente', 'id_colaborador' => 111, 'cpf' => '111111111111111111111111');

        try {
            $this->RegistroDePontoUseCases->atualizar($this->RegistroDePontoGateway, $dadosRegistroDePonto);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um registro de ponto pendente de finalização.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
