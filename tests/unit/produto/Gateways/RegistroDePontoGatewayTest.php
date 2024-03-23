<?php

namespace RegistroDePonto\Tests\Gateways;

use PHPUnit\Framework\TestCase;
use RegistroDePonto\Gateways\RegistroDePontoGateway;
use RegistroDePonto\External\MySqlConnection;

class RegistroDePontoGatewayTest extends TestCase
{
    private $dbConnection;
    private $RegistroDePontoGateway;

    protected function setUp(): void
    {
        $this->dbConnection = new MySqlConnection();
        $this->RegistroDePontoGateway = new RegistroDePontoGateway($this->dbConnection);
    }

    public function testCadastrarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111, 'cpf' => '12345678912');
        $idRegistroDePonto = $this->RegistroDePontoGateway->cadastrar($dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $RegistroDePontoExcluido = $this->RegistroDePontoGateway->excluir($idRegistroDePonto);
        $this->assertTrue($RegistroDePontoExcluido);
    }

    public function testAtualizarRegistroDePontoComSucesso()
    {
        $dadosRegistroDePonto = array('tipo' => 'inicio_expediente', 'id_colaborador' => 111, 'cpf' => '12345678912');
        $idRegistroDePonto = $this->RegistroDePontoGateway->cadastrar($dadosRegistroDePonto);
        $this->assertIsInt($idRegistroDePonto);

        $novosDadosRegistroDePonto = array('tipo' => 'termino_expediente', 'id_colaborador' => 111, 'cpf' => '12345678912');

        $resultado = $this->RegistroDePontoGateway->atualizar($idRegistroDePonto, $novosDadosRegistroDePonto);
        $this->assertTrue($resultado);

        $RegistroDePontoExcluido = $this->RegistroDePontoGateway->excluir($idRegistroDePonto);
        $this->assertTrue($RegistroDePontoExcluido);
    }
}
