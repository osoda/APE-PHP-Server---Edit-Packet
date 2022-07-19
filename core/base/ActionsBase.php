<?php

require_once 'core/base/Response.php';

class ActionsBase
{
  protected $dataActionNew = null;
  public $actionCompleted = false;

  public function sendAction()
  {
    $response = new Response($_GET['sockid']);
    list($responseText, $response_status) = $response->send($this->dataActionNew);

    error_log('*E: ActionsBase[21]: Nuevo paquete enviado: data: ' . print_r($this->dataActionNew, 1));
    error_log('*E: ActionsBase[21]: Nuevo paquete enviado: Response: ' . print_r($responseText, 1));
    error_log('*E: ActionsBase[21]: status Envio : ' . print_r($response_status, 1));

    $this->dataActionNew = null;
    $this->actionCompleted = true;
  }

  public function end()
  {
    if (is_null($this->dataActionNew)) return;

    $this->sendAction($this->dataActionNew);
  }
}
