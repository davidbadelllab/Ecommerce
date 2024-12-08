<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.whatsapp.columns.attention')
    </x-slot:title>

    <div class="h-[700px]">
        @livewire('agent-messages')
    </div>
</x-admin::layouts>
