<?php
require_once 'core/class/Tarea.php';

class TareaOnLine extends Tarea
{
  public function __construct($dataTask)
  {
    parent::__construct($dataTask);
  }

  protected function defineVarSession()
  {
    $this->dataTask = &$_SESSION['TAREA_ONLINE']['list'];
  }

  public static function getListSession()
  {
    return isset($_SESSION['TAREA_ONLINE']['list']) ? $_SESSION['TAREA_ONLINE']['list'] : [];
  }

  public static function &yiPrepareTaskOnLine($path, $iterator, $trigger = null)
  {
    $list = [];
    $params = [];
    foreach ($iterator as $fase) {
      yield $params;

      $task = [
        "path" => $path,
        "params" => [
          "trigger" => $trigger,
          "data" => $params,
        ]
      ];

      $list[] = $task;
      break;
    }

    return $list;
  }
}
