<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $entry)
    <url>
        <loc>{{ $entry['loc'] }}</loc>
        @if ($entry['lastmod'])<lastmod>{{ $entry['lastmod']->toAtomString() }}</lastmod>@endif
    </url>
@endforeach
</urlset>