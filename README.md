# Unlimited Yatra - Laravel 12 website + admin dashboard

Spiritual yatra tour website ("Tailor made Authentic Yatras Since 2006") built from the *Unlimited-Yatra-Homepage-Ideas* document.

## Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
# Create a MySQL database named unlimitedyatradb, then run migrations:
php artisan migrate --seed
php artisan storage:link
php artisan serve
```
- Website: http://localhost:8000
- Admin: http://localhost:8000/admin  -  `admin@unlimitedyatra.com` / `Admin@12345`  (**change this password!**)

Requires PHP 8.2+ and Composer. Tailwind / Swiper / Alpine load from CDN (no npm build needed).

## Homepage sections (as per document)
1 Banner slider · 2 About + Enquiry form · (Explore by destination) · 3 Popular Yatras · 4 Special Yatras carousel ·
5 Guides / Yatra leaders · 6 News & Blogs · 7 FAQ + Why travel with us · 8 Transport fleet slider ·
9 Recognition & affiliations (running logos) · 10 Newsletter + Footer.
WhatsApp / Call / Enquire buttons float on every page. Yatra cards show duration + destination on the image.
Categories: Indian (East / West / North / South) and International.

## Admin panel
Dashboard, Yatras, Categories, Banners, Guides, Blog, FAQs, Why-us points, Fleet, Affiliations,
Enquiries (with status), Newsletter subscribers, Site settings (phone, WhatsApp, address, social links, about text).
All CRUD screens are generated from `config/admin.php` - add a resource there (+ model & migration) for a new screen.

## Notes
- Demo images are placeholders (picsum.photos). Upload real Varanasi / Kedarnath / Puri / Maha Kumbh images from admin.
- Uploaded images are stored in `storage/app/public` (needs `php artisan storage:link`).
- Fleet demo images are stored locally as WebP files and sourced from Unsplash under its free license. Replace them with photos of your actual vehicles before launch:
    - Sedan: [Thein Than Zaw](https://unsplash.com/photos/white-sedan-on-wet-road-beside-green-grass-kb9dTYzZuiQ)
    - Innova: [Zoshua Colah](https://unsplash.com/photos/a-white-car-parked-on-a-city-street-i7iC4G_FjQA)
    - Passenger vans: [Markus Winkler](https://unsplash.com/photos/white-vans-parked-in-mossingen-3vlGNkDep4E)
    - Coach: [Jonathan Borba](https://unsplash.com/photos/parked-blue-and-black-compertur-bus-T5jzpRTVF1U)
- For an existing installation, apply database changes with `php artisan migrate`, then populate missing fleet demo details with `php artisan db:seed --class=FleetDemoContentSeeder`.
- For production: `APP_ENV=production APP_DEBUG=false`, run `php artisan config:cache route:cache view:cache`,
  and consider replacing the Tailwind CDN with a Vite build for best speed.
- If you already ran migrations earlier, run `php artisan migrate:fresh --seed` (new columns/tables were added).

## Inner pages
Common banner with breadcrumb on every page. Yatra listing (`/yatras`) with sidebar filters (search, duration, destination),
sorting and pagination; Yatra detail with quick facts, tabs (Overview / Itinerary / Inclusions / Gallery with lightbox),
sticky enquiry form + WhatsApp button, related yatras; About Us / Privacy / Terms (`/page/{slug}`, editable in Admin -> Pages);
Our Guides (`/our-guides`), Our Fleet (`/our-fleet`), FAQs (`/faqs`), Blog list + detail with sidebar & share buttons,
Contact page with info cards, enquiry form and Google Map (set `map_embed` in Site Settings).
