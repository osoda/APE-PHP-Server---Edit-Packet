<?php
require_once 'core/base/Response.php';

class Tarea
{
  private $dataTask;
  private $objAction;
  public $dataSend;
  public $status;

  const STATUS_START = 1;
  const STATUS_PROGRESS = 2;
  const STATUS_END = 3;

  public function __construct($dataTask)
  {
    $this->dataTask = $dataTask;
    $this->status == Tarea::STATUS_START;
    $this->next();
  }



  private function next()
  {
    $currentTask = $this->dataTask[0];
    if ($this->evalTask($currentTask))
      $this->taskCompleted();
    else
      $this->status = Tarea::STATUS_PROGRESS;
  }

  private function evalTask($task)
  {
    $objPath = $this->getPath($task['path']);
    $params = $task['params'];
    $this->objAction = null;

    foreach ($objPath as $propPath => $valuePath) {

      switch ($propPath) {
        case 'module':
          $action = $valuePath;
          require_once "core/module/$action/$action.php";
          $this->objAction = new $action;
          break;
        case 'method':
          $method = $valuePath;
          $this->dataSend = $this->objAction->$method($params);
          break;

        default:
          # code...
          break;
      }
    }

    return $this->objAction->actionCompleted;
  }

  public function end()
  {
    if ($this->status == Tarea::STATUS_PROGRESS) {
      $this->objAction->end();
      if ($this->objAction->actionCompleted)
        $this->taskCompleted();
    }
  }

  private function taskCompleted()
  {
    array_shift($this->dataTask);
    array_shift($_SESSION["TAREA"]["list"]);
    $this->status == Tarea::STATUS_END;

    if (empty($this->dataTask))
      error_log('*E: Controller[17]: ' . print_r('**** Tareas Completadas; Lista Vacia ****', 1));
  }

  // $path ejemplo: 'prop1.prop2'
  private function getPath($path)
  {
    $propiedades = explode('.', $path);

    switch (sizeof($propiedades)) {
      case 2:
        $objPath = [
          'module' => $propiedades[0],
          'method' => $propiedades[1],
        ];
        break;
      default:
        throw new Exception('El path ($propiedades) al que intenta acceder no esta definido hasta esa cantidad de niveles', 60001);
        break;
    }

    return $objPath;
  }
}
