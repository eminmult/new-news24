@php
    $currentFeatured = $getState();
    $record = $getRecord();
    $mediaItems = $record ? $record->getMedia('post-gallery') : collect();
@endphp

<style>
    .featured-selector-item {
        position: relative;
        cursor: pointer;
    }
    .featured-selector-item .hover-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    .featured-selector-item:hover .hover-overlay {
        background: rgba(0, 0, 0, 0.4);
    }
    .featured-selector-item .hover-text {
        opacity: 0;
        transition: opacity 0.2s;
        background: white;
        color: #111827;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .featured-selector-item:hover .hover-text {
        opacity: 1;
    }
</style>

<div class="space-y-2">
    @if($mediaItems->count() > 0)
        <div class="grid grid-cols-5 gap-4">
            @foreach($mediaItems as $media)
                <label class="featured-selector-item block rounded-lg overflow-hidden border-2 transition-all {{ $currentFeatured == $media->id ? 'border-primary-600 ring-2 ring-primary-600' : 'border-gray-300 hover:border-primary-400' }}">
                    <img
                        src="{{ $media->getUrl('thumb') }}"
                        alt="{{ $media->file_name }}"
                        class="w-full h-32 object-cover"
                    >

                    <!-- Галочка для выбранного фото -->
                    @if($currentFeatured == $media->id)
                        <div style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;">
                            <div style="background: #2563eb; color: white; border-radius: 9999px; padding: 0.375rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Бейдж "ОСНОВНОЕ" -->
                        <div style="position: absolute; top: 0.5rem; left: 0.5rem; z-index: 10;">
                            <span style="background: #2563eb; color: white; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 0.375rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                {{ __('posts.gallery_components.featured_badge') }}
                            </span>
                        </div>
                    @endif

                    <!-- Overlay при наведении -->
                    <div class="hover-overlay">
                        <span class="hover-text">
                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('posts.gallery_components.make_featured') }}
                        </span>
                    </div>

                    <!-- Radio input -->
                    <input
                        type="radio"
                        wire:model.live="data.featured_media_id"
                        name="featured_media_id"
                        value="{{ $media->id }}"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 20;"
                    >
                </label>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            {{ __('posts.gallery_components.no_photos_select') }}
        </div>
    @endif
</div>
