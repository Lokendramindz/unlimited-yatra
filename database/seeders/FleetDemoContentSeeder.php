<?php

namespace Database\Seeders;

use App\Models\Fleet;
use Illuminate\Database\Seeder;

class FleetDemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            'Sedan (Dzire / Etios)' => ['fleet-demo/sedan.webp', 'Sedan', 4, '2 Bags', "AC and GPS included\nIdeal for couples and solo travel\nCity and short-distance tours"],
            'Innova Crysta' => ['fleet-demo/innova.webp', 'MPV', 6, '4 Bags', "Most booked for families\nSpacious boot for luggage\nAll India long-distance"],
            'Tempo Traveller' => ['fleet-demo/van.webp', 'Tempo Traveller', 12, '10 Bags', "Group-friendly seating\nAir-conditioned cabin\nGreat for pilgrimage circuits"],
            'Luxury Coach' => ['fleet-demo/coach.webp', 'Coach', 35, '35 Bags', "Comfortable reclining seats\nExperienced coach drivers\nSuitable for large groups"],
        ];

        foreach ($vehicles as $name => [$image, $class, $seats, $baggage, $features]) {
            $fleet = Fleet::where('name', $name)->first();
            if (! $fleet) {
                continue;
            }

            $attributes = [];
            $hasPlaceholderImage = ! $fleet->image || str_contains($fleet->image, 'picsum.photos');
            if ($hasPlaceholderImage) {
                $attributes['image'] = $image;
                $attributes['is_popular'] = true;
            }
            if (! $fleet->baggage_capacity) {
                $attributes['baggage_capacity'] = $baggage;
            }
            if (! $fleet->features) {
                $attributes['features'] = $features;
            }
            if (! $fleet->details) {
                $attributes['details'] = 'Clean, air-conditioned and driven by experienced drivers for comfortable pilgrimage travel.';
            }
            if (! $fleet->vehicle_class) {
                $attributes['vehicle_class'] = $class;
            }
            if (! $fleet->seat_count) {
                $attributes['seat_count'] = $seats;
            }
            if ($fleet->faqs === null || $fleet->faqs === '') {
                $attributes['faqs'] = json_encode([
                    ['question' => 'Is a driver included?', 'answer' => 'Yes, an experienced driver is included with this vehicle.'],
                    ['question' => 'How is the rental price calculated?', 'answer' => 'We confirm the quote for your route, date, duration and expected distance before booking.'],
                    ['question' => 'Are tolls, parking and overnight charges included?', 'answer' => 'Any route-specific tolls, parking or overnight charges are explained in your final trip quote.'],
                    ['question' => 'Can I request a multi-day pilgrimage booking?', 'answer' => 'Yes. Share your itinerary and dates in the enquiry form to receive an availability and route quote.'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            if ($attributes) {
                $fleet->update($attributes);
            }
        }
    }
}