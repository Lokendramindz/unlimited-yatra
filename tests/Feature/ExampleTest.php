<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Fleet;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_seo_metadata(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('TravelAgency', false)
            ->assertSee('Travel India in Comfort &amp; Style', false);
    }

    public function test_admin_can_save_a_yatra_with_daily_itinerary_content(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.resource.create', 'yatras'))
            ->assertOk()
            ->assertSee('data-add-day', false)
            ->assertSee('itinerary_days[0][title]', false);

        $days = [
            ['title' => 'Day 1 - Arrival', 'content' => 'Arrive and visit the temple.'],
            ['title' => 'Day 2 - Darshan', 'content' => 'Attend morning darshan and depart.'],
        ];

        $this->post(route('admin.resource.store', 'yatras'), [
            'title' => 'Test pilgrimage itinerary',
            'destination' => 'Varanasi',
            'duration_days' => 2,
            'duration_nights' => 1,
            'is_active' => '1',
            'itinerary_days' => $days,
        ])->assertRedirect(route('admin.resource.index', 'yatras'));

        $this->assertDatabaseHas('yatras', [
            'slug' => 'test-pilgrimage-itinerary',
            'itinerary' => json_encode($days, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $this->get(route('yatras.show', 'test-pilgrimage-itinerary'))
            ->assertOk()
            ->assertSee('href="#overview"', false)
            ->assertSee('<section id="itinerary"', false)
            ->assertDontSee('x-show="tab', false)
            ->assertSee('Share your experience')
            ->assertSee('Day 1 - Arrival')
            ->assertSee('Arrive and visit the temple.')
            ->assertSee('Day 2 - Darshan')
            ->assertSee('Attend morning darshan and depart.');

        $yatraId = \App\Models\Yatra::where('slug', 'test-pilgrimage-itinerary')->value('id');
        $this->from(route('yatras.show', 'test-pilgrimage-itinerary'))
            ->post(route('reviews.yatras.store', 'test-pilgrimage-itinerary'), [
                'reviewer_name' => 'Yatra Guest',
                'rating' => 4,
                'body' => 'The itinerary and group support were very helpful.',
            ])->assertRedirect(route('yatras.show', 'test-pilgrimage-itinerary'));
        $this->assertDatabaseHas('reviews', ['yatra_id' => $yatraId, 'rating' => 4, 'is_approved' => false]);

        $this->get(route('yatras.index', ['view' => 'list']))
            ->assertOk()
            ->assertSee('aria-label="List view"', false)
            ->assertSee('flex flex-col gap-4', false);
    }

    public function test_transport_listing_detail_and_booking_enquiry_work(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->get(route('admin.resource.create', 'fleets'))
            ->assertOk()
            ->assertSee('name="baggage_capacity"', false)
            ->assertSee('name="seat_count"', false)
            ->assertSee('name="features"', false)
            ->assertSee('name="is_popular"', false)
            ->assertSee('data-faq-editor', false);

        $this->post(route('admin.resource.store', 'fleets'), [
            'name' => 'Admin Fleet Vehicle',
            'vehicle_class' => 'MPV',
            'seat_count' => 6,
            'capacity' => '6 Guests',
            'baggage_capacity' => '4 Bags',
            'air_conditioning' => '1',
            'driver_included' => '1',
            'description' => 'A comfortable vehicle.',
            'features' => "Air conditioned\nGPS enabled",
            'details' => 'Detailed admin vehicle description.',
            'is_popular' => '1',
            'is_active' => '1',
            'faqs' => [['question' => 'Can I book a multi-day route?', 'answer' => 'Yes, request a route-specific quote.']],
        ])->assertRedirect(route('admin.resource.index', 'fleets'));

        $this->assertDatabaseHas('fleets', [
            'slug' => 'admin-fleet-vehicle',
            'vehicle_class' => 'MPV',
            'seat_count' => 6,
            'baggage_capacity' => '4 Bags',
            'is_popular' => true,
        ]);

        $this->assertDatabaseHas('fleets', [
            'slug' => 'admin-fleet-vehicle',
            'faqs' => json_encode([['question' => 'Can I book a multi-day route?', 'answer' => 'Yes, request a route-specific quote.']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Admin Fleet Vehicle')
            ->assertSee(route('fleet.show', 'admin-fleet-vehicle'));

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee(route('fleet.show', 'admin-fleet-vehicle'));

        $this->get(route('fleet', ['vehicle_class' => 'MPV']))
            ->assertOk()
            ->assertSee('Admin Fleet Vehicle')
            ->assertDontSee('Test Pilgrimage Van');

        $this->get(route('fleet', ['passengers' => 10]))
            ->assertOk()
            ->assertDontSee('Admin Fleet Vehicle')
            ->assertDontSee('Test Pilgrimage Van');

        $this->get(route('fleet', ['popular' => 1]))
            ->assertOk()
            ->assertSee('Admin Fleet Vehicle');

        $this->get('/our-fleet')->assertStatus(301)->assertRedirect(route('fleet'));

        $fleet = Fleet::create([
            'name' => 'Test Pilgrimage Van',
            'slug' => 'test-pilgrimage-van',
            'vehicle_class' => 'Tempo Traveller',
            'seat_count' => 8,
            'capacity' => '8 Guests',
            'baggage_capacity' => '6 Bags',
            'description' => 'Comfortable vehicle for pilgrimage routes.',
            'details' => 'Air-conditioned vehicle with an experienced driver.',
            'features' => "Air conditioned\nGPS enabled",
            'is_active' => true,
            'is_popular' => true,
        ]);

        $this->get(route('fleet'))
            ->assertOk()
            ->assertSee('6 seats')
            ->assertDontSee('6 Guests')
            ->assertSee('Test Pilgrimage Van')
            ->assertSee(route('fleet.show', $fleet->slug));

        $this->get(route('fleet', ['view' => 'list']))
            ->assertOk()
            ->assertSee('aria-label="List view"', false)
            ->assertSee('flex flex-col gap-4', false);

        $this->get('/our-fleet/'.$fleet->slug)
            ->assertStatus(301)
            ->assertRedirect(route('fleet.show', $fleet->slug));

        $this->get(route('fleet.show', $fleet->slug))
            ->assertOk()
            ->assertSee('Request a vehicle booking')
            ->assertSee('Air conditioned')
            ->assertSee('Vehicle rental FAQs')
            ->assertSee('How is the rental price calculated?')
            ->assertSee('FAQPage', false)
            ->assertSee('Share your experience')
            ->assertSee('name="fleet_id" value="'.$fleet->id.'"', false);

        $reviewData = [
            'reviewer_name' => 'Asha Sharma',
            'email' => 'asha@example.com',
            'rating' => 5,
            'title' => 'Comfortable pilgrimage ride',
            'body' => 'The vehicle was clean and the driver handled our pilgrimage route very well.',
        ];
        $this->from(route('fleet.show', $fleet->slug))
            ->post(route('reviews.fleet.store', $fleet->slug), $reviewData)
            ->assertRedirect(route('fleet.show', $fleet->slug));

        $review = Review::where('fleet_id', $fleet->id)->firstOrFail();
        $this->assertFalse($review->is_approved);
        $this->get(route('fleet.show', $fleet->slug))->assertDontSee('Comfortable pilgrimage ride');

        $this->put(route('admin.resource.update', ['reviews', $review->id]), $reviewData + [
            'yatra_id' => '',
            'fleet_id' => $fleet->id,
            'is_approved' => '1',
            'is_featured' => '1',
        ])->assertRedirect(route('admin.resource.index', 'reviews'));

        $this->get(route('fleet.show', $fleet->slug))
            ->assertOk()
            ->assertSee('Comfortable pilgrimage ride')
            ->assertSee('Featured review')
            ->assertSee('1 approved review(s)');

        $this->from(route('fleet.show', $fleet->slug))->post(route('enquiry.store'), [
            'fleet_id' => $fleet->id,
            'name' => 'Pilgrim Test',
            'phone' => '+919876543210',
            'email' => 'pilgrim@example.com',
            'travel_date' => '2026-12-01',
            'travellers' => 6,
            'pickup_location' => 'Jaipur Airport',
            'dropoff_location' => 'Varanasi Hotel',
            'message' => 'One-way pilgrimage transport booking.',
        ])->assertRedirect(route('fleet.show', $fleet->slug));

        $this->assertDatabaseHas('enquiries', [
            'fleet_id' => $fleet->id,
            'pickup_location' => 'Jaipur Airport',
            'dropoff_location' => 'Varanasi Hotel',
            'name' => 'Pilgrim Test',
        ]);

        $this->get(route('admin.resource.index', 'enquiries'))
            ->assertOk()
            ->assertSee('Test Pilgrimage Van')
            ->assertSee('Jaipur Airport')
            ->assertSee('Varanasi Hotel');
    }
}
