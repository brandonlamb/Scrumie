<?php

class RoutingException extends ApplicationException {}
abstract class Routing {
    public $controller;
    public $action;
}
