<?php

function retornarRespostaJSON($resposta, int $statusCode): void
{
    http_response_code($statusCode);
    if (is_array($resposta)) {
        echo json_encode($resposta, JSON_UNESCAPED_UNICODE);
    } else {
        echo '{"mensagem":"' . $resposta . '"}';
    }
}
