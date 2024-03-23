<?php

namespace RegistroDePonto\UseCases;

require "./src/Interfaces/UseCases/ColaboradorUseCasesInterface.php";

use RegistroDePonto\Gateways\ColaboradorGateway;
use RegistroDePonto\Interfaces\UseCases\ColaboradorUseCasesInterface;

class ColaboradorUseCases implements ColaboradorUseCasesInterface
{
    public function obterPorCpf(ColaboradorGateway $colaboradorGateway, string $cpf)
    {
        if (empty($cpf)) {
            throw new \Exception("O campo cpf é obrigatório.", 400);
        }

        $dadosColaborador = $colaboradorGateway->obterPorCpf($cpf);
        return $dadosColaborador;
    }
}
