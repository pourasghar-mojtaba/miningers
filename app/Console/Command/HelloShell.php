<?php
App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class HelloShell extends AppShell {
    public function main() {
        $this->out('Hello world.');
    }

    public function hey_there() {
        $this->out('Hey there ' . $this->args[0]);
    }
}