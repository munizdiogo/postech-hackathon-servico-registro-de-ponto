<?php


namespace RegistroDePonto\Interfaces\Controllers;

interface ColaboradorControllerInterface
{
    public function obterPorCpf($dbConnection, string $cpf);
}
