{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Concert+One&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">

<style>
    .shimmer {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }
</style>

<div
    class="flex min-h-[78px] w-full justify-between bg-orange-500/95 backdrop-blur-sm border-b border-orange-600 px-[60px] max-1180:px-8 font-nunito">
    <!-- Left Navigation Section -->
    <div class="text-gray-800 text-xs flex items-center gap-x-10 max-[1180px]:gap-x-5">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        <a href="{{ route('shop.home.index') }}" aria-label="@lang('shop::app.components.layouts.header.bagisto')" class="flex items-center">
            <img src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}" width="51"
                height="29" alt="{{ config('app.name') }}">
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <v-desktop-category>
            <div class="flex items-center gap-5">
                <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>

                <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>

                <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Navigation Section -->
    <div class="flex items-center gap-x-9 max-[1100px]:gap-x-6 max-lg:gap-x-8">
        <!-- Search Bar Container -->
        <div class="relative w-full">
            <form action="{{ route('shop.search.index') }}" class="flex max-w-[445px] items-center" role="search">
                <label for="organic-search" class="sr-only">
                    @lang('shop::app.components.layouts.header.search')
                </label>

                <div
                    class="icon-search pointer-events-none absolute top-2.5 flex items-center text-xl text-gray-500 ltr:left-3 rtl:right-3">
                </div>

                <input type="text" name="query" value="{{ request('query') }}"
                    class="block w-full rounded-lg border border-transparent bg-white px-11 py-3 text-xs font-medium text-gray-900 transition-all hover:border-orange-400 focus:border-orange-400"
                    minlength="{{ core()->getConfigData('catalog.products.search.min_query_length') }}"
                    maxlength="{{ core()->getConfigData('catalog.products.search.max_query_length') }}"
                    placeholder="@lang('shop::app.components.layouts.header.search-text')" aria-label="@lang('shop::app.components.layouts.header.search-text')" aria-required="true" pattern="[^\\]+"
                    required>

                <button type="submit" class="hidden" aria-label="@lang('shop::app.components.layouts.header.submit')">
                </button>

                @if (core()->getConfigData('catalog.products.settings.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>
        </div>

        <!-- Right Navigation Links -->
        <div class="text-gray-800 mt-1.5 flex gap-x-8 max-[1100px]:gap-x-6 max-lg:gap-x-8 items-center">
            <!-- Compare -->
            @if (core()->getConfigData('catalog.products.settings.compare_option'))
                <a href="{{ route('shop.compare.index') }}" aria-label="@lang('shop::app.components.layouts.header.compare')"
                    class="hover:text-gray-600 transition-colors">
                    <span class="icon-compare inline-block cursor-pointer text-2xl" role="presentation"></span>
                </a>
            @endif

            <!-- Mini cart -->
            @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            <!-- User profile -->
            <x-shop::dropdown
                position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <span class="icon-users inline-block cursor-pointer text-2xl hover:text-gray-600 transition-colors"
                        role="button" aria-label="@lang('shop::app.components.layouts.header.profile')" tabindex="0"></span>
                </x-slot:toggle>

                <x-slot:content>
                    @guest('customer')
                        <div class="grid gap-2.5 p-5">
                            <p class="text-xl text-gray-800">
                                @lang('shop::app.components.layouts.header.welcome-guest')
                            </p>
                            <p class="text-sm text-gray-600">
                                @lang('shop::app.components.layouts.header.dropdown-text')
                            </p>
                        </div>
                        <div class="mt-6 flex gap-4 px-5 pb-5">
                            <a href="{{ route('shop.customer.session.create') }}"
                            style="background-color: #ea580c; color: #ffffff; padding: 0.5rem 1.25rem; border-radius: 0.5rem; text-decoration: none; transition: background-color 0.3s;"
                            onmouseover="this.style.backgroundColor='#d97706';"
                            onmouseout="this.style.backgroundColor='#ea580c';">
                            @lang('shop::app.components.layouts.header.sign-in')
                         </a>

                            <a href="{{ route('shop.customers.register.index') }}"
                                class="border-2 border-gray-300 px-5 py-2 rounded-lg hover:border-gray-400 transition-colors">
                                @lang('shop::app.components.layouts.header.sign-up')
                            </a>
                        </div>
                    @endguest

                    @auth('customer')
                        <div class="grid gap-2.5 p-5">
                            <p class="text-xl text-gray-800">
                                @lang('shop::app.components.layouts.header.welcome')
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>
                            <div class="mt-2.5 grid gap-1">
                                <a href="{{ route('shop.customers.account.profile.index') }}"
                                    class="px-5 py-2 text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                    @lang('shop::app.components.layouts.header.profile')
                                </a>
                                <a href="{{ route('shop.customers.account.orders.index') }}"
                                    class="px-5 py-2 text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                    @lang('shop::app.components.layouts.header.orders')
                                </a>
                                <x-shop::form method="DELETE" action="{{ route('shop.customer.session.destroy') }}"
                                    id="customerLogout" />
                                <a href="{{ route('shop.customer.session.destroy') }}"
                                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                    class="px-5 py-2 text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                    @lang('shop::app.components.layouts.header.logout')
                                </a>
                            </div>
                        </div>
                    @endauth
                </x-slot:content>
            </x-shop::dropdown>

            <!-- Admin Button -->
            <a href="https://www.yupiii.cl/admin"
            class="ml-4 inline-flex items-center px-4 py-2 rounded-lg shadow-md transition-all transform hover:-translate-y-1"
            style="background-color: #f97316; color: #ffffff; transition: all 0.3s ease-in-out;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4a4 4 0 1 1 0 8 4 4 0 1 1 0-8zm0 10c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6z" />
            </svg>
            Admin
         </a>


        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-category-template">
        <div class="flex items-center gap-5" v-if="isLoading">
            <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>
            <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>
            <span class="shimmer h-6 w-20 rounded bg-orange-400/50" role="presentation"></span>
        </div>

        <div class="flex items-center" v-else>
            <div
                class="group relative flex h-[77px] items-center border-b-4 border-transparent hover:border-b-4 hover:border-gray-800"
                v-for="category in categories"
            >
                <span>
                    <a
                        :href="category.url"
                        class="inline-block px-5 uppercase text-gray-800 hover:text-gray-600"
                    >
                        @{{ category.name }}
                    </a>
                </span>

                <div
                    class="pointer-events-none absolute top-[78px] z-[1] max-h-[580px] w-max max-w-[1260px] translate-y-1 overflow-auto overflow-x-auto border-t border-[#F3F3F3] bg-white p-9 opacity-0 shadow-[0_6px_6px_1px_rgba(0,0,0,.3)] transition duration-300 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 group-hover:duration-200 group-hover:ease-in ltr:-left-9 rtl:-right-9"
                    v-if="category.children.length"
                >
                    <div class="flex justify-between gap-x-[70px]">
                        <div
                            class="grid w-full min-w-max max-w-[150px] flex-auto grid-cols-[1fr] content-start gap-5"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren">
                                <p class="font-medium text-gray-800">
                                    <a :href="secondLevelCategory.url">
                                        @{{ secondLevelCategory.name }}
                                    </a>
                                </p>

                                <ul
                                    class="grid grid-cols-[1fr] gap-3"
                                    v-if="secondLevelCategory.children.length"
                                >
                                    <li
                                        class="text-sm font-medium text-gray-600"
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                    >
                                        <a :href="thirdLevelCategory.url">
                                            @{{ thirdLevelCategory.name }}
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;
                            this.categories = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }
                        return result;
                    }, []);
                }
            },
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
