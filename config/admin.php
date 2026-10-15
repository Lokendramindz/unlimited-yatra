<?php

/*
 | Admin panel resource definitions.
 | Add a new entry here (+ model & migration) and a full CRUD screen appears automatically.
 | Field types: text, textarea, number, select, image, checkbox, date, email
 */

$active = ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox'];
$sort   = ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0'];

return [
    'resources' => [

        'yatras' => [
            'model' => 'Yatra', 'label' => 'Yatras', 'singular' => 'Yatra', 'icon' => '🛕',
            'order' => ['id', 'desc'],
            'columns' => [['image', 'Image', 'image'], ['title', 'Title'], ['destination', 'Destination'], ['duration_label', 'Duration'], ['category.name', 'Category'], ['is_featured', 'Featured', 'bool'], ['is_special', 'Special', 'bool'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'slug', 'label' => 'Slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'category_id', 'label' => 'Category', 'type' => 'select', 'relation' => ['Category', 'name'], 'rules' => 'nullable|exists:categories,id'],
                ['name' => 'destination', 'label' => 'Destination', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'duration_days', 'label' => 'Duration (days)', 'type' => 'number', 'rules' => 'required|integer|min:1'],
                ['name' => 'duration_nights', 'label' => 'Duration (nights)', 'type' => 'number', 'rules' => 'nullable|integer|min:0'],
                ['name' => 'price', 'label' => 'Starting price (₹)', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                ['name' => 'image', 'label' => 'Main image', 'type' => 'image'],
                ['name' => 'short_description', 'label' => 'Short description', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:500'],
                ['name' => 'description', 'label' => 'Full description', 'type' => 'textarea', 'rows' => 8],
                ['name' => 'highlights', 'label' => 'Highlights (one per line)', 'type' => 'textarea', 'rows' => 5],
                ['name' => 'itinerary', 'label' => 'Day-by-day itinerary', 'type' => 'itinerary'],
                ['name' => 'inclusions', 'label' => 'Inclusions (one per line)', 'type' => 'textarea', 'rows' => 5],
                ['name' => 'exclusions', 'label' => 'Exclusions (one per line)', 'type' => 'textarea', 'rows' => 5],
                ['name' => 'best_time', 'label' => 'Best time to visit (e.g. Oct - Mar)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'gallery', 'label' => 'Photo gallery (select multiple images)', 'type' => 'images'],
                ['name' => 'is_featured', 'label' => 'Show in Popular / Featured section', 'type' => 'checkbox'],
                ['name' => 'is_special', 'label' => 'Show in Special Yatra slider', 'type' => 'checkbox'],
                ['name' => 'special_type', 'label' => 'Special yatra type (e.g. Jain Tirth, Buddhist, Vaishno Devi)', 'type' => 'text', 'rules' => 'nullable|max:100'],
                $active, $sort,
            ],
        ],

        'categories' => [
            'model' => 'Category', 'label' => 'Categories', 'singular' => 'Category', 'icon' => '🗂️',
            'order' => ['sort_order', 'asc'],
            'columns' => [['image', 'Image', 'image'], ['name', 'Name'], ['type', 'Type'], ['region', 'Region']],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'slug', 'label' => 'Slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['domestic' => 'Indian / Domestic', 'international' => 'International'], 'rules' => 'required|in:domestic,international'],
                ['name' => 'region', 'label' => 'Region (Domestic only)', 'type' => 'select', 'options' => ['east' => 'East India', 'west' => 'West India', 'north' => 'North India', 'south' => 'South India'], 'nullable' => true, 'rules' => 'nullable|in:east,west,north,south'],
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
                $sort,
            ],
        ],

        'banners' => [
            'model' => 'Banner', 'label' => 'Home Banners', 'singular' => 'Banner', 'icon' => '🖼️',
            'order' => ['sort_order', 'asc'],
            'columns' => [['image', 'Image', 'image'], ['title', 'Title'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'image', 'label' => 'Banner image (wide, min 1920x900)', 'type' => 'image', 'required_on_create' => true],
                ['name' => 'button_text', 'label' => 'Button text', 'type' => 'text', 'rules' => 'nullable|max:60'],
                ['name' => 'button_link', 'label' => 'Button link', 'type' => 'text', 'rules' => 'nullable|max:255'],
                $active, $sort,
            ],
        ],

        'guides' => [
            'model' => 'Guide', 'label' => 'Tour Guides / Yatra Leaders', 'singular' => 'Guide', 'icon' => '🧘',
            'order' => ['sort_order', 'asc'],
            'columns' => [['image', 'Photo', 'image'], ['name', 'Name'], ['designation', 'Designation'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'image', 'label' => 'Photo', 'type' => 'image'],
                ['name' => 'bio', 'label' => 'Short bio', 'type' => 'textarea', 'rows' => 4],
                $active, $sort,
            ],
        ],

        'posts' => [
            'model' => 'Post', 'label' => 'News & Blogs', 'singular' => 'Post', 'icon' => '📰',
            'order' => ['published_at', 'desc'],
            'columns' => [['image', 'Image', 'image'], ['title', 'Title'], ['published_at', 'Published'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'slug', 'label' => 'Slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'image', 'label' => 'Cover image', 'type' => 'image'],
                ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:500'],
                ['name' => 'body', 'label' => 'Content (HTML allowed)', 'type' => 'textarea', 'rows' => 12],
                ['name' => 'published_at', 'label' => 'Publish date', 'type' => 'date', 'rules' => 'nullable|date'],
                $active,
            ],
        ],

        'faqs' => [
            'model' => 'Faq', 'label' => 'FAQs', 'singular' => 'FAQ', 'icon' => '❓',
            'order' => ['sort_order', 'asc'],
            'columns' => [['question', 'Question'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'question', 'label' => 'Question', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'answer', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 5, 'rules' => 'required'],
                $active, $sort,
            ],
        ],

        'features' => [
            'model' => 'Feature', 'label' => 'Why Travel With Us', 'singular' => 'Point', 'icon' => '⭐',
            'order' => ['sort_order', 'asc'],
            'columns' => [['icon', 'Icon'], ['title', 'Title'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'icon', 'label' => 'Icon (emoji)', 'type' => 'text', 'rules' => 'nullable|max:16'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3],
                $active, $sort,
            ],
        ],

        'fleets' => [
            'model' => 'Fleet', 'label' => 'Transport Fleet', 'singular' => 'Vehicle', 'icon' => '🚌',
            'order' => ['sort_order', 'asc'],
            'columns' => [['image', 'Image', 'image'], ['name', 'Name'], ['capacity', 'Capacity'], ['is_popular', 'Popular', 'bool'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'name', 'label' => 'Vehicle name', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'slug', 'label' => 'URL slug (auto if empty)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'vehicle_class', 'label' => 'Vehicle class', 'type' => 'select', 'options' => ['Sedan' => 'Sedan', 'MPV' => 'MPV', 'SUV' => 'SUV', 'Tempo Traveller' => 'Tempo Traveller', 'Coach' => 'Coach'], 'nullable' => true, 'rules' => 'nullable|max:80'],
                ['name' => 'seat_count', 'label' => 'Passenger seats (number)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:100'],
                ['name' => 'capacity', 'label' => 'Passenger capacity (e.g. 4 Guests)', 'type' => 'text', 'rules' => 'nullable|max:100'],
                ['name' => 'baggage_capacity', 'label' => 'Baggage capacity (e.g. 2 Bags)', 'type' => 'text', 'rules' => 'nullable|max:100'],
                ['name' => 'fuel_type', 'label' => 'Fuel type', 'type' => 'select', 'options' => ['Diesel' => 'Diesel', 'Petrol' => 'Petrol', 'CNG' => 'CNG', 'Hybrid' => 'Hybrid', 'Electric' => 'Electric'], 'nullable' => true, 'rules' => 'nullable|max:50'],
                ['name' => 'transmission', 'label' => 'Transmission', 'type' => 'select', 'options' => ['Manual' => 'Manual', 'Automatic' => 'Automatic'], 'nullable' => true, 'rules' => 'nullable|max:50'],
                ['name' => 'air_conditioning', 'label' => 'Air conditioned', 'type' => 'checkbox'],
                ['name' => 'driver_included', 'label' => 'Driver included', 'type' => 'checkbox'],
                ['name' => 'per_day_rate', 'label' => 'Indicative rate per day (₹)', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                ['name' => 'included_km_per_day', 'label' => 'Included kilometres per day', 'type' => 'number', 'rules' => 'nullable|integer|min:0|max:2000'],
                ['name' => 'extra_km_rate', 'label' => 'Extra rate per kilometre (₹)', 'type' => 'number', 'rules' => 'nullable|numeric|min:0'],
                ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3],
                ['name' => 'features', 'label' => 'Vehicle features (one per line)', 'type' => 'textarea', 'rows' => 5],
                ['name' => 'details', 'label' => 'Full vehicle details', 'type' => 'textarea', 'rows' => 8],
                ['name' => 'faqs', 'label' => 'Vehicle FAQs', 'type' => 'faq_list'],
                ['name' => 'is_popular', 'label' => 'Mark as popular', 'type' => 'checkbox'],
                $active, $sort,
            ],
        ],

        'affiliations' => [
            'model' => 'Affiliation', 'label' => 'Recognition & Affiliations', 'singular' => 'Affiliation', 'icon' => '🏅',
            'order' => ['sort_order', 'asc'],
            'columns' => [['image', 'Logo', 'image'], ['name', 'Name'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'image', 'label' => 'Logo', 'type' => 'image'],
                $active, $sort,
            ],
        ],

        'pages' => [
            'model' => 'Page', 'label' => 'Pages (About, Privacy...)', 'singular' => 'Page', 'icon' => '📄',
            'order' => ['id', 'asc'],
            'columns' => [['image', 'Banner', 'image'], ['title', 'Title'], ['slug', 'URL slug'], ['is_active', 'Active', 'bool']],
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'slug', 'label' => 'Slug (auto if empty) - page opens at /page/slug', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'subtitle', 'label' => 'Subtitle (under heading)', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'image', 'label' => 'Banner image', 'type' => 'image'],
                ['name' => 'content', 'label' => 'Content (HTML allowed)', 'type' => 'textarea', 'rows' => 14],
                $active,
            ],
        ],

        'enquiries' => [
            'model' => 'Enquiry', 'label' => 'Enquiries', 'singular' => 'Enquiry', 'icon' => '📩',
            'order' => ['id', 'desc'], 'can_create' => false,
            'columns' => [['created_at', 'Received'], ['name', 'Name'], ['phone', 'Phone'], ['yatra.title', 'Yatra'], ['fleet.name', 'Vehicle'], ['pickup_location', 'Pickup'], ['dropoff_location', 'Drop-off'], ['status', 'Status']],
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:255'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'rules' => 'required|max:30'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email'],
                ['name' => 'yatra_id', 'label' => 'Yatra', 'type' => 'select', 'relation' => ['Yatra', 'title'], 'rules' => 'nullable|exists:yatras,id'],
                ['name' => 'fleet_id', 'label' => 'Transport vehicle', 'type' => 'select', 'relation' => ['Fleet', 'name'], 'rules' => 'nullable|exists:fleets,id'],
                ['name' => 'travellers', 'label' => 'Travellers', 'type' => 'number', 'rules' => 'nullable|integer|min:1'],
                ['name' => 'travel_date', 'label' => 'Travel date', 'type' => 'date', 'rules' => 'nullable|date'],
                ['name' => 'pickup_location', 'label' => 'Pickup location', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'dropoff_location', 'label' => 'Drop-off location', 'type' => 'text', 'rules' => 'nullable|max:255'],
                ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'rows' => 4],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['new' => 'New', 'contacted' => 'Contacted', 'converted' => 'Converted', 'closed' => 'Closed'], 'rules' => 'required|in:new,contacted,converted,closed'],
            ],
        ],

        'reviews' => [
            'model' => 'Review', 'label' => 'Customer Reviews', 'singular' => 'Review', 'icon' => '⭐',
            'order' => ['id', 'desc'], 'can_create' => false,
            'columns' => [['created_at', 'Submitted'], ['reviewer_name', 'Customer'], ['rating', 'Rating'], ['yatra.title', 'Yatra'], ['fleet.name', 'Vehicle'], ['is_approved', 'Approved', 'bool'], ['is_featured', 'Featured', 'bool']],
            'fields' => [
                ['name' => 'reviewer_name', 'label' => 'Customer name', 'type' => 'text', 'rules' => 'required|max:120'],
                ['name' => 'email', 'label' => 'Private email', 'type' => 'email', 'rules' => 'nullable|email|max:150'],
                ['name' => 'title', 'label' => 'Review title', 'type' => 'text', 'rules' => 'nullable|max:160'],
                ['name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number', 'rules' => 'required|integer|between:1,5'],
                ['name' => 'body', 'label' => 'Review', 'type' => 'textarea', 'rows' => 6, 'rules' => 'required|max:3000'],
                ['name' => 'yatra_id', 'label' => 'Yatra', 'type' => 'select', 'relation' => ['Yatra', 'title'], 'rules' => 'nullable|exists:yatras,id'],
                ['name' => 'fleet_id', 'label' => 'Vehicle', 'type' => 'select', 'relation' => ['Fleet', 'name'], 'rules' => 'nullable|exists:fleets,id'],
                ['name' => 'is_approved', 'label' => 'Approved (visible publicly)', 'type' => 'checkbox'],
                ['name' => 'is_featured', 'label' => 'Feature this review', 'type' => 'checkbox'],
            ],
        ],

        'subscribers' => [
            'model' => 'Subscriber', 'label' => 'Newsletter Subscribers', 'singular' => 'Subscriber', 'icon' => '✉️',
            'order' => ['id', 'desc'], 'can_create' => false, 'can_edit' => false,
            'columns' => [['email', 'Email'], ['created_at', 'Subscribed']],
            'fields' => [],
        ],
    ],

    // Editable site-wide settings (key => label)
    'settings' => [
        'site_name' => 'Site name',
        'tagline' => 'Tagline',
        'phone' => 'Phone (shown on call button)',
        'whatsapp' => 'WhatsApp number (digits with country code, e.g. 919876543210)',
        'email' => 'Email',
        'address' => 'Address',
        'about_title' => 'Home: about heading',
        'map_embed' => 'Google Maps embed URL (src of the iframe) for Contact page',
        'about_text' => 'Home: about text (next to enquiry form)',
        'facebook' => 'Facebook URL',
        'instagram' => 'Instagram URL',
        'youtube' => 'YouTube URL',
        'twitter' => 'X (Twitter) URL',
        'linkedin' => 'LinkedIn URL',
    ],
];
