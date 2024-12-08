<x-admin::layouts.anonymous>
    <!-- Title of the page -->
    <x-slot:title>
        {{ __('admin::app.components.layouts.sidebar.whatsapp') }}
    </x-slot>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 dark:text-white font-bold">
            {{ __('admin::app.components.layouts.sidebar.whatsapp') }}
        </p>
    </div>

    <div class="flex gap-[16px] justify-between items-center mt-[28px] max-sm:flex-wrap">
        <!-- WhatsApp Templates Card -->
        <a
            href="{{ route('admin.whatsapp.templates.index') }}"
            class="flex flex-1 gap-[10px] p-[16px] bg-white dark:bg-gray-900 rounded-[4px] min-w-[200px] transition-all hover:shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
        >
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                    {{ __('admin::app.components.layouts.sidebar.whatsapp-templates') }}
                </p>
            </div>
        </a>

        <!-- WhatsApp Messages Card -->
        <a
            href="{{ route('admin.whatsapp.messages.index') }}"
            class="flex flex-1 gap-[10px] p-[16px] bg-white dark:bg-gray-900 rounded-[4px] min-w-[200px] transition-all hover:shadow-[0px_8px_10px_0px_rgba(0,0,0,0.05)]"
        >
            <div class="flex flex-col gap-[8px]">
                <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                    {{ __('admin::app.components.layouts.sidebar.whatsapp-messages') }}
                </p>
            </div>
        </a>
    </div>
</x-admin::layouts.anonymous>
