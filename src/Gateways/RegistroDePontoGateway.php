<?php

namespace RegistroDePonto\Gateways;

require "./src/Interfaces/Gateways/RegistroDePontoGatewayInterface.php";

use RegistroDePonto\Interfaces\DbConnection\DbConnectionInterface;
use RegistroDePonto\Interfaces\Gateways\RegistroDePontoGatewayInterface;
use PDOException;

class RegistroDePontoGateway implements RegistroDePontoGatewayInterface
{
    private $repositorioDados;
    private $nomeTabela = "registros_de_ponto";

    public function __construct(DbConnectionInterface $database)
    {
        $this->repositorioDados = $database;
    }

    public function cadastrar(array $dados)
    {
        if (in_array($dados["tipo"], ["inicio_expediente", "inicio_pausa"])) {
            $status = "iniciado";
        }

        $tipo =  strpos($dados["tipo"], "expediente") ? "expediente" : "pausa";

        $dadosParaCadastro = [
            "cadastrado_em" => date("Y-m-d H:i:s"),
            "modificado_em" => date("Y-m-d H:i:s"),
            "tipo" => $tipo,
            "id_colaborador" => $dados["id_colaborador"],
            "status" => $status,
            "data_hora_inicio" => date("Y-m-d H:i:s")
        ];

        $resultado = $this->repositorioDados->inserir($this->nomeTabela, $dadosParaCadastro);
        return $resultado;
    }

    public function atualizar(int $id, array $dados): bool
    {
        if (in_array($dados["tipo"], ["termino_expediente", "termino_pausa"])) {
            $status = "finalizado";
        }

        $dadosParaAtualizacao = [
            "modificado_em" => date("Y-m-d H:i:s"),
            "data_hora_termino" => date("Y-m-d H:i:s"),
            "status" => $status
        ];

        $resultado = $this->repositorioDados->atualizar($this->nomeTabela, $id, $dadosParaAtualizacao);
        return $resultado;
    }

    public function excluir(int $id): bool
    {
        $resultado = $this->repositorioDados->excluir($this->nomeTabela, $id);
        return $resultado;
    }

    public function excluirPorCategoria(string $categoria): bool
    {
        $resultado = $this->repositorioDados->excluirPorCategoria($this->nomeTabela, $categoria);
        return $resultado;
    }

    public function obterPorNome(string $nome): array
    {
        $campos = [];
        $parametros = [
            [
                "campo" => "nome",
                "valor" => $nome
            ]
        ];
        $resultado = $this->repositorioDados->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        return $resultado[0] ?? [];
    }

    public function obterRegistroDePontoNaoFinalizadoPorTipoEIdColaborador(string $tipo, string $idColaborador): array
    {
        $tipo =  strpos($tipo, "expediente") ? "expediente" : "pausa";
        $campos = [];
        $parametros = [
            [
                "campo" => "tipo",
                "valor" => $tipo
            ],
            [
                "campo" => "id_colaborador",
                "valor" => $idColaborador
            ],
            [
                "campo" => "status",
                "valor" => "iniciado"
            ]
        ];
        $resultado = $this->repositorioDados->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        return $resultado[0] ?? [];
    }

    public function obterPorId(string $id): array
    {
        $campos = [];
        $parametros = [
            [
                "campo" => "id",
                "valor" => $id
            ]
        ];
        $resultado = $this->repositorioDados->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        return $resultado[0] ?? [];
    }

    public function obterRegistrosPorFiltro(array $dados, string $orderByASCouDESC): array
    {
        $resultado = $this->repositorioDados->obterRegistrosPorFiltro($this->nomeTabela, $dados, $orderByASCouDESC);
        return $resultado ?? [];
    }
}
