<?php
if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
} else {
    require './vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;

function enviarEmail($destinatario, $nome, $assunto, $mensagem, $caminhoArquivoAnexo = null)
{
    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->SMTPDebug = 0;
    $mailer->Port = EMAIL_PORT; //Indica a porta de conexão 
    $mailer->Host = EMAIL_HOST; //Endereço do Host do SMTP 
    $mailer->SMTPAuth = true; //define se haverá ou não autenticação 
    $mailer->Username = EMAIL_USERNAME; //Login de autenticação do SMTP
    $mailer->Password = EMAIL_PASSWORD; //Senha de autenticação do SMTP
    $mailer->FromName = 'Registro de Ponto'; //Nome que será exibido
    $mailer->From = EMAIL_USERNAME; //Obrigatório ser a mesma caixa postal configurada no remetente do SMTP
    $mailer->AddAddress($destinatario, $nome);
    $mailer->Subject = $assunto;
    $mailer->Body = $mensagem;

    if (!empty($caminhoArquivoAnexo)) {
        $mailer->addAttachment($caminhoArquivoAnexo, 'relatorio_registro_de_ponto.pdf');
    }
    return $mailer->Send();
}
