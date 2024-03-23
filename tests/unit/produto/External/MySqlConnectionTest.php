<?php

use RegistroDePonto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;

class MySqlConnectionTest extends TestCase
{
    private $dbConnection;
    private $nomeTabela;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = new MySqlConnection;
        $this->nomeTabela = 'registros_de_ponto';
    }

    public function testConectarComSucesso()
    {
        $conn = $this->dbConnection->conectar();
        $this->assertInstanceOf(\PDO::class, $conn);
    }
    public function testConectarComErro()
    {
        $conn = $this->dbConnection->conectar();
        $this->assertInstanceOf(\PDO::class, $conn);
    }

    public function testInserir()
    {
        $parametros = array(
            'status' => 'iniciado',
            'cadastrado_em' => date('Y-m-d H:i:s'),
            'modificado_em' => date('Y-m-d H:i:s'),
            'id_colaborador' => 111,
            'tipo' => 'inico_expediente',
            'data_hora_inicio' => '2024-03-22 08:00:00',
            'data_hora_termino' => '2024-03-22 17:00:00'
        );

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);
        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }

    public function testExcluir()
    {
        $parametros = array(
            'status' => 'iniciado',
            'cadastrado_em' => date('Y-m-d H:i:s'),
            'modificado_em' => date('Y-m-d H:i:s'),
            'id_colaborador' => 111,
            'tipo' => 'inico_expediente',
            'data_hora_inicio' => '2024-03-22 08:00:00',
            'data_hora_termino' => '2024-03-22 17:00:00'
        );

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);
        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }

    public function testAtualizar()
    {
         $parametros = array(
            'status' => 'iniciado',
            'cadastrado_em' => date('Y-m-d H:i:s'),
            'modificado_em' => date('Y-m-d H:i:s'),
            'id_colaborador' => 111,
            'tipo' => 'inico_expediente',
            'data_hora_inicio' => '2024-03-22 08:00:00',
            'data_hora_termino' => '2024-03-22 17:00:00'
        );

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $novosDados = array(
            'status' => 'finalizado'
        );

        $result = $this->dbConnection->atualizar($this->nomeTabela, $id, $novosDados);

        $this->assertTrue($result);

        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);

        $this->assertTrue($excluirRegistro);
    }

    public function testBuscarPorParametros()
    {
        $parametros = array(
            'status' => 'iniciado',
            'cadastrado_em' => date('Y-m-d H:i:s'),
            'modificado_em' => date('Y-m-d H:i:s'),
            'id_colaborador' => 111,
            'tipo' => 'inico_expediente',
            'data_hora_inicio' => '2024-03-22 08:00:00',
            'data_hora_termino' => '2024-03-22 17:00:00'
        );

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $campos = ["*"];

        $parametros = [
            [
                "campo" => "id_colaborador",
                "valor" => "111"
            ]
        ];

        $result = $this->dbConnection->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        $this->assertIsArray($result);

        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }
}
