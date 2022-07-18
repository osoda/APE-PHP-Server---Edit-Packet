<?php


require_once('vendor/autoload.php');


class Response
{
  public function __construct($socktId, $port = 8083)
  {
    $this->socktId = str_pad(dechex($socktId), 4, "0", STR_PAD_LEFT);
    $this->port = $port;
    $this->httpRequest = new HTTP_Request2();
  }

  public function send($data)
  {
    try {
      $request = $this->httpRequest->setUrl("http://127.0.0.1:{$this->port}?func=send()&sockid=$this->socktId");
      $url = $request->getUrl();
      // $url->setQueryVariables([
      //   'func' => 'send()',
      //   'sockid' => $this->socktId,
      // ]);
      $request->setMethod(HTTP_Request2::METHOD_POST);
      $request->setBody($data);

      $response = $request->send();
      $responseText = $response->getBody();
      $response_status = $response->getStatus();
      $response_obj = json_decode($responseText, 1);
      if ($response_status != 200) {
        error_log('*E: Respuesta: ' . print_r($responseText, 1));
        error_log('*E: RespuestaObj: ' . print_r($response_obj, 1));
        error_log('*E: Respuesta Status: ' . print_r($response_status, 1));
        error_log('*E: Respuesta Status: ' . print_r($response_status, 1));
        throw new Exception("DEV: No se esta creando la de conexión con la API", 61000);
      }
      // echo $response_status;
      // echo $responseText;
    } catch (HTTP_Request2_Exception $ex) {
      error_log('*E: testF[HTTP_Request2_Exception]: ' . print_r($ex, 1));
      throw new Exception("Error HTTP_Request2_Exception: {$ex->getMessage()}", 61000);
    } catch (Exception $e) {
      error_log('*E: Response[23]: ' . print_r($e, 1));
      throw new Exception("Error : {$e->getMessage()}", 61000);
    }

    return [$responseText, $response_status];
  }
}
