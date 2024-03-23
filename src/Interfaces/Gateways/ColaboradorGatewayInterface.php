<?php

namespace RegistroDePonto\Interfaces\Gateways;

interface ColaboradorGatewayInterface
{
    public function obterPorCpf(string $cpf);
}
