<?php

namespace RegistroDePonto\UseCases;

require "./src/Interfaces/UseCases/RegistroDePontoUseCasesInterface.php";

use RegistroDePonto\Gateways\RegistroDePontoGateway;
use RegistroDePonto\Interfaces\UseCases\RegistroDePontoUseCasesInterface;

class RegistroDePontoUseCases implements RegistroDePontoUseCasesInterface
{
    public function cadastrar(RegistroDePontoGateway $registroDePontoGateway, array $dados)
    {
        if (empty($dados["tipo"])) {
            throw new \Exception("O campo tipo é obrigatório.", 400);
        }

        if (empty($dados["id_colaborador"])) {
            throw new \Exception("O campo id_colaborador é obrigatório.", 400);
        }

        $registroDePontoNaoFinalizado = $registroDePontoGateway->obterRegistroDePontoNaoFinalizadoPorTipoEIdColaborador($dados["tipo"], $dados["id_colaborador"]);

        if (!empty($registroDePontoNaoFinalizado)) {
            throw new \Exception("Já existe um registro de ponto não finalizado com este tipo: " . $dados["tipo"] . ". Encerre-o antes de iniciar um novo registro.", 409);
        }

        $resultadoCadastro = $registroDePontoGateway->cadastrar($dados);
        return $resultadoCadastro;
    }

    public function atualizar(RegistroDePontoGateway $registroDePontoGateway, array $dados)
    {
        if (empty($dados["tipo"])) {
            throw new \Exception("O campo tipo é obrigatório.", 400);
        }

        if (empty($dados["cpf"])) {
            throw new \Exception("O campo cpf é obrigatório.", 400);
        }

        if (empty($dados["id_colaborador"])) {
            throw new \Exception("O campo id_colaborador é obrigatório.", 400);
        }

        if ($dados["tipo"] == "termino_expediente") {
            $tipoABuscar = "inicio_expediente";
        }

        if ($dados["tipo"] == "termino_pausa") {
            $tipoABuscar = "inicio_pausa";
        }
        
        $registroDePonto = $registroDePontoGateway->obterRegistroDePontoNaoFinalizadoPorTipoEIdColaborador($tipoABuscar, $dados["id_colaborador"]);

        $id = $registroDePonto["id"] ?? null;

        if (!empty($registroDePonto)) {
            $resultadoAtualizacao = $registroDePontoGateway->atualizar($id, $dados);
            return $resultadoAtualizacao;
        } else {
            throw new \Exception("Não foi encontrado um registro de ponto pendente de finalização.", 400);
        }
    }

    public function excluir(RegistroDePontoGateway $registroDePontoGateway, int $id)
    {
        if (empty($id)) {
            throw new \Exception("O campo ID é obrigatório.", 400);
        }

        $RegistroDePontoEncontrado = $registroDePontoGateway->obterPorId($id);

        if ($RegistroDePontoEncontrado) {
            $resultadoAtualizacao = $registroDePontoGateway->excluir($id);
            return $resultadoAtualizacao;
        } else {
            throw new \Exception("Não foi encontrado um registro de ponto com o ID informado.", 400);
        }
    }

    public function excluirPorCategoria(RegistroDePontoGateway $registroDePontoGateway, string $categoria)
    {
        if (empty($categoria)) {
            throw new \Exception("O campo categoria é obrigatório.", 400);
        }

        $resultado = $registroDePontoGateway->excluirPorCategoria($categoria);
        return $resultado;
    }

    public function obterRegistrosPorFiltro(RegistroDePontoGateway $registroDePontoGateway, array $dados, string $orderByASCouDESC)
    {
        if (empty($dados["idColaborador"])) {
            throw new \Exception("O campo idColaborador é obrigatório.", 400);
        }

        if (empty($dados["dataHoraInicio"])) {
            throw new \Exception("O campo dataHoraInicio é obrigatório.", 400);
        }
        if (empty($dados["dataHoraFim"])) {
            throw new \Exception("O campo dataHoraFim é obrigatório.", 400);
        }

        $resultado = $registroDePontoGateway->obterRegistrosPorFiltro($dados, $orderByASCouDESC);
        
        return $resultado;
    }
}
