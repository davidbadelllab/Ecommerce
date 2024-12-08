<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
</v-products-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-products-carousel-template">
        <div
            class="container mx-auto px-4 sm:px-6 lg:px-8 mt-20 max-lg:px-8 max-md:mt-8 max-sm:mt-7 max-sm:!px-4 bg-gray-900"
            v-if="! isLoading && products.length"
        >
            <div class="flex justify-between items-center">
                <h2 class="font-dmserif text-lg  bg-gradient-to-r text-red-500 bg-clip-text ">
                    @{{ title }}
                </h2>

                <div class="flex items-center gap-8">
                    <a
                        :href="navigationLink"
                        class="hidden max-lg:flex group"
                        v-if="navigationLink"
                    >
                        <p class="flex items-center text-xl max-md:text-base max-sm:text-sm text-gray-700 group-hover:text-gray-900 transition-colors duration-300">
                            @lang('shop::app.components.products.carousel.view-all')
                            <span class="icon-arrow-right text-2xl max-md:text-lg max-sm:text-sm ml-2 group-hover:translate-x-1 transition-transform duration-300"></span>
                        </p>
                    </a>

                    <button
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="swipeLeft"
                    aria-label="@lang('shop::app.components.products.carousel.previous')"
                >
                    <span class="icon-arrow-left-stylish rtl:icon-arrow-right-stylish text-2xl text-gray-600 group-hover:text-gray-900"></span>
                </button>


                <button
                style="display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 9999px; background-color: #ffffff; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.3s ease-in-out; outline: none; z-index: 10;"
                @click="swipeRight"
                aria-label="@lang('shop::app.components.products.carousel.next')"
                onmouseover="this.style.boxShadow='0px 6px 10px rgba(0, 0, 0, 0.15)'"
                onmouseout="this.style.boxShadow='0px 4px 6px rgba(0, 0, 0, 0.1)'"
            >
                <span style="font-size: 1.5rem; color: #4b5563; transition: color 0.3s;" onmouseover="this.style.color='#1f2937'" onmouseout="this.style.color='#4b5563'" class="icon-arrow-right-stylish rtl:icon-arrow-left-stylish"></span>
            </button>

                </div>
            </div>

            <div
                ref="swiperContainer"
                class="flex gap-8 mt-10 pb-2.5 overflow-auto scroll-smooth scrollbar-hide snap-x snap-mandatory max-md:gap-7 max-md:mt-5 max-sm:gap-4 max-md:pb-0"
            >
                <x-shop::products.card
                    class="min-w-[291px] max-md:min-w-56 max-sm:min-w-[192px] snap-start shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl bg-white overflow-hidden transform hover:-translate-y-1"
                    v-for="product in products"
                />
            </div>

            <a
                :href="navigationLink"
                class="mt-8 hidden lg:flex mx-auto w-max px-8 py-3 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 text-center text-base transform hover:-translate-y-1"
                :aria-label="title"
                v-if="navigationLink"
            >
                @lang('shop::app.components.products.carousel.view-all')
            </a>
        </div>

        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false" />
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: ['src', 'title', 'navigationLink'],

            data() {
                return {
                    isLoading: true,
                    products: [],
                    offset: 323,
                };
            },

            mounted() {
                this.getProducts();
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;
                            this.products = response.data.data;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;
                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;
                    if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
                        container.scrollLeft = 0;
                    } else {
                        container.scrollLeft += this.offset;
                    }
                },
            },
        });
    </script>
@endPushOnce
