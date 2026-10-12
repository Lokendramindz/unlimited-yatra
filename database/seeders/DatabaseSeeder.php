<?php

namespace Database\Seeders;

use App\Models\{Affiliation, Banner, Category, Faq, Feature, Fleet, Guide, Page, Post, Setting, User, Yatra};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $img = fn (string $seed, int $w = 1000, int $h = 700) => "https://picsum.photos/seed/$seed/$w/$h";

        // Admin user — CHANGE THE PASSWORD after first login
        User::updateOrCreate(['email' => 'admin@unlimitedyatra.com'], [
            'name' => 'Admin', 'password' => Hash::make('Admin@12345'), 'is_admin' => true,
        ]);

        // Settings
        $settings = [
            'site_name' => 'Unlimited Yatra',
            'tagline' => 'Tailor made Authentic Yatras Since 2006',
            'phone' => '+91 98765 43210',
            'whatsapp' => '919876543210',
            'email' => 'info@unlimitedyatra.com',
            'address' => 'Jaipur, Rajasthan, India',
            'about_title' => 'Your trusted partner for authentic spiritual journeys',
            'about_text' => "Since 2006 Unlimited Yatra has been designing tailor made pilgrimage journeys across India, Nepal and Tibet. From the ghats of Varanasi and the Sangam at Prayagraj to Kedarnath, Jagannath Puri and the Maha Kumbh, our yatra leaders take care of every detail - comfortable stays, sattvic food, reliable transport and devoted guides - so you can focus on your darshan.",
            'map_embed' => 'https://www.google.com/maps?q=Jaipur,Rajasthan&output=embed',
            'facebook' => '#', 'instagram' => '#', 'youtube' => '#',
        ];
        foreach ($settings as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        // Categories: 4 domestic regions + 1 international
        $cats = [
            ['East India', 'domestic', 'east'], ['West India', 'domestic', 'west'],
            ['North India', 'domestic', 'north'], ['South India', 'domestic', 'south'],
            ['International (Nepal, Tibet & more)', 'international', null],
        ];
        $c = [];
        foreach ($cats as $i => [$name, $type, $region]) {
            $c[$region ?? 'intl'] = Category::create([
                'name' => $name, 'slug' => Str::slug($name), 'type' => $type, 'region' => $region,
                'image' => $img('cat'.$i, 600, 400), 'sort_order' => $i,
            ]);
        }

        // Banners
        foreach ([
            ['Experience the Divine in Varanasi', 'Ganga aarti, ancient temples & timeless rituals', 'varanasi'],
            ['Char Dham & Kedarnath Yatra', 'A journey to the abode of Lord Shiva', 'kedarnath'],
            ['Jagannath Puri & Maha Kumbh', 'Join millions on the most sacred pilgrimages', 'puri'],
        ] as $i => [$t, $s, $seed]) {
            Banner::create(['title' => $t, 'subtitle' => $s, 'image' => $img($seed, 1920, 900),
                'button_text' => 'Explore Yatras', 'button_link' => '/yatras', 'sort_order' => $i]);
        }

        // Yatras
        $yatras = [
            ['Varanasi - Prayagraj - Ayodhya Yatra', 'Varanasi, Prayagraj, Ayodhya', 7, 6, 28500, 'north', true, false, null],
            ['Kedarnath Badrinath Char Dham', 'Kedarnath, Badrinath, Gangotri, Yamunotri', 10, 9, 42000, 'north', true, false, null],
            ['Jagannath Puri & Konark Yatra', 'Puri, Konark, Bhubaneswar', 5, 4, 19500, 'east', true, false, null],
            ['Gaya - Bodhgaya Pind Daan Yatra', 'Gaya, Bodhgaya', 4, 3, 14500, 'east', true, false, null],
            ['Dwarka Somnath Nageshwar Yatra', 'Dwarka, Somnath, Nageshwar', 6, 5, 24500, 'west', true, false, null],
            ['Rameshwaram Madurai Kanyakumari', 'Rameshwaram, Madurai, Kanyakumari', 8, 7, 31000, 'south', true, false, null],
            ['Kailash Mansarovar Yatra via Nepal', 'Kathmandu, Mansarovar, Kailash', 14, 13, 185000, 'intl', true, false, null],
            ['Nepal Pashupatinath Muktinath Yatra', 'Kathmandu, Pokhara, Muktinath', 7, 6, 38000, 'intl', true, false, null],
            ['Jain Tirth Yatra - Palitana & Girnar', 'Palitana, Girnar, Shankheshwar', 6, 5, 22000, 'west', false, true, 'Jain Tirth Yatra'],
            ['Buddhist Circuit Yatra', 'Lumbini, Bodhgaya, Sarnath, Kushinagar', 9, 8, 46000, 'east', false, true, 'Buddhist Yatra'],
            ['Himalayan Monasteries Tour', 'Dharamshala, Tawang, Spiti', 8, 7, 39000, 'north', false, true, 'Monasteries Tour'],
            ['Nav Durga Devi Yatra', 'Vindhyachal, Kamakhya, Jwala Ji', 9, 8, 35000, 'north', false, true, 'Nav Durga / Devi Yatra'],
            ['Vaishno Devi Yatra', 'Katra, Vaishno Devi, Patnitop', 4, 3, 16500, 'north', false, true, 'Vaishno Devi'],
            ['Hemkund Sahib Yatra', 'Haridwar, Govindghat, Hemkund Sahib', 6, 5, 27500, 'north', false, true, 'Hemkund Sahib'],
            ['Ganga Sagar Yatra', 'Kolkata, Gangasagar', 3, 2, 12500, 'east', false, true, 'Ganga Sagar'],
        ];
        foreach ($yatras as $i => [$title, $dest, $d, $n, $price, $reg, $feat, $spec, $stype]) {
            Yatra::create([
                'category_id' => $c[$reg]->id, 'title' => $title, 'slug' => Str::slug($title),
                'destination' => $dest, 'duration_days' => $d, 'duration_nights' => $n, 'price' => $price,
                'image' => $img('yatra'.$i), 'is_featured' => $feat, 'is_special' => $spec, 'special_type' => $stype,
                'short_description' => "A carefully planned yatra covering $dest with comfortable stay, transport and expert yatra leader.",
                'description' => "<p>Sample description for <strong>$title</strong>. Replace this from the admin panel with the real details.</p>",
                'highlights' => "Darshan at major temples\nComfortable hotels / dharamshalas\nSattvic meals\nExperienced yatra leader",
                'itinerary' => "Day 1: Arrival and local darshan\nDay 2: Main temple darshan\nDay 3: Excursions and rituals\nLast day: Departure",
                'inclusions' => "Accommodation on twin sharing basis\nDaily sattvic breakfast and dinner\nAC transport as per itinerary\nExperienced yatra leader throughout\nAll toll, parking and driver charges",
                'exclusions' => "Personal expenses\nLunch (unless mentioned)\nAir / train fare to the starting point\nTemple special darshan tickets\nAnything not mentioned in inclusions",
                'best_time' => 'October to March',
                'gallery' => [$img('yatra'.$i.'a', 900, 700), $img('yatra'.$i.'b', 900, 700), $img('yatra'.$i.'c', 900, 700)],
                'sort_order' => $i,
            ]);
        }

        // CMS pages
        Page::create([
            'title' => 'About Us', 'slug' => 'about-us', 'subtitle' => 'Tailor made Authentic Yatras Since 2006',
            'image' => $img('aboutbanner', 1600, 700),
            'content' => '<h2>Our story</h2><p>Unlimited Yatra was founded in 2006 with one aim - to make sacred journeys comfortable, authentic and meaningful. Over the years we have guided thousands of devotees to Varanasi, Prayagraj, Kedarnath, Jagannath Puri, Nepal and Tibet.</p><h2>What we do</h2><p>Every yatra is tailor made around your group, dates and devotion. We handle stay, sattvic food, transport and darshan arrangements, led by experienced yatra leaders.</p><h2>Our promise</h2><ul><li>Authentic, traditionally arranged yatras</li><li>Transparent pricing and no hidden costs</li><li>Care for senior citizens and families</li></ul><p><em>Replace this text from Admin &rarr; Pages.</em></p>',
        ]);
        Page::create(['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<p>Sample privacy policy. Edit from Admin &rarr; Pages.</p>']);
        Page::create(['title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'content' => '<p>Sample terms and conditions. Edit from Admin &rarr; Pages.</p>']);

        // Guides
        foreach ([['Pt. Ramesh Sharma', 'Senior Yatra Leader'], ['Swami Anand Giri', 'Spiritual Guide'], ['Sunita Devi', 'Women Pilgrim Coordinator'], ['Dorje Lama', 'Tibet & Nepal Guide']] as $i => [$n, $d]) {
            Guide::create(['name' => $n, 'designation' => $d, 'image' => $img('guide'.$i, 500, 600), 'bio' => 'Years of experience leading devotees on sacred journeys.', 'sort_order' => $i]);
        }

        // Posts
        foreach (['Best time to visit Kedarnath', 'Maha Kumbh: A first-timer\'s guide', 'Varanasi Ganga Aarti - what to expect'] as $i => $t) {
            Post::create(['title' => $t, 'slug' => Str::slug($t), 'image' => $img('post'.$i, 800, 500),
                'excerpt' => 'Practical tips and spiritual insights from our yatra experts.',
                'body' => '<p>Sample article. Edit from the admin panel.</p>', 'published_at' => now()->subDays($i * 5)]);
        }

        // FAQs
        foreach ([
            ['Are your yatras suitable for senior citizens?', 'Yes. We plan comfortable pacing, assisted darshan where possible and suitable transport for elders.'],
            ['Is vegetarian food provided?', 'All our yatras include pure vegetarian (sattvic) meals. Jain food is available on request.'],
            ['Can the yatra be customised?', 'Absolutely - every yatra is tailor made for your group, dates and budget.'],
            ['How do I book?', 'Send us an enquiry or WhatsApp us. We will share the itinerary and confirm after a small advance.'],
        ] as $i => [$q, $a]) {
            Faq::create(['question' => $q, 'answer' => $a, 'sort_order' => $i]);
        }

        // Why us
        foreach ([
            ['🛕', 'Authentic Experiences', 'Rituals and darshan arranged the traditional way.'],
            ['🧑‍🤝‍🧑', 'Expert Yatra Leaders', 'Knowledgeable leaders accompany every group.'],
            ['🚌', 'Comfortable Transport', 'Our own well-maintained fleet.'],
            ['🙏', 'Since 2006', 'Trusted by thousands of pilgrims.'],
        ] as $i => [$ic, $t, $d]) {
            Feature::create(['icon' => $ic, 'title' => $t, 'description' => $d, 'sort_order' => $i]);
        }

        // Fleet
        foreach ([['Sedan (Dzire / Etios)', '4 Seater'], ['Innova Crysta', '6 Seater'], ['Tempo Traveller', '12-17 Seater'], ['Luxury Coach', '35-45 Seater']] as $i => [$n, $cap]) {
            Fleet::create(['name' => $n, 'capacity' => $cap, 'image' => $img('fleet'.$i, 800, 500), 'description' => 'Clean, AC and driven by experienced drivers.', 'sort_order' => $i]);
        }

        // Affiliations
        foreach (['IATA', 'Ministry of Tourism', 'TAAI', 'ADTOI', 'Incredible India', 'IATO'] as $i => $n) {
            Affiliation::create(['name' => $n, 'image' => $img('aff'.$i, 240, 120), 'sort_order' => $i]);
        }
    }
}
