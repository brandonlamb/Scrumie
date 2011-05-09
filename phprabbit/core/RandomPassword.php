<?php

class RandomPassword {
    protected $length;
    protected $password;

    public function __construct($length=8) {
        $this->length = $length;
    }

    protected function generate()
    {
        $lower_ascii_bound = 50;
        $upper_ascii_bound = 122;
        $notuse = array (58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);

        for ($i=0; $i < $this->length; $i++) {
            mt_srand ((double)microtime() * 1000000);
            $randnum = mt_rand ($lower_ascii_bound, $upper_ascii_bound);
            if (!in_array ($randnum, $notuse)) {
                $this->password .= chr($randnum);
            }
        }

        return $this->password;
    }

    public function __toString() {
        return ($this->password) ? $this->password : $this->generate();
    }
}
