{!! view_render_event('bagisto.shop.layout.features.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'services_content',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
@endphp

@if ($customization)
    <div style="background-color: #f9fafb; padding-top: 4rem; padding-bottom: 6rem;">
        <div style="margin: 0 auto; max-width: 1120px; padding-left: 1rem; padding-right: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                @foreach ($customization->options['services'] as $service)
                    <div style="position: relative; transform: translateY(0); transition: all 0.3s ease-in-out;"
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0px 10px 20px rgba(0,0,0,0.1)';"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="display: flex; flex-direction: column; align-items: center; border-radius: 1rem; background-color: #ffffff; padding: 1.5rem; text-align: center; box-shadow: 0px 2px 4px rgba(0,0,0,0.1);">
                            <span style="display: flex; margin-bottom: 1rem; height: 4rem; width: 4rem; align-items: center; justify-content: center; border-radius: 50%; background: linear-gradient(to right, #6366f1, #8b5cf6); font-size: 1.5rem; color: #ffffff; transition: transform 0.3s;"
                                  class="{{ $service['service_icon'] }}"
                                  role="presentation" aria-hidden="true"
                                  onmouseover="this.style.transform='scale(1.1)';"
                                  onmouseout="this.style.transform='scale(1)';">
                            </span>

                            <div style="margin-top: 1rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827;">
                                    {{ $service['title'] }}
                                </h3>
                                <p style="font-size: 0.875rem; color: #6b7280;">
                                    {{ $service['description'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}
