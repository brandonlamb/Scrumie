<?php

interface RoutingInterface {
    public function getActionName();
    public function getControllerName();
    public function toString();
}
