@php use App\Helpers\Constants; @endphp
<div class="">
    {{-- HERO --}}
    <section class="wrapper !bg-[#fef4f2]">
        <div class="container pt-10 pb-20 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] xl:pb-40 lg:pb-40 md:pb-40">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] !mb-5 items-center">
                <div class="md:w-10/12 md:!ms-[8.33333333%] lg:!ms-0 lg:w-5/12 xl:!ms-0 xl:w-5/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] !mt-[50px] max-w-full text-center lg:text-start xl:text-start order-2 lg:!order-none xl:!order-none"
                    data-cues="slideInDown" data-group="page-title" data-delay="600">
                    <h1
                        class="xl:!text-[2.4rem] !text-[calc(1.375rem_+_1.5vw)] font-bold !leading-[1.2] !mb-5 md:mx-[-1.25rem] lg:mx-0 xl:mx-0">
                        {{ trans('web/home.hero_section.title') }}</h1>
                    <p class="lead !text-[1.05rem] !leading-[1.6] font-medium !mb-7">
                        {{ trans('web/home.hero_section.description') }}</p>
                    <div class="flex justify-center lg:!justify-start xl:!justify-start" data-cues="slideInDown"
                        data-group="page-title-buttons" data-delay="900">
                        <span><a
                                class="btn btn-orange !text-white !bg-[#f78b77] border-[#f78b77] hover:text-white hover:bg-[#f78b77] hover:!border-[#f78b77]   active:text-white active:bg-[#f78b77] active:border-[#f78b77] disabled:text-white disabled:bg-[#f78b77] disabled:border-[#f78b77] rounded !me-2">{{ trans('web/home.hero_section.btn_contact') }}</a></span>
                        <span><a
                                class="btn btn-yellow !text-white !bg-[#fab758] border-[#fab758] hover:text-white hover:bg-[#fab758] hover:!border-[#fab758]   active:text-white active:bg-[#fab758] active:border-[#fab758] disabled:text-white disabled:bg-[#fab758] disabled:border-[#fab758] rounded">{{ trans('web/home.hero_section.btn_service') }}</a></span>
                    </div>
                </div>

                <div class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] !mt-[50px] max-w-full"
                    data-cue="slideInDown">
                    <figure class="m-0 p-0"><img class="w-auto" src="/assets/img/illustrations/i6.png"
                            srcset="/assets/img/illustrations/i6.png" alt="image"></figure>
                </div>

            </div>

        </div>

    </section>

    {{-- FAQ, BEST_PRODUCT, HELP CENTER --}}
    <section class="wrapper !bg-gradient-to-b from-[#fef4f2] to-[#fff8f6]">
        <div class="container py-[4.5rem] xl:!p-[6rem_15px_7rem] lg:!p-[6rem_15px_7rem] md:!p-[6rem_15px_7rem]">
            {{-- HELP CENTER --}}
            <div
                class="flex flex-wrap mx-[-15px] xl:mx-[-12.5px] lg:mx-[-12.5px] md:mx-[-12.5px] !mt-[-8rem] xl:!mt-[-12.5rem] lg:!mt-[-12.5rem] md:!mt-[-12.5rem]  !mb-[4.5rem] xl:!mb-[7rem] lg:!mb-[7rem] md:!mb-[7rem]">

                @foreach (trans('web/home.help_center.items') as $row)
                    <div
                        class="md:w-6/12 lg:w-6/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[12.5px] lg:!px-[12.5px] md:!px-[12.5px] !mt-[25px] max-w-full">
                        <div
                            class="card !shadow-[0_0.25rem_1.75rem_rgba(30,34,40,0.07)] card-border-bottom !border-[{{ $row['color'] }}] after:!border-t-[calc(0.4rem_-_6px)] after:!border-b-[6px]">
                            <div class="card-body flex-[1_1_auto] p-[40px]">
                                <img class="svg-inject icon-svg icon-svg-md !w-[2.6rem] !h-[2.6rem] !text-[{{ $row['color'] }}] text-green !mb-3"
                                    src="{{ $row['icon'] }}" alt="{{ $row['title'] }}">
                                <h4>{{ $row['title'] }}</h4>
                                <p class="!mb-2 min-h-[90px]">{{ $row['description'] }}</p>
                            </div>
                            <!--/.card-body -->
                        </div>
                        <!--/.card -->
                    </div>
                @endforeach

            </div>
            {{-- BEST PRODUCT --}}
            <div
                class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] !mb-[4.5rem] xl:!mb-[7rem] lg:!mb-[7rem] md:!mb-[7rem] items-center">
                <div
                    class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                    <figure class="m-0 p-0"><img class="w-auto" src="/assets/img/illustrations/i8.png"
                            srcset="/assets/img/illustrations/i8.png" alt="image"></figure>
                </div>

                <div
                    class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                    <h3 class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7">
                        {{ trans('web/home.best_product.title') }}
                    </h3>
                    <div class="flex flex-row !mb-6">
                        <p class="!mb-0">
                            {{ trans('web/home.best_product.description') }}
                        </p>
                    </div>
                    <a href="{{ trans('web/home.best_product.link') }}"
                        class="btn !text-white !bg-[#123456] border-[#123456] hover:text-white hover:bg-[#123456] hover:!border-[#123456] rounded">
                        مطالعه بیشتر
                    </a>
                </div>

            </div>
            {{-- FAQ --}}
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] items-center">
                <div
                    class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full xl:!order-2 lg:!order-2">
                    <figure class="m-0 p-0"><img class="w-auto" src="/assets/img/illustrations/i2.png"
                            srcset="/assets/img/illustrations/i2.png" alt="image"></figure>
                </div>

                <div
                    class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                    <h3
                        class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7 xl:!mt-10 lg:!mt-10">
                        {{ trans('web/home.faq.title') }}</h3>
                    <div class="accordion accordion-wrapper" id="accordionExample">
                        @foreach (range(1, 4) as $index => $value)
                            <div class="card plain accordion-item">
                                <div class="card-header p-[0_0_0.8rem_0] !border-0" id="heading{{ $index }}">
                                    <button
                                        class="hover:!text-[#f78b77] before:!text-[#f78b77] {{ $loop->first ? 'accordion-button' : 'collapsed' }}"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $index }}">
                                        سوال؟
                                    </button>
                                </div>
                                <div id="collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="card-body flex-[1_1_auto]">
                                        <p>جواب</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </section>

    {{-- SERVICES TAB --}}
    <section class="wrapper !bg-[#fef4f2] pt-10">
        <div class="container py-[4.5rem] xl:!p-[0_15px_7rem] lg:!p-[0rem_15px_7rem] md:!p-[0rem_15px_7rem]">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] items-center">
                <div class="w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full text-center">
                    <h3 class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7">
                        {{ trans('web/home.services_tabs.title') }}
                    </h3>
                    <p class="lead !text-[1.05rem] !leading-[1.6] font-medium !mb-7 mx-auto max-w-3xl">
                        {{ trans('web/home.services_tabs.description') }}
                    </p>
                </div>

            </div>


            <div class="tabs-wrapper">
                <x-tabs wire:model="tab_service_selected" active-class="bg-[#fff]  !text-black"
                    label-class="font-semibold !text-[#000]"
                    label-div-class="rounded-xl overflow-hidden !rounded-b-none w-fit ms-1">
                    @foreach (trans('web/home.services_tabs.tabs') as $index => $tab)
                        <x-tab name="tab_{{ $index }}" :label="$tab['title']">
                            <div
                                class="flex flex-wrap !mt-[-50px] items-center bg-[#fff] px-10 pb-5 rounded-2xl ltr:rounded-tl-none rtl:rounded-tr-none">
                                @if ($tab['layout'] === 'text-image')
                                    <div
                                        class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                                        <h3
                                            class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7">
                                            {{ $tab['heading'] }}</h3>
                                        <p class="!mb-6 min-h-[163px]">{{ $tab['content'] }}</p>
                                        <a href="{{ $tab['link'] }}"
                                            class="btn !text-white !bg-[#123456] border-[#123456] hover:text-white hover:bg-[#123456] hover:!border-[#123456] rounded">{{ $tab['button_text'] }}</a>
                                    </div>

                                    <div
                                        class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                                        <figure class="m-0 p-0"><img class="w-auto max-h-[411px]"
                                                src="{{ $tab['image'] }}" srcset="{{ $tab['image'] }}"
                                                alt="{{ $tab['alt'] }}"></figure>
                                    </div>
                                @else
                                    <div
                                        class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                                        <figure class="m-0 p-0"><img class="w-auto max-h-[411px]"
                                                src="{{ $tab['image'] }}" srcset="{{ $tab['image'] }}"
                                                alt="{{ $tab['alt'] }}"></figure>
                                    </div>

                                    <div
                                        class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                                        <h3
                                            class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7">
                                            {{ $tab['heading'] }}</h3>
                                        <p class="!mb-6 min-h-[163px]">{{ $tab['content'] }}</p>
                                        <a href="{{ $tab['link'] }}"
                                            class="btn !text-white !bg-[#123456] border-[#123456] hover:text-white hover:bg-[#123456] hover:!border-[#123456] rounded">{{ $tab['button_text'] }}</a>
                                    </div>
                                @endif
                            </div>
                        </x-tab>
                    @endforeach
                </x-tabs>

            </div>
        </div>

    </section>

    {{-- STATISTICS --}}
    <section class="wrapper !bg-[#fff]">
        <div class="container py-[4.5rem] xl:pt-28 lg:pt-28 md:pt-28 xl:pb-40 lg:pb-40 md:pb-40">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] xl:!mt-0 lg:!mt-0">
                <div
                    class="xl:w-4/12 lg:w-4/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full text-center lg:text-start xl:text-start !mt-[50px] xl:!mt-0 lg:!mt-0">
                    <h3
                        class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-3 xl:!pr-20">
                        {{ trans('web/home.statistics.title') }}</h3>
                    <p class="lead !text-[1.05rem] !leading-[1.6] font-medium !mb-0 xxl:!pr-10">
                        {{ trans('web/home.statistics.description') }}</p>
                </div>

                <div
                    class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] !px-[15px] xl:!px-[35px] lg:!px-[20px] max-w-full xl:!mt-2 lg:!mt-2 !mt-[50px]">
                    <div class="flex flex-wrap mx-[-15px] items-center counter-wrapper !mt-[-30px] !text-center">
                        @foreach (trans('web/home.statistics.items') as $item)
                            <div
                                class="xl:w-4/12 lg:w-4/12 md:w-4/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 409.6 409.6"
                                    data-inject-url="https://sandbox-tailwind-template.netlify.app/assets/img/icons/lineal/{{ $item['icon'] }}.svg"
                                    class="svg-inject icon-svg !text-[#f78b77] text-orange !mb-3 !w-[2.6rem] !h-[2.6rem] m-[0_auto]">
                                    <path class="lineal-stroke"
                                        d="M204.8 409.6C91.9 409.6 0 317.7 0 204.8c0-49.9 18.2-98.1 51.2-135.5 4.4-5 12-5.5 17-1.1s5.5 12 1.1 17c-29.1 33-45.2 75.5-45.2 119.5 0 99.6 81.1 180.7 180.7 180.7s180.7-81.1 180.7-180.7S304.4 24.1 204.8 24.1c-27.3-.1-54.2 6.1-78.7 18-6 2.9-13.2.4-16.1-5.6-2.9-6-.4-13.2 5.6-16.1C143.4 6.9 173.9-.1 204.8 0c112.9 0 204.8 91.9 204.8 204.8s-91.9 204.8-204.8 204.8z">
                                    </path>
                                    <path class="lineal-fill"
                                        d="M349.4 204.8c0 79.8-64.7 144.6-144.6 144.6S60.2 284.6 60.2 204.8 125 60.2 204.8 60.2 349.4 125 349.4 204.8z">
                                    </path>
                                    <path class="lineal-stroke"
                                        d="M204.8 361.4c-86.4 0-156.6-70.2-156.6-156.6S118.4 48.2 204.8 48.2s156.6 70.2 156.6 156.6-70.2 156.6-156.6 156.6zm0-289.1c-73.1 0-132.5 59.4-132.5 132.5s59.4 132.5 132.5 132.5 132.5-59.5 132.5-132.5S277.9 72.3 204.8 72.3z">
                                    </path>
                                    <path class="lineal-stroke"
                                        d="M200.9 246.7c-8.8 0-17.2-3.5-23.5-9.7L145 204.5c-4.7-4.7-4.7-12.3 0-17s12.3-4.7 17 0l32.5 32.5c3.6 3.5 9.3 3.5 12.8 0l49.8-49.9c4.7-4.7 12.3-4.7 17 0s4.7 12.3 0 17L224.4 237c-6.2 6.2-14.7 9.7-23.5 9.7z">
                                    </path>
                                </svg>
                                <h3
                                    class="counter xl:!text-[2rem] !text-[calc(1.325rem_+_0.9vw)] !tracking-[normal] !leading-none !mb-2">
                                    {{ $item['count'] }}</h3>
                                <p class="!text-[0.8rem] font-medium !mb-0">{{ $item['label'] }}</p>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- TESTIMONIALS, PROJECT --}}
    <section class="wrapper !bg-[#ffffff]">
        <div class="container py-[4.5rem] xl:!p-[6rem_15px_7rem] lg:!p-[6rem_15px_7rem] md:!p-[6rem_15px_7rem]">
            {{-- TESTIMONIALS --}}
            <div class="itemgrid !mb-[4.5rem] xl:!mb-[8rem] lg:!mb-[8rem] md:!mb-[8rem] !mt-3">
                <div
                    class="flex flex-wrap mx-[-15px] isotope !mt-[-9rem] xl:!mt-[-15rem] lg:!mt-[-15rem] md:!mt-[-15rem]">
                    @foreach (range(1,4) as $value)
                        <div
                            class="item md:w-6/12 lg:w-6/12 xl:w-3/12 flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full">
                            <div
                                class="card !shadow-[0_0.25rem_1.75rem_rgba(30,34,40,0.07)] card-border-bottom  !border-[#fddcd6] after:!border-t-[calc(0.4rem_-_6px)] after:!border-b-[6px]">
                                <div class="card-body flex-[1_1_auto] p-[40px]">
                                    <span
                                        class="ratings  inline-block relative w-20 h-[0.8rem] text-[0.9rem] leading-none before:text-[rgba(38,43,50,0.1)] after:inline-block after:not-italic after:font-normal after:absolute after:!text-[#fcc032] after:content-['\2605\2605\2605\2605\2605'] after:overflow-hidden after:left-0 after:top-0 before:inline-block before:not-italic before:font-normal before:absolute before:!text-[#fcc032] before:content-['\2605\2605\2605\2605\2605'] before:overflow-hidden before:left-0 before:top-0 five !mb-3"></span>
                                    <blockquote
                                        class="!text-[0.85rem] !leading-[1.7] font-medium !pl-4 icon !mb-0 relative p-0 border-0 before:content-['\201d'] before:absolute before:top-[-1.5rem] before:left-[-0.9rem] before:text-[rgba(52,63,82,0.05)] before:text-[10rem] before:leading-none before:z-[1]">
                                        <p>"خیلی عالی بود دمشون گرم خیلی زحمت کشیدن و فلان و فلان وفلان"</p>
                                        <div class="flex items-center text-start">
                                            <div class="info !pl-0">
                                                <h5 class="!mb-1 text-[.9rem] !leading-[1.5]">
                                                    سجاد خدابخشی</h5>
                                                <p class="!mb-0 text-[.8rem]">مدیر تیم فلان</p>
                                            </div>
                                        </div>
                                    </blockquote>
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

            {{-- PROJECT --}}
            <div class="projects-tiles">
                <div class="project itemgrid grid-view">
                    <div
                        class="flex flex-wrap mx-[-15px] md:mx-[-20px] lg:mx-[-20px] xl:mx-[-35px] !mt-[-50px] xl:!mt-[-70px] lg:!mt-[-70px] md:!mt-[-70px] isotope">
                        <div
                            class="item xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full md:!mt-7 lg:!mt-20 xl:!mt-20 max-md:!mt-[50px]">
                            <div class="project-details flex justify-center !self-end flex-col !pl-0 !pb-0">
                                <div class="post-header">
                                    <h2
                                        class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-4 xxl:!pr-20">
                                        {{ trans('web/home.portfolio.title') }}</h2>
                                    <p class="lead text-[1.05rem] !leading-[1.6] font-medium !mb-0">
                                        {{ trans('web/home.portfolio.description') }}</p>
                                </div>

                            </div>

                        </div>


                        @foreach (range(1,3) as $value)
                            <div
                                class="item xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] md:!px-[20px] !px-[15px] max-w-full !mt-[70px]">
                                <figure class="lift rounded !mb-6">
                                    <a href="#">
                                        <img
                                            src="/assets/img/photos/rp1.jpg" srcset="/assets/img/photos/rp1.jpg"
                                            alt="نام تصویر">
                                    </a>
                                </figure>
                                <div
                                    class="uppercase !tracking-[0.02rem] text-[0.7rem] font-bold text-line relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4">
                                    طراحی سایت
                                </div>
                                <h2 class="post-title h3">سایت فروشگاهی مگریکو</h2>
                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="text-center mt-10">
                    <a href="#"
                        class="btn !text-white !bg-[#123456] border-[#123456] hover:text-white hover:bg-[#123456] hover:!border-[#123456] rounded">
                        {{ trans('web/home.portfolio.button_text') }}
                    </a>
                </div>
            </div>

        </div>

    </section>

    {{-- BLOG --}}
    <section class="wrapper mb-4">
        <div class="bg-white">
            <div class="w-full flex justify-between items-center text-center max-w-5xl mx-auto">
                <h3 class="xl:!text-[1.8rem] !leading-[1.3] !text-[calc(1.305rem_+_0.66vw)] font-bold !mb-7">
                    {{ trans('web/home.blog.title') }}
                </h3>
                <a href="#"
                    class="btn !text-white !bg-[#123456] border-[#123456] hover:text-white hover:bg-[#123456] hover:!border-[#123456] rounded">
                    {{ trans('web/home.blog.show_more') }}
                </a>
            </div>

            <div class="flex max-w-5xl h-full mx-auto justify-center items-center">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 w-full">
                    @foreach ($blogs as $blog)
                        <div class="w-full bg-white border rounded-2xl overflow-hidden hover:shadow-lg transition duration-300">
                            <img src="{{$blog->getFirstMediaUrl('image',Constants::RESOLUTION_854_480)}}" class="h-48 w-full object-cover" alt="{{$blog->title}}"/>
                            <div class="p-4">
                                <time datetime="2023-03-13" class="text-xs uppercase text-gray-400 font-semibold">
                                    {{jdate($blog->created_at)->ago()}}
                                </time>
                                <h2 class="my-1 text-2xl font-bold leading-relaxed">{{$blog->title}}</h2>
                                <p class="text-sm my-2">{{Str::limit($blog->description)}}</p>
                                <a href="{{$blog->path()}}"
                                    class="mt-4 py-2.5 px-6 rounded-full border font-medium !text-black bg-[#fff8f6] hover:text-black hover:bg-white  flex justify-center items-center space-x-2 transition duration-300">
                                    <span>
                                        {{ trans('web/home.blog.btn_text') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT_FORM --}}
    <section class="wrapper !bg-gradient-to-b from-[#fef4f2] to-[#fff8f6]">
        <div class="container py-[4.5rem] xl:!py-28 lg:!py-28 md:!py-28">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] items-center">
                <div
                    class="xl:w-7/12 lg:w-7/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                    <figure class="m-0 p-0">
                        <img class="w-auto" src="/assets/img/illustrations/i5.png"
                            srcset="/assets/img/illustrations/i5.png" alt="تصویر تماس با ما">
                    </figure>
                </div>

                <div
                    class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] !mt-[50px] max-w-full">
                    <div class="contact-form">
                        <div class="flex flex-wrap mx-[-10px]">
                            @foreach (trans('web/home.contact_form.input') as $row)
                                <div
                                    class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full">
                                    <div class="form-floating relative !mb-4">
                                        <input id="form_{{ $row['name'] }}" type="{{ $row['type'] }}"
                                            name="{{ $row['name'] }}"
                                            class=" form-control  relative block w-full text-[.75rem] font-medium !text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out     focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset]   focus-visible:!border-[rgba(63,120,224,0.5)]   placeholder:!text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] !leading-[1.25]"
                                            placeholder="" required>
                                        <label for="form_{{ $row['name'] }}"
                                            class="!text-[#959ca9] !mb-2 !inline-block text-[.75rem] !absolute !z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">
                                            {{ $row['label'] }} *</label>
                                        <div class="valid-feedback"> Looks good! </div>
                                        <div class="invalid-feedback"> Please enter your first name. </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="w-full flex-[0_0_auto] !px-[15px] max-w-full">
                                <div class="form-floating relative !mb-4">
                                    <textarea id="form_message" name="message"
                                        class=" form-control  relative block w-full text-[.75rem] font-medium !text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out     focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset]   focus-visible:!border-[rgba(63,120,224,0.5)]   placeholder:!text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] !leading-[1.25]"
                                        placeholder="" style="height: 150px" required></textarea>
                                    <label for="form_message"
                                        class="!text-[#959ca9] !mb-2 !inline-block text-[.75rem] !absolute !z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">{{ trans('web/home.contact_form.text_aria') }}
                                        *</label>
                                    <div class="valid-feedback"> Looks good! </div>
                                    <div class="invalid-feedback"> Please enter your messsage. </div>
                                </div>
                            </div>

                            <div class="w-full flex-[0_0_auto] !px-[15px] max-w-full">
                                <div class="form-check block min-h-[1.36rem] !pl-[1.55rem] !mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck"
                                        required>
                                    <label class="form-check-label" for="invalidCheck"><a
                                            href="{{ trans('web/home.contact_form.terms_link_text') }}"
                                            class="hover">{{ trans('web/home.contact_form.terms_link_text') }}</a>
                                        {{ trans('web/home.contact_form.terms') }} </label>
                                </div>
                            </div>

                            <div class="w-full flex-[0_0_auto] !px-[15px] max-w-full">
                                <input type="submit"
                                    class="btn btn-primary !text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:!border-[#3f78e0]   active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-send !mb-3 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]"
                                    value="{{ trans('web/home.contact_form.btn_text') }}">
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
</div>
