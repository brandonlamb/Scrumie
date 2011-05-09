<?php
/**
 * Clix
 *
 * @author Roman Nowicki <peengle@gmail.com>
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClixTask.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClixParameter.php';

class Clix {
    static public $scriptName;
    public $taskDirectory;

    public function __construct($taskDirectory = null) {
        global $argv;

        self::$scriptName = array_shift($argv);

        if(substr($taskDirectory, -1) != DIRECTORY_SEPARATOR)
            $taskDirectory .= DIRECTORY_SEPARATOR;

        $this->taskDirectory = ($taskDirectory) ? $taskDirectory : dirname(__FILE__) . DIRECTORY_SEPARATOR;
    }

    public function run() {
        global $argv;

        if(count($argv))
            $taskName = array_shift($argv);
        else
            $taskName = '--list';

        if($taskName == '--list') {
            return $this->noActionSpecified();
        }
        else if(is_file($taskName)) {
            $taskName = pathinfo($taskName, PATHINFO_FILENAME);
        }

        $this->includeTask($taskName);

        $this->runTask($taskName, $argv);
    }

    public function runTask($taskName, array $parameters) {

        $args = array();
        foreach($parameters as $parameter)
        {
            $Param = new ClixParameter($parameter);
            $args[$Param->keyword] = $Param;
        }

        $this->includeTask($taskName);

        $Task = new $taskName($args);
        try {
            $Task->execute();
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage()."\n";
            exit;
        } catch (ClixTaskException $e) {
            echo $e->getMessage()."\n";
            exit;
        }
    }

    public function noActionSpecified() {
        echo "\nNo task specified. Select one from below :\n--------------------------------------\n";

        $list = array();
        $maxTaskNameLength = 0;
        foreach($this->retriveTaskList() as $taskName) {
            $list[$taskName] = $this->getTaskHint($taskName);
            $maxTaskNameLength = (strlen($taskName) > $maxTaskNameLength) ? strlen($taskName) : $maxTaskNameLength;
        }

        foreach($list as $name => $hint) {
            echo sprintf("%s - %s\n", str_pad($name, $maxTaskNameLength), $hint);
        }

        echo "\n\n";
    }

    public function getTaskHint($taskName) {
        return constant($taskName.'::HINT');
    }

    public function showTaskHelp($taskName) {
        $this->includeTask($taskName);
        $Task = new $taskName;
        $Task->help();
    }

    public function includeTask($taskName) {
        $file =  $this->taskDirectory . $taskName.'.php';

        if(!is_file($file)) {
            $this->message('Invalid task name: use --list to show all available tasks');
            exit -1;
        }

        if(!is_readable($file)) {
            $this->message('File %s is not readable', $file);
            exit -2;
        }

        require_once $file;

        if(!$this->isInstanceOfTaskClass($taskName)) {
            $this->message('%s is invalid type', $taskName);
            exit -3;
        }
    }

    public function isInstanceOfTaskClass($className) {
        $Class = new ReflectionClass($className);

        if($Class->isAbstract())
            return false;

        if(!$Class->implementsInterface('ClixTaskInterface'))
            return false;

        return true;
    }

    public function retriveTaskList() {
        $TaskDirecotry = new DirectoryIterator($this->taskDirectory);
        $task = array();

        foreach($TaskDirecotry as $File)
        {
            if($File->isDot())
                continue;
            if($File->isDir())
                continue;

            $fileinfo = (object) pathinfo($File->getPathname());

            if(!isset($fileinfo->extension))
                continue;

            if($fileinfo->extension != 'php')
                continue;

            require_once($File->getPathname());

            if(!$this->isInstanceOfTaskClass($fileinfo->filename))
                continue;

            $task[] = $fileinfo->filename;
        }

        return $task;
    }

    public function retriveTaskArguments($taskName) {
        $this->includeTask($taskName);
        $Task = new $taskName;
        return $Task->getArgumentList();
    }

    static public function error($message) {
        $args = func_get_args();

        if(count($args) == 1)
            echo $message."\n";
        else
            echo call_user_func_array('sprintf', $args)."\n";

        exit;
    }

    static public function message($message) {
        $args = func_get_args();

        if(count($args) == 1)
            echo $message;
        else
            echo call_user_func_array('sprintf', $args);

        echo "\n";
    }

    static public function ask($message, $default_value = null) {

        ob_start();
        self::message($message." ($default_value): ", $default_value);
        $message = trim(ob_get_contents());
        ob_end_clean();
        echo $message;

        $handle = fopen ('php://stdin','r');
        $line = fgets($handle);
        
        return (trim($line) === '') ? $default_value : trim($line);
    }

    static public function options(array $options, $default = null) {
        foreach($options as $key => $value)
            self::message("[%s]\t-> %s", $key, $value);

        $selection = self::ask('Choose one from above', $default);

        if(!array_key_exists($selection, $options))
            self::options($options);

        return $selection;
    }

    static public function stop($message="Stoped\n") {
        forward_static_call_array(array(__CLASS__, 'message'), func_get_args());
        exit;
    }

    static public function exec($cmd) {
        passthru($cmd);
    }
}
