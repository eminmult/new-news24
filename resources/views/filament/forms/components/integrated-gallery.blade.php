@php
    $record = $getRecord();
    $mediaItems = $record ? $record->getMedia('post-gallery') : collect();
    $currentFeatured = $record?->featured_media_id;
@endphp

<div
    x-data="{
        featuredId: @js($currentFeatured),
        deleteMode: {},
        setFeatured(id) {
            this.featuredId = id;
            $wire.set('data.featured_media_id', id);
        },
        async deleteMedia(mediaId) {
            if (confirm('{{ __('posts.gallery_components.delete_photo') }}')) {
                await $wire.call('deleteMedia', mediaId);
                window.location.reload();
            }
        }
    }"
    class="space-y-4"
>
    @if($mediaItems->count() > 0)
        <div class="grid grid-cols-5 gap-4">
            @foreach($mediaItems as $media)
                <div class="relative group rounded-lg overflow-hidden border-2 transition-all"
                     :class="featuredId == {{ $media->id }} ? 'border-primary-600 ring-2 ring-primary-600' : 'border-gray-300'"
                >
                    <!-- Изображение -->
                    <img
                        src="{{ $media->getUrl('thumb') }}"
                        alt="{{ $media->file_name }}"
                        class="w-full h-32 object-cover"
                    >

                    <!-- Бейдж ОСНОВНОЕ -->
                    <div
                        x-show="featuredId == {{ $media->id }}"
                        class="absolute top-2 left-2 z-10"
                    >
                        <span class="bg-primary-600 text-white text-xs font-semibold px-2 py-1 rounded shadow-lg">
                            {{ __('posts.gallery_components.featured_badge') }}
                        </span>
                    </div>

                    <!-- Кнопки управления -->
                    <div class="absolute top-2 right-2 z-10 flex gap-1">
                        <!-- Кнопка "Сделать основным" -->
                        <button
                            type="button"
                            @click="setFeatured({{ $media->id }})"
                            class="bg-white hover:bg-yellow-50 border border-gray-300 rounded p-1.5 shadow-sm transition-colors"
                            title="{{ __('posts.gallery_components.make_featured') }}"
                        >
                            <svg class="w-4 h-4"
                                 :class="featuredId == {{ $media->id }} ? 'text-yellow-500 fill-yellow-500' : 'text-gray-400'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </button>

                        <!-- Кнопка удаления -->
                        <button
                            type="button"
                            @click="deleteMedia({{ $media->id }})"
                            class="bg-white hover:bg-red-50 border border-gray-300 rounded p-1.5 shadow-sm transition-colors"
                            title="{{ __('posts.gallery_components.delete') }}"
                        >
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Затемнение при наведении -->
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity pointer-events-none"></div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-gray-200 pt-4 mt-4">
            <div class="text-sm font-medium text-gray-700 mb-2">{{ __('posts.gallery_components.upload_new') }}</div>
        </div>
    @else
        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 mb-4">
            {{ __('posts.gallery_components.no_photos_yet') }}
        </div>
    @endif

    <!-- Форма загрузки -->
    <form wire:submit.prevent="uploadPhotos" enctype="multipart/form-data">
        <div class="space-y-2">
            <input
                type="file"
                wire:model="newPhotos"
                multiple
                accept="image/*"
                class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-primary-50 file:text-primary-700
                    hover:file:bg-primary-100
                    cursor-pointer"
            >
            @if($record)
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ __('posts.gallery_components.upload_button') }}
                </button>
            @else
                <p class="text-sm text-gray-500">{{ __('posts.gallery_components.create_first') }}</p>
            @endif
        </div>
    </form>
</div>
