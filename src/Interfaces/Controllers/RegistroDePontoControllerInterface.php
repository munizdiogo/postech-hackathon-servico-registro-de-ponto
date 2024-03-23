<?php


namespace RegistroDePonto\Interfaces\Controllers;

interface RegistroDePontoControllerInterface
{
    public function cadastrar($dbConnection, array $dados);
    public function atualizar($dbConnection, array $dados);
    public function excluir($dbConnection, int $id);
}
