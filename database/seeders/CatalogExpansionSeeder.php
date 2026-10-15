<?php

namespace Database\Seeders;

use App\Models\{Category, Fleet, Yatra};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogExpansionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Yatra::where('description', 'like', '%Sample description%')->get() as $yatra) {
            $yatra->update([
                'description' => '<p>Follow a '.$yatra->duration_days.'-day pilgrimage route through <strong>'.$yatra->destination.'</strong>, with time for planned darshan, local transfers and rest between visits. Final hotel, meal and transport arrangements can be confirmed for your travel dates and group size.</p>',
            ]);
        }

        $yatras = [
            [
                'Kashi Vishwanath & Vindhyachal Devi Yatra', 'north', 'Varanasi, Vindhyachal', 4, 3, 17800,
                'A short pilgrimage combining Kashi Vishwanath darshan and a visit to Vindhyavasini Devi in Vindhyachal.',
                "Day 1: Arrive in Varanasi and settle in.\nDay 2: Visit Kashi Vishwanath and the ghats of Varanasi.\nDay 3: Travel to Vindhyachal for Vindhyavasini Devi darshan.\nDay 4: Depart from Varanasi.",
                'October to March',
            ],
            [
                'Braj Bhoomi Mathura Vrindavan Yatra', 'north', 'Mathura, Vrindavan, Govardhan, Barsana', 5, 4, 22400,
                'Explore the principal Krishna pilgrimage sites across Mathura and the Braj region, including Vrindavan, Govardhan and Barsana.',
                "Day 1: Arrive in Mathura and visit Shri Krishna Janmabhoomi.\nDay 2: Visit Vrindavan temples and attend evening aarti.\nDay 3: Travel through Govardhan and nearby Braj sites.\nDay 4: Visit Barsana and return to Mathura.\nDay 5: Depart after breakfast.",
                'October to March',
            ],
            [
                'Tirupati Balaji & Kanchipuram Yatra', 'south', 'Tirupati, Kanchipuram, Chennai', 5, 4, 26800,
                'A South India temple journey pairing Tirumala Venkateswara darshan with the historic temples of Kanchipuram.',
                "Day 1: Arrive in Chennai and transfer to Tirupati.\nDay 2: Travel to Tirumala for Venkateswara darshan, subject to slot availability.\nDay 3: Local temples in Tirupati and time to rest.\nDay 4: Visit Kanchipuram temples and continue to Chennai.\nDay 5: Depart from Chennai.",
                'November to February',
            ],
            [
                'Shirdi Nashik Trimbakeshwar Yatra', 'west', 'Shirdi, Shani Shingnapur, Nashik, Trimbakeshwar', 5, 4, 21900,
                'Visit Sai Baba’s samadhi in Shirdi, Shani Shingnapur and the Nashik-Trimbakeshwar pilgrimage area.',
                "Day 1: Arrive in Shirdi and visit Sai Baba Samadhi Mandir.\nDay 2: Visit Shani Shingnapur and return to Shirdi.\nDay 3: Travel to Nashik and visit local sacred sites.\nDay 4: Visit Trimbakeshwar and return to Nashik.\nDay 5: Depart from Nashik.",
                'October to March',
            ],
            [
                'Nepal Sacred Cities: Kathmandu, Janakpur & Lumbini', 'intl', 'Kathmandu, Janakpur, Lumbini', 9, 8, 56800,
                'A cross-country Nepal pilgrimage linking Pashupatinath in Kathmandu with Janakpur and the birthplace of the Buddha at Lumbini.',
                "Day 1: Arrive in Kathmandu and transfer to your hotel.\nDay 2: Visit Pashupatinath and key Kathmandu heritage sites.\nDay 3: Continue local darshan and prepare for the road journey.\nDay 4: Travel to Janakpur.\nDay 5: Visit Janaki Mandir and local sacred sites.\nDay 6: Travel to Lumbini.\nDay 7: Visit the Maya Devi Temple and monastic zone.\nDay 8: Return transfer or onward travel arrangements.\nDay 9: Depart as per your confirmed route.",
                'October to April',
            ],
        ];

        foreach ($yatras as $index => [$title, $region, $destination, $days, $nights, $price, $summary, $itinerary, $bestTime]) {
            $category = $region === 'intl'
                ? Category::where('type', 'international')->firstOrFail()
                : Category::where('region', $region)->firstOrFail();

            Yatra::updateOrCreate(['slug' => Str::slug($title)], [
                'category_id' => $category->id,
                'title' => $title,
                'destination' => $destination,
                'duration_days' => $days,
                'duration_nights' => $nights,
                'price' => $price,
                'image' => 'https://picsum.photos/seed/yatra-extra-'.($index + 1).'/1000/700',
                'short_description' => $summary,
                'description' => '<p>'.$summary.' The itinerary allows time for darshan and local transfers; final timings and arrangements are confirmed around your travel dates.</p>',
                'highlights' => "Temple darshan at the principal stops\nComfortable road transfers between destinations\nTime for rest and local visits\nFlexible arrangements for your group",
                'itinerary' => $itinerary,
                'inclusions' => "Accommodation on twin sharing basis\nDaily breakfast\nRoad transport as per itinerary\nDriver, toll and parking charges",
                'exclusions' => "Travel to the first destination\nLunch and personal expenses\nSpecial darshan tickets unless confirmed\nAnything not mentioned in inclusions",
                'best_time' => $bestTime,
                'gallery' => [
                    'https://picsum.photos/seed/yatra-extra-'.($index + 1).'-a/900/700',
                    'https://picsum.photos/seed/yatra-extra-'.($index + 1).'-b/900/700',
                ],
                'is_featured' => false,
                'is_special' => false,
                'is_active' => true,
                'sort_order' => 20 + $index,
            ]);
        }

        $vehicles = [
            ['Toyota Innova Hycross', 'MPV', 6, '6 Guests', '4 Bags', 'fleet-extra-hycross', "Hybrid powertrain\nAir-conditioned cabin\nSuitable for family pilgrimage routes", 6800, 250],
            ['Kia Carens', 'Premium MPV', 6, '6 Guests', '3 Bags', 'fleet-extra-carens', "Comfortable second-row seating\nAir conditioning for all rows\nPractical family luggage space", 5200, 210],
            ['Force Urbania 12-Seater', 'Premium Tempo Traveller', 12, '12 Guests', '10 Bags', 'fleet-extra-urbania', "Forward-facing passenger seats\nAir-conditioned high-roof cabin\nGood fit for group circuits", 9800, 320],
            ['Mini Coach 22-Seater', 'Mini Coach', 22, '22 Guests', '18 Bags', 'fleet-extra-minicoach', "Reclining passenger seats\nAir-conditioned cabin\nFor larger pilgrimage groups", 14800, 420],
        ];

        foreach ($vehicles as $index => [$name, $class, $seats, $capacity, $bags, $imageSeed, $features, $rate, $extraKmRate]) {
            Fleet::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'vehicle_class' => $class,
                'seat_count' => $seats,
                'capacity' => $capacity,
                'baggage_capacity' => $bags,
                'fuel_type' => 'Diesel',
                'transmission' => 'Manual',
                'air_conditioning' => true,
                'driver_included' => true,
                'per_day_rate' => $rate,
                'included_km_per_day' => 250,
                'extra_km_rate' => $extraKmRate,
                'features' => $features,
                'details' => 'Chauffeur-driven, air-conditioned transport for multi-day pilgrimage and intercity routes. Final availability and quote depend on route, dates and total distance.',
                'faqs' => json_encode([
                    ['question' => 'Is a driver included?', 'answer' => 'Yes, the rental includes an experienced driver.'],
                    ['question' => 'How is the final quote calculated?', 'answer' => 'The quote is confirmed for your route, dates, distance and trip duration.'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'image' => 'https://picsum.photos/seed/'.$imageSeed.'/1000/700',
                'description' => $features,
                'is_active' => true,
                'is_popular' => $index < 2,
                'sort_order' => 4 + $index,
            ]);
        }
    }
}