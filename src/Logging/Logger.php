<?php
/**
 * AVOLUTIONS
 *
 * Just another open source PHP framework.
 *
 * @copyright   Copyright (c) 2019 - 2021 AVOLUTIONS
 * @license     MIT License (https://avolutions.org/license)
 * @link        https://avolutions.org
 */

namespace Avolutions\Logging;

use Avolutions\Config\Config;
use Datetime;

/**
 * Logger class
 *
 * The Logger class writes messages with a specific level to a logfile.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.1.0
 */
class Logger
{
    /**
     * The loglevels in ascending order of priority.
     *
	 * @var array $loglevels
	 */
    private static array $loglevels = [
        LogLevel::DEBUG,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
        LogLevel::ERROR,
        LogLevel::CRITICAL,
        LogLevel::ALERT,
        LogLevel::EMERGENCY
    ];

    /**
     * log
     *
     * Opens the logfile and write the message and all other information
     * like date, time, level to the file.
     *
     * @param string $logLevel The log level
     * @param string $message The log message
     */
    private static function log(string $logLevel, string $message)
    {
        // only log message if $loglevel is greater or equal than the loglevel from config
		if (array_search($logLevel, self::$loglevels) < array_search(Config::get('logger/loglevel'), self::$loglevels)) {
            return;
        }

		$logpath = Config::get('logger/logpath');
		$logfile = Config::get('logger/logfile');
		$datetimeFormat = Config::get('logger/datetimeFormat');

		$datetime = new Datetime();
		$logText = '['.$logLevel.'] | '.$datetime->format($datetimeFormat).' | '.$message;

		if (!is_dir($logpath)){
			mkdir($logpath, 0755);
		}

		$handle = fopen($logpath.$logfile, 'a');
		fwrite($handle, $logText);
		fwrite($handle, PHP_EOL);
		fclose($handle);
	}

    /**
     * emergency
     *
     * Writes the passed message with level "EMERGENCY" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function emergency(string $message)
    {
		self::log(LogLevel::EMERGENCY, $message);
	}

    /**
     * alert
     *
     * Writes the passed message with level "ALERT" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function alert(string $message)
    {
		self::log(LogLevel::ALERT, $message);
	}

    /**
     * critical
     *
     * Writes the passed message with level "CRITICAL" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function critical(string $message)
    {
		self::log(LogLevel::CRITICAL, $message);
	}

    /**
     * error
     *
     * Writes the passed message with level "ERROR" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function error(string $message)
    {
		self::log(LogLevel::ERROR, $message);
	}

    /**
     * warning
     *
     * Writes the passed message with level "WARNING" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function warning(string $message)
    {
		self::log(LogLevel::WARNING, $message);
	}

    /**
     * notice
     *
     * Writes the passed message with level "NOTICE" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function notice(string $message)
    {
		self::log(LogLevel::NOTICE, $message);
	}

    /**
     * info
     *
     * Writes the passed message with level "INFO" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function info(string $message)
    {
		self::log(LogLevel::INFO, $message);
	}

    /**
     * debug
     *
     * Writes the passed message with level "DEBUG" to the logfile.
     *
     * @param string $message The message to log
     */
    public static function debug(string $message)
    {
        self::log(LogLevel::DEBUG, $message);
	}
}