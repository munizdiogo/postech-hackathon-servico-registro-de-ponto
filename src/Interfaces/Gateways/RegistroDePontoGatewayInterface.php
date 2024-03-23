<?php

namespace RegistroDePonto\Interfaces\Gateways;

interface RegistroDePontoGatewayInterface
{
    public function cadastrar(array $dados);
    public function atualizar(int $id, array $dados): bool;
    public function excluir(int $id): bool;
    public function obterPorId(string $id): array;
    public function obterRegistrosPorFiltro(array $dados, string $orderByASCouDESC);
}
