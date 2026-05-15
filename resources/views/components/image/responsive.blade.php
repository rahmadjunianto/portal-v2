{{-- resources/views/components/image/responsive.blade.php --}}
{{-- Optimized responsive image with WebP + fallback --}}
{{-- Uses object-contain to preserve full image without cropping --}}

@props([
    'filename' => null,
    'folder' => 'posts',
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'sizes' => null,
    'width' => null,
    'height' => null,
])

@php
    $imageProcessor = app(\App\Services\ImageProcessor::class);
    $srcset = $imageProcessor->getSrcset($filename, $folder);
    $sizesConfig = $imageProcessor->getSizes();

    // Default sizes - responsive breakpoints
    $defaultSizes = '(min-width: 1200px) 1200px, (min-width: 768px) 768px, 100vw';

    // Fallback image
    $fallbackUrl = $imageProcessor->getLargestVariant($filename, $folder);

    // Get dimensions from largest available variant
    $defaultWidth = $width ?? 1200;
    $defaultHeight = $height ?? 675;
@endphp

{{-- Picture element with WebP > fallback --}}
<picture>
    {{-- WebP sources --}}
    @if(isset($srcset['webp']))
        <source
            type="image/webp"
            srcset="{{ collect($srcset['webp'])->map(fn($item) => $item['url'] . ' ' . $item['maxWidth'] . 'w')->implode(', ') }}"
            sizes="{{ $sizes ?? $defaultSizes }}"
        >
    @endif

    {{-- Fallback img with object-contain (preserve full image) --}}
    <img
        src="{{ $fallbackUrl }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        width="{{ $defaultWidth }}"
        height="{{ $defaultHeight }}"
        loading="{{ $loading }}"
        decoding="async"
        style="object-fit: contain;"
    >
</picture>
