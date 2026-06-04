@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<feed xmlns="http://www.w3.org/2005/Atom"
      xml:lang="id">
    <title>{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</title>
    <subtitle>{{ $settings->meta_description ?? 'Portal Resmi Kementerian Agama Kabupaten Nganjuk' }}</subtitle>
    <link href="{{ $settings->site_url ?? url('/') }}" rel="alternate" type="text/html" />
    <link href="{{ url('/atom') }}" rel="self" type="application/atom+xml" />
    <id>{{ url('/') }}</id>
    <updated>{{ now()->toIso8601String() }}</updated>
    <rights>Copyright {{ date('Y') }} {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</rights>
    <generator uri="https://laravel.com">Laravel</generator>
    <author>
        <name>{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}</name>
        <uri>{{ $settings->site_url ?? url('/') }}</uri>
    </author>
    
    @foreach($posts as $post)
    <entry>
        <title type="html"><![CDATA[{{ $post->title }}]]></title>
        <link href="{{ route('posts.show', $post->slug) }}" rel="alternate" type="text/html" />
        <id>{{ route('posts.show', $post->slug) }}</id>
        <updated>{{ $post->published_at?->toIso8601String() }}</updated>
        <published>{{ $post->published_at?->toIso8601String() }}</published>
        <summary type="html"><![CDATA[{{ Str::limit(strip_tags($post->subtitle ?? $post->content), 300) }}]]></summary>
        <content type="html"><![CDATA[{{ $post->content }}]]></content>
        @if($post->author)
        <author>
            <name>{{ $post->author->username }}</name>
        </author>
        @endif
        @if($post->category)
        <category term="{{ $post->category->name }}" />
        @endif
        @if($post->tags && $post->tags->count() > 0)
            @foreach($post->tags as $tag)
        <category term="{{ $tag->name }}" />
            @endforeach
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
        <media:content xmlns:media="http://search.yahoo.com/mrss/" url="{{ $thumbnailUrl }}" medium="image" />
        @endif
        @endif
    </entry>
    @endforeach
</feed>
