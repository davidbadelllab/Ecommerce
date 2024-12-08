{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'footer_links',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
@endphp

<footer style="background-color: #1f2937; color: #ffffff;">
    <div style="max-width: 1120px; margin: 0 auto; padding: 3rem 1rem;">
        <div style="display: grid; grid-template-columns: 1fr; gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
            <!-- Footer Links Section -->
            <div>
                <div style="display: none; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
                    @if ($customization?->options)
                        @foreach ($customization->options as $footerLinkSection)
                            @php
                                usort($footerLinkSection, function ($a, $b) {
                                    return $a['sort_order'] - $b['sort_order'];
                                });
                            @endphp

                            <div>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach ($footerLinkSection as $link)
                                        <li>
                                            <a
                                                href="{{ $link['url'] }}"
                                                style="color: #d1d5db; text-decoration: none; transition: color 0.2s; display: inline-block; margin-bottom: 1rem;"
                                                onmouseover="this.style.color='#ffffff'"
                                                onmouseout="this.style.color='#d1d5db'">
                                                {{ $link['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Mobile Accordion -->
                <div>
                    <div style="background-color: #374151; border-radius: 0.5rem; overflow: hidden;">
                        <div style="padding: 1rem; font-weight: 500; color: #ffffff;">
                            @lang('shop::app.components.layouts.footer.footer-content')
                        </div>

                        <div style="padding: 1rem; background-color: #374151;">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                                @if ($customization?->options)
                                    @foreach ($customization->options as $footerLinkSection)
                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                            @foreach ($footerLinkSection as $link)
                                                <li>
                                                    <a
                                                        href="{{ $link['url'] }}"
                                                        style="color: #d1d5db; text-decoration: none; transition: color 0.2s; display: inline-block; margin-bottom: 1rem;"
                                                        onmouseover="this.style.color='#ffffff'"
                                                        onmouseout="this.style.color='#d1d5db'">
                                                        {{ $link['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                <div>
                    {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

                    <div style="margin-top: 1rem;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem;">
                            @lang('shop::app.components.layouts.footer.newsletter-text')
                        </h2>

                        <p style="color: #d1d5db; margin-bottom: 1rem;">
                            @lang('shop::app.components.layouts.footer.subscribe-stay-touch')
                        </p>

                        <form action="{{ route('shop.subscription.store') }}" method="POST" style="margin-bottom: 1rem;">
                            @csrf
                            <div style="position: relative; margin-bottom: 1rem;">
                                <input
                                    type="email"
                                    name="email"
                                    style="width: 100%; padding: 0.75rem 1rem; background-color: #374151; border: 1px solid #4b5563; border-radius: 0.5rem; color: #ffffff;"
                                    placeholder="email@example.com"
                                    required
                                />
                                <button
                                    type="submit"
                                    style="width: 100%; padding: 0.5rem 1rem; background-color: #2563eb; color: #ffffff; border: none; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#1d4ed8'"
                                    onmouseout="this.style.backgroundColor='#2563eb'">
                                    @lang('shop::app.components.layouts.footer.subscribe')
                                </button>
                            </div>
                        </form>
                    </div>

                    {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
                </div>
            @endif
        </div>

        <!-- Footer Bottom -->
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #374151;">
            <p style="text-align: center; color: #9ca3af; font-size: 0.875rem;">
               Yupiii.cl
            </p>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
