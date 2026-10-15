<?php

namespace Tests\Unit;

use App\Models\Yatra;
use PHPUnit\Framework\TestCase;

class YatraItineraryTest extends TestCase
{
    public function test_it_reads_structured_day_content(): void
    {
        $yatra = new Yatra([
            'itinerary' => json_encode([
                ['title' => 'Day 1 - Arrival', 'content' => 'Check in and visit the temple.'],
                ['title' => 'Day 2 - Darshan', 'content' => 'Attend morning darshan and depart.'],
            ], JSON_THROW_ON_ERROR),
        ]);

        $this->assertSame([
            ['title' => 'Day 1 - Arrival', 'content' => 'Check in and visit the temple.'],
            ['title' => 'Day 2 - Darshan', 'content' => 'Attend morning darshan and depart.'],
        ], $yatra->itinerary_days);
    }

    public function test_it_keeps_legacy_line_based_itineraries_readable(): void
    {
        $yatra = new Yatra(['itinerary' => "Day 1: Arrival and local darshan\nDay 2: Main temple darshan"]);

        $this->assertSame([
            ['title' => 'Day 1', 'content' => 'Arrival and local darshan'],
            ['title' => 'Day 2', 'content' => 'Main temple darshan'],
        ], $yatra->itinerary_days);
    }
}