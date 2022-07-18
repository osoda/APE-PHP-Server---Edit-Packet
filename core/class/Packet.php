<?php

class Packet
{
  private $dataPacket;

  const DEFAULT = '__dd__';

  public function __construct($dataPacket = Packet::DEFAULT)
  {
    $this->dataPacket = $dataPacket;
    $this->bindSpecialCharacter();
  }

  public function __toString()
  {
    return $this->dataPacket;
  }

  private function bindSpecialCharacter()
  {
    $this->dataPacket = str_replace('\\x00', '\x00', $this->dataPacket);
  }
}
