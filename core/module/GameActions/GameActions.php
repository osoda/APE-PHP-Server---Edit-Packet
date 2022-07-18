<?php

require_once 'core/base/ActionsBase.php';
class GameActions extends ActionsBase
{
  public function __construct()
  {
  }

  public function changeMapa($params)
  {
    $trigger = $params['trigger'];
    $sendDest = $params['sendDest'];

    if ($this->isActivaTrigger($trigger)) {
      $this->dataActionNew = $sendDest;
    }

    return Packet::DEFAULT;
  }

  private function isActivaTrigger($trigger)
  {
    if (is_null($trigger)) return true;

    if (is_array($trigger)) {
      $command = $trigger[0];
      if (isset($_SESSION["TAREA"]['Globals']) && isset($_SESSION["TAREA"]['Globals'][$command])) {
        foreach ($_SESSION["TAREA"]['Globals'][$command] as $action => $value) {
          switch ($action) {
            case 'regex':
              if (preg_match("$value", $_SESSION['TAREA']['data'], $output_array))
                return true;
              break;
            default:
              break;
          }
        }
      }
    }
    return false;
  }
}
