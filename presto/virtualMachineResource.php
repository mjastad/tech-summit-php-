<?php

require 'resource.php';
require_once('virtualMachine.php');
require_once('powerVM.php');

class VirtualMachineResource extends Resource {

 private $RESOURCE_VM = '/vms/';
 private $RESOURCE_VM_PWR_STATE='/set_power_state/';

 function __construct(){
    parent::__construct();
 }

 public function getAll($conn) {
    $result = parent::getAll($conn, $this->RESOURCE_VM);
    return $this->parseJson($result);
 }

 public function get($conn, $data) {
    $result = parent::get($conn, $this->RESOURCE_VM, $data);
    return new VM(json_decode($result));
 }

 public function create($conn, $data) {
    return parent::create($conn, $this->RESOURCE_VM, json_encode($data->get()));
 }

 public function delete($conn, $data) {
    return parent::delete($conn, $this->RESOURCE_VM, $data->getUUID());
 }

 public function powerOn($conn, $data) {
    $vmPwr = new VMPower("ON",$data->getUUID()); 
    return parent::create($conn, $this->RESOURCE_VM.$data->getUUID().$this->RESOURCE_VM_PWR_STATE, json_encode($vmPwr->get()));
 }

 public function powerOff($conn, $data) {
    $vmPwr = new VMPower("OFF",$data->getUUID());
    return parent::create($conn, $this->RESOURCE_VM.$data->getUUID().$this->RESOURCE_VM_PWR_STATE, json_encode($vmPwr->get()));
 }

 public function showAll($conn){
     var_dump(json_decode(parent::getAll($conn, $this->RESOURCE_VM)));
 }

 public function find($conn, $target) {
    return parent::find($conn, $target);
 }

 private function parseJson($json) {
    $instances = array();
    $data = json_decode($json);

    foreach($data->entities as $entity)  {
       $vm = new VM($entity);
       array_push($instances,$vm);
    }

    return $instances;
 }
  
}

?>
