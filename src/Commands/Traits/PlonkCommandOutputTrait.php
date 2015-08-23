<?php

namespace Metrique\Plonk\Commands\Traits;

trait PlonkCommandOutputTrait {

    public static $CONSOLE_INFO = 0;
    public static $CONSOLE_ERROR = 1;
    public static $CONSOLE_COMMENT = 2;

	/**
     * Outputs information to the console.
     * @param int $mode 
     * @param string $message 
     * @return void
     */
    public function output($mode, $message, $newline = false) {

        $newline = $newline ? PHP_EOL : '';

        switch ($mode) {
            case self::$CONSOLE_COMMENT:
                $this->comment('[-msg-] ' . $message . $newline);
            break;

            case self::$CONSOLE_ERROR:
                $this->error('[-err-] ' . $message . $newline);
            break;
            
            default:
                $this->info('[-nfo-] ' . $message . $newline);
            break;
        }
    }

    /**
     * Helper method to make new lines, and comments look pretty!
     * @return void
     */
    public function newline() {
        $this->info('');    
    }
}