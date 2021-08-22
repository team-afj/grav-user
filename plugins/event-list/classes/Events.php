<?php

namespace Grav\Plugin\EventList;

use Grav\Common\Grav;
use Sabre\VObject;

class Events
{
    /**
     * The grav instance.
     *
     * @var Grav
     * */
    protected $grav;

    /**
     * Constructor.
     */
    public function __construct(Grav $grav)
    {
        $this->grav = $grav;
    }

    private function getPath($filename)
    {
        $user_dir = \Grav\Common\Utils::fullPath('user://data');
        $calendar_dir = $user_dir . DIRECTORY_SEPARATOR . 'calendars';

        if (!file_exists($calendar_dir)) {
            mkdir($calendar_dir, 0777);
        }

        $path = $calendar_dir . DIRECTORY_SEPARATOR . $filename;

        return $path;
    }

    /**
     * Parses a file and return the events.
     *
     * @return Event[]
     */
    private function parse($file)
    {
        $vcalendar = VObject\Reader::read(
            fopen($file, 'r'),
            VObject\Reader::OPTION_FORGIVING
        );
        $events = [];

        $current_date = new \DateTime();

        foreach ($vcalendar->VEVENT as $event) {
            $start_date = $event->DTSTART->getDateTime();

            if ($start_date >= $current_date) {
                $end = $event->DTEND->getDateTime()->modify('-1 hour');
                $events[(string) $event->UID] = new Event(
                    $start_date,
                    $end,
                    (string) $event->SUMMARY,
                    (string) $event->DESCRIPTION,
                );
            }
        }

        uasort($events, 'Grav\Plugin\EventList\Event::compare');

        return $events;
    }

    private function downloadIcal($path, $url)
    {
        file_put_contents($path, file_get_contents($url));
    }

    public function getEvents($filename, $address)
    {
        $path = $this->getPath($filename);

        if (file_exists($path)) {
            $hours_since_creation = (time() - filemtime($path)) / 3600;

            // We download the calendar again every 12 hours
            if ($hours_since_creation > 12) {
                $this->grav['log']->info(
                    'Updating calendar: ' . $filename . ' (' . $address . ')'
                );
                $this->downloadIcal($path, $address);
            }
        } else {
            $this->grav['log']->info(
                'Downloading new calendar: ' . $filename . ' (' . $address . ')'
            );
            $this->downloadIcal($path, $address);
        }

        return $this->parse($path);
    }
}
