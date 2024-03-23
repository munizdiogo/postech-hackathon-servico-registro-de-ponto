<?php

use Fpdf\Fpdf;

if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
} else {
    require './vendor/autoload.php';
}

function gerarPdfEspelhoDePonto($nomeArquivo, array $array_dados)
{
    // Criar novo objeto FPDF
    $pdf = new Fpdf();

    $pdf->AddPage('L');

    // Definir fonte
    $pdf->SetFont('Arial', 'B', 12);

    // Adicionar título
    $pdf->Cell(0, 30, 'Espelho de ponto', 0, 1, 'C');

    // Adicionar cabeçalho da tabela
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(30, 10, 'CPF', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Data Inicio', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Data Termino', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Tipo', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Hrs. Trab.', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Min. Trab.', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Hrs. Em Pausa', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Min. Em Pausa', 1, 1, 'C');

    // Definir fonte para os dados
    $pdf->SetFont('Arial', '', 10);

    // Adicionar dados da tabela
    $totalHorasTrabalhadas = 0;
    $totalMinutosTrabalhados = 0;
    $totalHorasEmPausa = 0;
    $totalMinutosEmPausa = 0;

    foreach ($array_dados as $chave => $valor) {
        $dataHoraInicio = new DateTime($valor['data_hora_inicio']);
        $dataHoraTermino = new DateTime($valor['data_hora_termino']);
        $pdf->Cell(10, 10, $valor['id'], 1, 0, 'C');
        $pdf->Cell(30, 10, $valor['cpf'], 1, 0, 'C');
        $pdf->Cell(40, 10, $dataHoraInicio->format("d/m/Y - H:i"), 1, 0, 'C');
        $pdf->Cell(40, 10, $dataHoraTermino->format("d/m/Y - H:i"), 1, 0, 'C');
        $pdf->Cell(30, 10, $valor['tipo'], 1, 0, 'C');
        $pdf->Cell(30, 10, $valor['status'], 1, 0, 'C');
        $pdf->Cell(20, 10, $valor['qtd_horas_trabalhadas'], 1, 0, 'C');
        $pdf->Cell(20, 10, $valor['qtd_minutos_trabalhados'], 1, 0, 'C');
        $pdf->Cell(30, 10, $valor['qtd_horas_em_pausa'], 1, 0, 'C');
        $pdf->Cell(30, 10, $valor['qtd_minutos_em_pausa'], 1, 1, 'C');
        $totalHorasTrabalhadas += $valor['qtd_horas_trabalhadas'];
        $totalMinutosTrabalhados += $valor['qtd_minutos_trabalhados'];
        $totalHorasEmPausa += $valor['qtd_horas_em_pausa'];
        $totalMinutosEmPausa += $valor['qtd_minutos_em_pausa'];
    }

    $pdf->Cell(0, 10, "", 0, 1, 'C');
    $pdf->Cell(0, 10, "", 0, 1, 'C');
    $pdf->Cell(0, 10, "", 0, 1, 'C');
    $pdf->Cell(0, 10, "", 0, 1, 'C');
    $pdf->Cell(0, 10, "Total de horas trabalhadas: $totalHorasTrabalhadas", 0, 1, 'L');
    $pdf->Cell(0, 10, "Total de minutos trabalhados: $totalMinutosTrabalhados", 0, 1, 'L');
    $pdf->Cell(0, 10, "Total de horas Em Pausa: $totalHorasEmPausa", 0, 1, 'L');
    $pdf->Cell(0, 10, "Total de minutos Em Pausa: $totalMinutosEmPausa", 0, 1, 'L');

    // Gerar o PDF
    $pdf->Output($nomeArquivo, 'F');
}
