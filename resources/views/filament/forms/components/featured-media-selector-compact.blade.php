@php
    $currentFeatured = $getState();
    $record = $getRecord();
    $mediaItems = $record ? $record->getMedia('post-gallery') : collect();
@endphp

<div class="border-t border-gray-200 pt-4 mt-2">
    <div class="text-sm font-medium text-gray-700 mb-3">{{ __('posts.gallery_components.select_featured') }}</div>

    <div class="grid grid-cols-10 gap-3">
        @foreach($mediaItems as $media)
            <label class="relative cursor-pointer rounded overflow-hidden border-2 transition-all block {{ $currentFeatured == $media->id ? 'border-primary-600 ring-2 ring-primary-600' : 'border-gray-300 hover:border-primary-400' }}">
                <img
                    src="{{ $media->getUrl('thumb') }}"
                    alt="{{ $media->file_name }}"
                    class="w-full h-20 object-cover"
                >

                @if($currentFeatured == $media->id)
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(37, 99, 235, 0.2); display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 2rem; height: 2rem; color: rgb(37, 99, 235);" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif

                <input
                    type="radio"
                    wire:model.live="data.featured_media_id"
                    name="featured_media_id"
                    value="{{ $media->id }}"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;"
                >
            </label>
        @endforeach
    </div>
</div>
