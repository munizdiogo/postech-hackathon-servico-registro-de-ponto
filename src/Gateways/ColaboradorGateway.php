<?php

namespace RegistroDePonto\Gateways;

require "./src/Interfaces/Gateways/ColaboradorGatewayInterface.php";

use RegistroDePonto\Interfaces\DbConnection\DbConnectionInterface;
use RegistroDePonto\Interfaces\Gateways\ColaboradorGatewayInterface;
use PDOException;

class ColaboradorGateway implements ColaboradorGatewayInterface
{
    private $repositorioDados;
    private $nomeTabela = "colaboradores";

    public function __construct(DbConnectionInterface $database)
    {
        $this->repositorioDados = $database;
    }

    public function obterPorCpf(string $cpf): array
    {
        $campos = [];
        $parametros = [
            [
                "campo" => "cpf",
                "valor" => $cpf
            ]
        ];
        $resultado = $this->repositorioDados->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        return $resultado[0] ?? [];
    }
}
