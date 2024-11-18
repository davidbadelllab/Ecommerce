@if ($sliderData)
    <div class="slider-container">
        <div class="slider-content">
            @if (count($sliderData))
                <div class="slider-images">
                    @foreach ($sliderData as $index => $slider)
                        <a 
                            @if ($slider->slider_path)
                                href="{{ $slider->slider_path }}"
                            @endif
                        >
                            <img
                                class="banner-image"
                                src="{{ Storage::url($slider->path) }}"
                                alt="{{ $slider->title }}"
                            />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

  @push('css')
    <style>
        /* Contenedor principal del slider */
        .slider-container {
            width: 100%;
            max-width: 1920px; /* Abarca hasta 1920px o más */
            height: 100vh;
            overflow: hidden;
            position: relative;
            margin: 0 auto; /* Centra el contenedor si es necesario */
        }

        /* Contenido del slider */
        .slider-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        /* Imágenes del banner */
        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Ajustes para pantallas grandes */
        @media (min-width: 1200px) {
            .slider-container {
                height: 80vh; /* Puedes ajustar la altura según sea necesario */
            }
        }

        /* Ajustes para pantallas muy grandes (más de 1920px) */
        @media (min-width: 1920px) {
            .slider-container {
                max-width: none; /* Elimina la restricción del ancho máximo */
                height: 80vh;
            }
        }
    </style>
@endpush

@endif
