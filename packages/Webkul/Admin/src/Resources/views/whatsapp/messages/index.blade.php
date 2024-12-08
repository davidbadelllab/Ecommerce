<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.components.layouts.sidebar.whatsapp-messages')
    </x-slot:title>

    <!-- Contenedor principal con altura específica -->
    <div class="content full-height">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap mb-4">

        </div>

        <div class="h-[calc(100vh-150px)] bg-white dark:bg-gray-900 rounded shadow-md overflow-hidden">
            @livewire('whatsapp-chat')
        </div>
    </div>

    @livewireScripts

    <script src="https://cdn.socket.io/4.7.4/socket.io.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('livewire:load', () => {
            try {
                const socket = io('http://localhost:3000', {
                    withCredentials: true,
                    transports: ['websocket', 'polling']
                });

                socket.on('connect', () => {
                    console.log('Socket conectado');
                });

                socket.on('connect_error', (error) => {
                    console.error('Error de conexión:', error);
                });

                socket.on('authStatus', (status) => {
                    if (!status) {
                        window.location.href = '/admin/whatsapp/templates';
                    }
                });

                socket.on('message', (data) => {
                    console.log('Mensaje recibido:', data);
                    Livewire.dispatch('messageReceived', data);
                });

                socket.on('messageSent', (data) => {
                    console.log('Mensaje enviado:', data);
                    Livewire.dispatch('messageSent', data);
                });

            } catch (error) {
                console.error('Error al inicializar socket:', error);
            }
        });
    </script>
</x-admin::layouts>
