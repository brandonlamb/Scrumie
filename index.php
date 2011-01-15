<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
require_once ('./core/Application.php');
require_once('./controllers/ScrumieController.php');


require_once('./core/DAO.php');
DAO::setAdapter(new DatabaseAdapter(new PDO('sqlite:data/scrumie.sqlite')));

session_start();
$App = Application::getInstance();

$App->dispatch($App->getControllerName(), $App->getActionName());

//debug function
function mpr($val, $die=false) {
    if(!headers_sent()) 
        header("content-type: text/plain");

    if (is_array($val) || is_object($val)) {
        print_r($val);

    if(is_array($val))
        reset($val);
    }   
    else
        var_dump($val);

    if($die)
    {   
        $trace = debug_backtrace();
        echo "--\n";
        echo sprintf('Who called me: %s line %s', $trace[0]['file'], $trace[0]['line']);
        die();
    }   
}
