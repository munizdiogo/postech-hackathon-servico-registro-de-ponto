<?php

namespace RegistroDePonto\Controllers;

require "./src/Interfaces/Controllers/ColaboradorControllerInterface.php";
require "./src/Gateways/ColaboradorGateway.php";
require "./src/UseCases/ColaboradorUseCases.php";

use RegistroDePonto\Gateways\ColaboradorGateway;
use RegistroDePonto\Interfaces\Controllers\ColaboradorControllerInterface;
use RegistroDePonto\UseCases\ColaboradorUseCases;

class ColaboradorController implements ColaboradorControllerInterface
{
    public function obterPorCpf($dbConnection, string $cpf)
    {
        $colaboradorGateway = new ColaboradorGateway($dbConnection);
        $colaboradorUseCases = new ColaboradorUseCases();
        $RegistroDePontos = $colaboradorUseCases->obterPorCpf($colaboradorGateway, $cpf);
        return $RegistroDePontos;
    }
}
