<?php

namespace RegistroDePonto\Controllers;

require "./src/Interfaces/Controllers/RegistroDePontoControllerInterface.php";
require "./src/Gateways/RegistroDePontoGateway.php";
require "./src/UseCases/RegistroDePontoUseCases.php";

use RegistroDePonto\Gateways\RegistroDePontoGateway;
use RegistroDePonto\Interfaces\Controllers\RegistroDePontoControllerInterface;
use RegistroDePonto\UseCases\RegistroDePontoUseCases;

class RegistroDePontoController implements RegistroDePontoControllerInterface
{
    public function cadastrar($dbConnection, array $dados)
    {
        $dadosParaRegistro = [
            "tipo" => $dados["tipo"] ?? null,
            "id_colaborador" => $dados["id_colaborador"] ?? null
        ];

        $registroDePontoGateway = new RegistroDePontoGateway($dbConnection);
        $registroDePontoUseCases = new RegistroDePontoUseCases();
        $salvarDados = $registroDePontoUseCases->cadastrar($registroDePontoGateway, $dadosParaRegistro);
        return $salvarDados;
    }

    public function atualizar($dbConnection, array $dados)
    {
        $dadosParaRegistro = [
            "tipo" => $dados["tipo"] ?? null,
            "cpf" => $dados["cpf"] ?? null,
            "id_colaborador" => $dados["id_colaborador"] ?? null
        ];

        $registroDePontoGateway = new RegistroDePontoGateway($dbConnection);
        $registroDePontoUseCases = new RegistroDePontoUseCases();
        $atualizarDados = $registroDePontoUseCases->atualizar($registroDePontoGateway, $dadosParaRegistro);
        return $atualizarDados;
    }

    public function excluir($dbConnection, int $id)
    {
        $registroDePontoGateway = new RegistroDePontoGateway($dbConnection);
        $registroDePontoUseCases = new RegistroDePontoUseCases();
        $excluirRegistroDePonto = $registroDePontoUseCases->excluir($registroDePontoGateway, $id);
        return $excluirRegistroDePonto;
    }
    public function excluirPorCategoria($dbConnection, string $categoria)
    {
        $registroDePontoGateway = new RegistroDePontoGateway($dbConnection);
        $registroDePontoUseCases = new RegistroDePontoUseCases();
        $excluirRegistroDePonto = $registroDePontoUseCases->excluirPorCategoria($registroDePontoGateway, $categoria);
        return $excluirRegistroDePonto;
    }

    public function obterRegistrosPorFiltro($dbConnection, array $dados, string $orderByASCouDESC)
    {
        $registroDePontoGateway = new RegistroDePontoGateway($dbConnection);
        $registroDePontoUseCases = new RegistroDePontoUseCases();
        $registrosDePonto = $registroDePontoUseCases->obterRegistrosPorFiltro($registroDePontoGateway, $dados, $orderByASCouDESC);
        return $registrosDePonto;
    }
}
