<?php

namespace RegistroDePonto\Interfaces\UseCases;

use RegistroDePonto\Gateways\ColaboradorGateway;

interface ColaboradorUseCasesInterface
{
    public function obterPorCpf(ColaboradorGateway $ColaboradorGateway, string $cpf);
}
