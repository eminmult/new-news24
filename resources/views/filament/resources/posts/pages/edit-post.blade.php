<x-filament-panels::page>
    @push('scripts')
    <script>
        // Heartbeat для поддержания блокировки поста
        const postId = {{ $postId }};
        let heartbeatInterval;

        function sendHeartbeat() {
            fetch(`/admin/post-lock/heartbeat/${postId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).catch(error => console.error('Heartbeat error:', error));
        }

        function releaseLock() {
            fetch(`/admin/post-lock/release/${postId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).catch(error => console.error('Release lock error:', error));
        }

        // Отправляем heartbeat каждые 60 секунд
        heartbeatInterval = setInterval(sendHeartbeat, 60000);

        // Очистка при уходе со страницы
        window.addEventListener('beforeunload', function() {
            clearInterval(heartbeatInterval);
            releaseLock();
        });

        // Очистка при клике на кнопку "Отменить" или навигации
        document.addEventListener('DOMContentLoaded', function() {
            // Ловим все клики по ссылкам навигации
            document.addEventListener('click', function(e) {
                const target = e.target.closest('a');
                if (target && !target.hasAttribute('data-save-button')) {
                    // Если это не кнопка сохранения, снимаем блокировку
                    releaseLock();
                    clearInterval(heartbeatInterval);
                }
            });
        });
    </script>
    @endpush
</x-filament-panels::page>
