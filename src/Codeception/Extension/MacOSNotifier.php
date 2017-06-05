<?php
namespace Codeception\Extension;
require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

class MacOSNotifier extends \Codeception\Platform\Extension {

    static $events = array('result.print.after' => 'notify');

    function notify($event)
    {
        $result = $event->getResult();
        $failed = $result->failureCount() or $result->errorCount();
        $success_count = $result->count() - $result->failureCount() - $result->errorCount();

        $print = ($result->count() - $result->failureCount() - $result->errorCount()) . " passed, ".
            $result->failureCount()." failed, ".
            $result->errorCount()." errors";

        // Create a Notifier
        $notifier = NotifierFactory::create();

        // Create your notification
        $notification =
            (new Notification())
            ->setTitle('Codeception Tests: ' . ($failed ? "FAILED" : "PASSED"))
            ->setBody($print)
            ->setIcon(realpath(__DIR__.'/../../../codeception.png'));

        // Send it
        $notifier->send($notification);

    }

}