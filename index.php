<?php

header('Content-Type: application/json; charset=utf-8');
require "./utils/RespostasJson.php";
require "./utils/GerarPdfEspelhoDePonto.php";
require "./utils/EnviarEmail.php";
require "./src/External/MySqlConnection.php";
require "./src/Controllers/RegistroDePontoController.php";
require "./src/Controllers/ColaboradorController.php";

use RegistroDePonto\Controllers\ColaboradorController;
use RegistroDePonto\External\MySqlConnection;
use RegistroDePonto\Controllers\RegistroDePontoController;

$dbConnection = new MySqlConnection();
$registroDePontoController = new RegistroDePontoController();
$colaboradorController = new ColaboradorController();

$cpf = !empty($_POST["cpf"]) ? $_POST["cpf"] : (!empty($_GET["cpf"]) ? $_GET["cpf"] : null);

$headers = apache_request_headers();
$token = $headers["Authorization"];

if (empty($token)) {
    retornarRespostaJSON("Não autorizado. Nenhum token informado.", 401);
    exit;
}

if (!empty($_GET["acao"])) {
    switch ($_GET["acao"]) {
        case "registrar":
            try {
                if (empty($_POST["tipo"])) {
                    retornarRespostaJSON("O tipo é obrigatório.", 400);
                    exit;
                }

                if (empty($_POST["cpf"])) {
                    retornarRespostaJSON("O CPF é obrigatório.", 400);
                    exit;
                }

                if (!in_array($_POST["tipo"], ["inicio_expediente", "inicio_pausa", "termino_expediente", "termino_pausa"])) {
                    retornarRespostaJSON("O tipo informado é inválido.", 400);
                    exit;
                }

                $dadosParaRegistro = [
                    "tipo" => $_POST["tipo"] ?? null,
                    "cpf" => !empty($_POST["cpf"]) ? str_replace([".", "-"], "",  $_POST["cpf"]) : null
                ];

                $colaboradorExistente = $colaboradorController->obterPorCpf($dbConnection, $dadosParaRegistro["cpf"]);

                if (empty($colaboradorExistente)) {
                    retornarRespostaJSON("Nenhum colaborador encontrado com o CPF informado", 400);
                    exit;
                }

                if ($colaboradorExistente["status"] == "desativado") {
                    retornarRespostaJSON("Não foi possível registrar o ponto, pois o colaborador está com o status: desativado", 200);
                    exit;
                }

                $dadosParaRegistro["id_colaborador"] = $colaboradorExistente["id"];

                if (in_array($_POST["tipo"], ["inicio_expediente", "inicio_pausa"])) {
                    $registroDePontoController->cadastrar($dbConnection, $dadosParaRegistro);
                    retornarRespostaJSON("Registro de ponto " . $_POST["tipo"] . " registrado com sucesso.", 200);
                } else {
                    $registroDePontoController->atualizar($dbConnection, $dadosParaRegistro);
                    retornarRespostaJSON("Registro de ponto " . $_POST["tipo"] . " registrado com sucesso.", 200);
                }
            } catch (\Exception $e) {
                retornarRespostaJSON($e->getMessage(), $e->getCode());
            }
            break;

        case "obterRegistrosPorFiltro":

            $cpf = !empty($_GET["cpf"]) ? str_replace([".", "-"], "",  $_GET["cpf"]) : null;
            $dataHoraInicio = $_GET["dataHoraInicio"] ?? null;
            $dataHoraFim = $_GET["dataHoraFim"] ?? null;

            if (empty($cpf)) {
                retornarRespostaJSON("O CPF é obrigatório.", 400);
                exit;
            }

            if (empty($dataHoraInicio)) {
                retornarRespostaJSON("A dataHoraInicio é obrigatório.", 400);
                exit;
            }

            if (empty($dataHoraFim)) {
                retornarRespostaJSON("A dataHoraFim é obrigatório.", 400);
                exit;
            }

            $colaboradorExistente = $colaboradorController->obterPorCpf($dbConnection, $cpf);

            if (empty($colaboradorExistente)) {
                retornarRespostaJSON("Nenhum colaborador encontrado com o CPF informado", 400);
                exit;
            }

            if ($colaboradorExistente["status"] == "desativado") {
                retornarRespostaJSON("Não foi possível registrar o ponto, pois o colaborador está com o status: desativado", 200);
                exit;
            }

            $idColaborador = $colaboradorExistente["id"];

            $dadosParaBuscarRegistros = [
                "cpf" => $cpf,
                "idColaborador" => $idColaborador,
                "dataHoraInicio" => $dataHoraInicio,
                "dataHoraFim" => $dataHoraFim
            ];

            $registros = $registroDePontoController->obterRegistrosPorFiltro($dbConnection, $dadosParaBuscarRegistros, "DESC");

            if (empty($registros)) {
                retornarRespostaJSON("Nenhum registro encontrado.", 200);
                exit;
            }

            echo json_encode($registros);
            break;

        case "enviarRelatorioPorEmail":

            $cpf = !empty($_GET["cpf"]) ? str_replace([".", "-"], "",  $_GET["cpf"]) : null;
            $dataHoraInicio = $_GET["dataHoraInicio"] ?? null;
            $dataHoraFim = $_GET["dataHoraFim"] ?? null;

            if (empty($cpf)) {
                retornarRespostaJSON("O CPF é obrigatório.", 400);
                exit;
            }

            if (empty($dataHoraInicio)) {
                retornarRespostaJSON("A dataHoraInicio é obrigatório.", 400);
                exit;
            }

            if (empty($dataHoraFim)) {
                retornarRespostaJSON("A dataHoraFim é obrigatório.", 400);
                exit;
            }

            $colaboradorExistente = $colaboradorController->obterPorCpf($dbConnection, $cpf);

            if (empty($colaboradorExistente)) {
                retornarRespostaJSON("Nenhum colaborador encontrado com o CPF informado", 400);
                exit;
            }

            if ($colaboradorExistente["status"] == "desativado") {
                retornarRespostaJSON("Não foi possível registrar o ponto, pois o colaborador está com o status: desativado", 200);
                exit;
            }

            $idColaborador = $colaboradorExistente["id"];

            $dadosParaBuscarRegistros = [
                "cpf" => $cpf,
                "idColaborador" => $idColaborador,
                "dataHoraInicio" => $dataHoraInicio,
                "dataHoraFim" => $dataHoraFim
            ];

            $registros = $registroDePontoController->obterRegistrosPorFiltro($dbConnection, $dadosParaBuscarRegistros, "DESC");

            if (empty($registros)) {
                retornarRespostaJSON("Nenhum registro encontrado.", 200);
                exit;
            }

            $qtdMinutosEmPausaPorDia = [];

            foreach ($registros as $chave => $registro) {
                $minutosTrabalhadosFinal = 0;
                $minutosEmPausaFinal = 0;

                if ($registro["tipo"] == "expediente" && $registro["status"] == "finalizado") {
                    $dataHoraInicioExpediente = new DateTime($registro['data_hora_inicio']);
                    $dataHoraTerminoExpediente = new DateTime($registro['data_hora_termino']);
                    $diferencaExpediente = $dataHoraTerminoExpediente->diff($dataHoraInicioExpediente);
                    $minutosTrabalhados = $diferencaExpediente->days * 24 * 60; // Convertendo dias para minutos
                    $minutosTrabalhados += $diferencaExpediente->h * 60; // Adicionando horas convertidas para minutos
                    $minutosTrabalhados += $diferencaExpediente->i; // Adicionando minutos
                    $minutosTrabalhadosFinal += $minutosTrabalhados;
                }

                if ($registro["tipo"] == "pausa" && $registro["status"] == "finalizado") {
                    $dataHoraInicioPausa = new DateTime($registro['data_hora_inicio']);
                    $dataHoraTerminoPausa = new DateTime($registro['data_hora_termino']);
                    $diferencaPausa = $dataHoraTerminoPausa->diff($dataHoraInicioPausa);
                    $minutosEmPausa = $diferencaPausa->days * 24 * 60; // Convertendo dias para minutos
                    $minutosEmPausa += $diferencaPausa->h * 60; // Adicionando horas convertidas para minutos
                    $minutosEmPausa += $diferencaPausa->i; // Adicionando minutos
                    $minutosEmPausaFinal = $minutosEmPausa;
                    $minutosTrabalhadosFinal = $minutosTrabalhadosFinal - $minutosEmPausaFinal;
                    $dia = new DateTime($registro['data_hora_inicio']);
                    $diaIndice = $dia->format("Y-m-d");
                    $qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"] = !empty($qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"]) ? $qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"] + $minutosEmPausaFinal : $minutosEmPausaFinal;
                }

                $qtdHorasEmPausa = $minutosEmPausaFinal / 60;

                $qtdHorasTrabalhadas = !empty($qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"]) ? ($minutosTrabalhadosFinal / 60) - ($qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"] / 60) : ($minutosTrabalhadosFinal / 60);

                $minutosTrabalhadosFinal = !empty($qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"]) ? $minutosTrabalhadosFinal - $qtdMinutosEmPausaPorDia["$diaIndice"]["minutos_em_pausa"] : $minutosTrabalhadosFinal;

                $registros[$chave]["qtd_horas_trabalhadas"] = $qtdHorasTrabalhadas <= 0 ? "0" : $qtdHorasTrabalhadas;
                $registros[$chave]["qtd_minutos_trabalhados"] = $minutosTrabalhadosFinal <= 0 ? "0" : $minutosTrabalhadosFinal;
                $registros[$chave]["qtd_horas_em_pausa"] = $qtdHorasEmPausa <= 0 ? "0" : $qtdHorasEmPausa;
                $registros[$chave]["qtd_minutos_em_pausa"] = $minutosEmPausaFinal <= 0 ? "0" : $minutosEmPausaFinal;
            }

            $registros = array_reverse($registros);

            $caminhoENomeArquivo = PATH_ARQUIVOS_TEMPORARIOS . "/$cpf" . "_" . time() . ".pdf";
            gerarPdfEspelhoDePonto($caminhoENomeArquivo, $registros);

            $dataHoraInicioData = new DateTime($dataHoraInicio);
            $dataHoraFimData = new DateTime($dataHoraFim);

            $mensagem = "Olá! Segue em anexo o relatório do seu registro de ponto entre a data: " . $dataHoraInicioData->format("d/m/Y às H:i:s") . " e " . $dataHoraFimData->format("d/m/Y às H:i:s") . " conforme solicitado.";

            $enviarPorEmail = enviarEmail($colaboradorExistente["email"], $colaboradorExistente["nome"], "Relatorio - Registro de Ponto", $mensagem, $caminhoENomeArquivo);

            if (!$enviarPorEmail) {
                retornarRespostaJSON("Ocorreu um erro ao enviar e-mail com o relatorio. Tente novamente mais tarde.", 500);
                unlink($caminhoENomeArquivo);
                exit;
            }

            unlink($caminhoENomeArquivo);
            retornarRespostaJSON("Espelho de ponto gerado e enviado por e-mail com sucesso!", 200);
            break;

        default:
            echo '{"mensagem": "A ação informada é inválida."}';
            http_response_code(400);
    }
}
