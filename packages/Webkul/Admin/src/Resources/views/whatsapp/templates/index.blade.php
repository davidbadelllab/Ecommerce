<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.components.layouts.sidebar.whatsapp-templates')
    </x-slot>

    <div>
        @livewire('whats-app-q-r')
    </div>

    <script src="https://cdn.socket.io/4.7.4/socket.io.min.js" crossorigin="anonymous"></script>
    <script>
        if (typeof window.socketInitialized === 'undefined') {
            window.socketInitialized = true;

            const socket = io('http://localhost:3000', {
                withCredentials: true,
                transports: ['websocket']
            });

            socket.on('connect', () => {
                console.log('Socket conectado');
                socket.emit('checkAuth');
            });

            socket.on('qrCode', (qr) => {
                console.log('QR recibido:', qr.substring(0, 50));
                if (window.Livewire) {
                    Livewire.dispatch('updateQrCode', { qr });
                }
            });

            socket.on('redirectToMessages', (data) => {
                if (data.authenticated) {
                    window.location.replace(data.redirectUrl);
                }
            });

            socket.on('connect_error', (error) => {
                console.error('Error de conexión:', error);
            });
        }
    </script>
</x-admin::layouts>
