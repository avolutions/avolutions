<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @copyright	Copyright (c) 2019 - 2020 AVOLUTIONS
 * @license		MIT License (http://avolutions.org/license)
 * @link		http://avolutions.org
 */
 
namespace Avolutions\Event;

use Avolutions\Event\EntityEvent;

/**
 * EventDispatcher class
 *
 * TODO
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.3.0
 */
class EventDispatcher
{
	/**
     * TODO
     */
    public static function dispatch($Event)
    {
        if ($Event instanceof EntityEvent) {            
            $entityName = $Event->Entity->getEntityName();
            $listener = APP_LISTENER_NAMESPACE.$entityName.'Listener';
            $method = 'handle'.$Event->getName();
            $callable = [$listener, $method];

            if (\is_callable($callable)) {
                call_user_func($callable, $Event);
            }

            return;
        }

        $ListenerCollection = ListenerCollection::getInstance();
        foreach ($ListenerCollection->getListener($Event->getName()) as $listener) {
            call_user_func($listener, $Event);
        }
    }
}