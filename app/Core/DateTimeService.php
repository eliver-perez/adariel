<?php

namespace App\Core;

use DateTimeImmutable;
use DateTimeZone;

class DateTimeService
{
    private DateTimeZone $timezone;
    private DateTimeZone $utc;

    public function __construct(string $timezone)
    {
        $this->timezone = new DateTimeZone($timezone);
        $this->utc = new DateTimeZone('UTC');
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timezone);
    }

    public function nowFormatted(
        string $format = 'Y-m-d H:i:s'
    ): string {
        return $this->now()->format($format);
    }

    public function nowUtc(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->utc);
    }

    public function nowUtcFormatted(
        string $format = 'Y-m-d H:i:s'
    ): string {
        return $this->nowUtc()->format($format);
    }

    public function toUtc(
        string $date,
        int $minutes
    ): DateTimeImmutable {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        $datetime = new DateTimeImmutable(
            sprintf(
                '%s %02d:%02d:00',
                $date,
                $hours,
                $mins
            ),
            $this->timezone
        );

        return $datetime->setTimezone($this->utc);
    }

    public function fromUtc(
        string $datetime
    ): DateTimeImmutable {
        return (new DateTimeImmutable(
            $datetime,
            $this->utc
        ))->setTimezone($this->timezone);
    }

    public function fromUtcFormatted(
        string $datetime,
        string $format = 'd/m/Y H:i A'
    ): string {
        return $this->fromUtc($datetime)->format($format);
    }
}