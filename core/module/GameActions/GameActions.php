<?php

require_once 'core/base/ActionsBase.php';
require_once 'core/class/TareaOnLine.php';
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

  public function openMerchants($params)
  {
    $trigger = $params['trigger'];

    if (!$this->isActivaTrigger($trigger))
      return Packet::DEFAULT;

    $list = $this->listTaskOpenMerchants();
    if ($list === false)
      return Packet::DEFAULT;

    $objTaskOpenMerchant = new TareaOnLine($list);
    $objTaskOpenMerchant->exec();
    $objTaskOpenMerchant->end();

    return $objTaskOpenMerchant->dataSend;
  }

  private function listTaskOpenMerchants()
  {
    if (!empty($listSession = TareaOnLine::getListSession()))
      return $listSession;

    $rMerchant = '/\|\+(\d+);\d;\d;(-\d+);([a-zA-z-]+);-5/';
    if (!preg_match_all($rMerchant, $_SESSION['TAREA']['data'], $mercantes))
      return false;

    $gId1 = 1;
    $gId2 = 2;
    $gNamePj = 3;

    $yiTaskOnline = TareaOnLine::yiPrepareTaskOnLine(
      'GameActions._openMerchant',
      $mercantes[0]
    );
    $i = 0;
    foreach ($yiTaskOnline as &$paramsTaskOnline) {
      $id1 = $mercantes[$gId1][$i];
      $id2 = $mercantes[$gId2][$i];
      $paramsTaskOnline = [
        "ER4|$id2|$id1",
        $mercantes[$gNamePj][$i],
      ];
      ($i++);
    }
    $list = $yiTaskOnline->getReturn();

    return $list;
  }

  public function _openMerchant($params)
  {
    $trigger = $params['trigger'];
    $data = $params['data'];

    if (!$this->isActivaTrigger($trigger))
      return Packet::DEFAULT;

    $this->dataActionNew = $data[0];
    error_log('*E: GameActions[78]: En cola Accion de abrir el Mercante: ' . print_r($data[1], 1));

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
