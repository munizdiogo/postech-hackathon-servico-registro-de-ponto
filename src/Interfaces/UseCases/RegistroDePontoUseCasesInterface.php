<?php

namespace RegistroDePonto\Interfaces\UseCases;

use RegistroDePonto\Gateways\RegistroDePontoGateway;

interface RegistroDePontoUseCasesInterface
{
    public function cadastrar(RegistroDePontoGateway $registroDePontoGateway, array $dados);
    public function atualizar(RegistroDePontoGateway $registroDePontoGateway, array $dados);
    public function excluir(RegistroDePontoGateway $registroDePontoGateway, int $id);
}
