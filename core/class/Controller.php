<?php
require_once 'core/class/Tarea.php';
require_once 'core/class/Packet.php';

class Controller
{

  public function __construct()
  {
    try {
      if (self::session_start())
        $_SESSION["TAREA"] = json_decode(file_get_contents('tasks.json'), 1);

      $dataTask = $_SESSION["TAREA"]["list"];
      // error_log('*E: Controller[16]: ' . print_r(json_encode($_SESSION), 1));
      //Tareas finalizadas
      if (empty($dataTask)) {
        $this->sendDataAPE(new Packet()); //Packete de finalizacion de tareas
        return;
      }

      $data = file_get_contents('php://input');
      error_log('*E: index[4]: ' . print_r($data, 1));
      $_SESSION['TAREA']['data'] = $data;


      $objTask = new Tarea($dataTask);
      $objTask->exec();
      $this->sendDataAPE(new Packet($objTask->dataSend));
      $objTask->end();
    } catch (Exception $e) {
      error_log('*E: Controller[29]: Ocurrio un ERROR');
      error_log('*E: Controller[30]: ' . print_r($e->getMessage(), 1));
      $this->sendDataAPE(new Packet());
    }
  }
  private function sendDataAPE($dataSend)
  {
    error_log('*E: Controller[23]sendDataAPE: ' . print_r("11$dataSend", 1));

    echo "11$dataSend";
  }

  private static function session_start()
  {
    $session_id = "APDSERVER$_GET[sockid]";
    session_id($session_id);


    session_start();

    if (empty($_SESSION['TAREA'])) {
      $isNew = true;
      $_SESSION['TAREA'] = [];
      $_SESSION['TAREA_ONLINE'] = [];
    } else
      $isNew = false;

    // error_log('*E: Controller[19]: ' . print_r($_SESSION, 1));
    // $_SESSION['a'] = 112;
    // error_log('*E: Controller[19]: ' . print_r($_SESSION, 1));

    return $isNew;
  }
}
