<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" 
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</title>
        <link>{{ $settings->site_url ?? url('/') }}</link>
        <description>{{ $settings->meta_description ?? 'Portal Resmi Kementerian Agama Kabupaten Nganjuk' }}</description>
        <language>id-ID</language>
        <copyright>Copyright {{ date('Y') }} {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</copyright>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />
        <image>
            <url>{{ asset('logo-kemenag.png') }}</url>
            <title>{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</title>
            <link>{{ $settings->site_url ?? url('/') }}</link>
        </image>
        
        @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <link>{{ route('posts.show', $post->slug) }}</link>
            <guid isPermaLink="true">{{ route('posts.show', $post->slug) }}</guid>
            <description><![CDATA[{{ Str::limit(strip_tags($post->subtitle ?? $post->content), 300) }}]]></description>
            <content:encoded><![CDATA[{{ $post->content }}]]></content:encoded>
            <pubDate>{{ $post->published_at?->toRssString() }}</pubDate>
            @if($post->author)
            <dc:creator><![CDATA[{{ $post->author->username }}]]></dc:creator>
            @endif
            @if($post->category)
            <category><![CDATA[{{ $post->category->name }}]]></category>
            @endif
            @if($post->thumbnail)
            @php
                $thumbnailUrl = null;
                if (!empty($post->thumbnail)) {
                    $imageProcessor = app(\App\Services\ImageProcessor::class);
                    $thumbnailUrl = $imageProcessor->getLargestVariant($post->thumbnail, 'posts');
                    if (!$thumbnailUrl && file_exists(public_path('storage/' . $post->thumbnail))) {
                        $thumbnailUrl = asset('storage/' . $post->thumbnail);
                    }
                }
            @endphp
            @if($thumbnailUrl)
            <media:content url="{{ $thumbnailUrl }}" medium="image" />
            <enclosure url="{{ $thumbnailUrl }}" type="image/jpeg" length="0" />
            @endif
            @endif
        </item>
        @endforeach
    </channel>
</rss>
