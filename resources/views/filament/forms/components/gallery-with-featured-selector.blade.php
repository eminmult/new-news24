@php
    $currentFeatured = $getRecord()?->featured_media_id;
    $record = $getRecord();
    $mediaItems = $record ? $record->getMedia('post-gallery') : collect();
@endphp

<div x-data="{
    featuredId: @js($currentFeatured),
    setFeatured(id) {
        this.featuredId = id;
        $wire.set('data.featured_media_id', id);
    }
}" class="space-y-4">

    @if($mediaItems->count() > 0)
        <div class="mb-6">
            <div class="text-sm font-medium text-gray-700 mb-3">{{ __('posts.gallery_components.uploaded_photos') }}</div>
            <div class="grid grid-cols-5 gap-4">
                @foreach($mediaItems as $media)
                    <div
                        @click="setFeatured({{ $media->id }})"
                        :class="featuredId == {{ $media->id }} ? 'ring-2 ring-primary-600 border-primary-600' : 'border-gray-300 hover:border-primary-400'"
                        class="relative cursor-pointer rounded-lg overflow-hidden border-2 transition-all group"
                    >
                        <img
                            src="{{ $media->getUrl('thumb') }}"
                            alt="{{ $media->file_name }}"
                            class="w-full h-32 object-cover"
                        >

                        <!-- Бейдж "ОСНОВНОЕ" -->
                        <div
                            x-show="featuredId == {{ $media->id }}"
                            class="absolute top-2 left-2 z-10"
                        >
                            <span class="bg-primary-600 text-white text-xs font-semibold px-2 py-1 rounded shadow-lg">
                                {{ __('posts.gallery_components.featured_badge') }}
                            </span>
                        </div>

                        <!-- Галочка -->
                        <div
                            x-show="featuredId == {{ $media->id }}"
                            class="absolute top-2 right-2 z-10"
                        >
                            <div class="bg-primary-600 text-white rounded-full p-1.5 shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Overlay при наведении -->
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="border-t border-gray-200 pt-4">
            <div class="text-sm font-medium text-gray-700 mb-3">{{ __('posts.gallery_components.upload_new') }}</div>
        </div>
    @endif

    <!-- Оригинальная галерея загрузки -->
    <div>
        {{ $this->form->getComponent('gallery_upload') }}
    </div>
</div>
