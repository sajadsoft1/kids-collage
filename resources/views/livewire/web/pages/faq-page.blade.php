<div>
    {{-- Hero Section --}}
    <section class="wrapper !bg-[#ffffff]">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] !mt-[-50px] xl:mx-[-35px] lg:mx-[-20px] items-center">
                <div class="xl:w-7/12 lg:w-7/12 flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full xl:!order-2 lg:!order-2 !relative !mt-[50px]">
                    <figure class="m-0 p-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#fef4f2] via-transparent to-transparent rounded-3xl"></div>
                        <img class="w-auto relative z-10" src="/assets/img/illustrations/i22.png" alt="{{ trans('web/faq.hero_section.tag') }}">
                    </figure>
                </div>
                <div class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <span class="bg-[#fef4f2] text-[#f78b77] px-4 py-2 rounded-full text-sm font-medium inline-block mb-4">{{ trans('web/faq.hero_section.tag') }}</span>
                    <h2 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-3">
                        {{ trans('web/faq.hero_section.title') }}
                    </h2>
                    <p class="text-gray-600 mb-6">
                        {{ trans('web/faq.hero_section.description') }}
                    </p>
                    <a href="/contact-us" class="btn !text-white !bg-[#f78b77] border-[#f78b77] hover:text-white hover:bg-[#f67a63] hover:!border-[#f67a63] active:text-white active:bg-[#f78b77] active:border-[#f78b77] !rounded-[50rem] !mt-2 transition-all duration-300 hover:-translate-y-1">
                        {{ trans('web/faq.hero_section.contact_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="wrapper !bg-gradient-to-b from-[#fef4f2] to-[#fff8f6]">
        <div class="container py-[4.5rem] xl:!py-28 lg:!py-28 md:!py-28">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px]">
                <div class="w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <div class="accordion accordion-wrapper" id="accordionSimple">
                        {{-- سوال 1 --}}
                        <div class="card accordion-item bg-white hover:bg-gray-50 rounded-2xl shadow-sm mb-4 transition-all duration-300 hover:-translate-y-1">
                            <div class="card-header border-b border-gray-100" id="headingOne">
                                <button class="accordion-button collapsed w-full text-end py-5 px-6 text-base font-semibold flex items-center justify-between" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <span class="flex-1">هزینه طراحی سایت چقدر است؟</span>
                                </button>
                            </div>
                            <div id="collapseOne" class="accordion-collapse collapse transition-all duration-300" data-bs-parent="#accordionSimple">
                                <div class="card-body px-6 py-5">
                                    <p class="text-gray-600 leading-relaxed">هزینه طراحی سایت به عوامل مختلفی بستگی دارد. برای دریافت قیمت دقیق می‌توانید با ما تماس بگیرید.</p>
                                </div>
                            </div>
                        </div>

                        {{-- سوال 2 --}}
                        <div class="card accordion-item bg-white hover:bg-gray-50 rounded-2xl shadow-sm mb-4 transition-all duration-300 hover:-translate-y-1">
                            <div class="card-header border-b border-gray-100" id="headingTwo">
                                <button class="accordion-button collapsed w-full text-end py-5 px-6 text-base font-semibold flex items-center justify-between" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <span class="flex-1">مدت زمان طراحی سایت چقدر است؟</span>
                                </button>
                            </div>
                            <div id="collapseTwo" class="accordion-collapse collapse transition-all duration-300" data-bs-parent="#accordionSimple">
                                <div class="card-body px-6 py-5">
                                    <p class="text-gray-600 leading-relaxed">زمان طراحی سایت بسته به نوع پروژه متفاوت است. معمولاً بین 2 تا 8 هفته زمان می‌برد.</p>
                                </div>
                            </div>
                        </div>

                        {{-- سوال 3 --}}
                        <div class="card accordion-item bg-white hover:bg-gray-50 rounded-2xl shadow-sm mb-4 transition-all duration-300 hover:-translate-y-1">
                            <div class="card-header border-b border-gray-100" id="headingThree">
                                <button class="accordion-button collapsed w-full text-end py-5 px-6 text-base font-semibold flex items-center justify-between" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    <span class="flex-1">آیا پشتیبانی پس از طراحی سایت ارائه می‌دهید؟</span>
                                </button>
                            </div>
                            <div id="collapseThree" class="accordion-collapse collapse transition-all duration-300" data-bs-parent="#accordionSimple">
                                <div class="card-body px-6 py-5">
                                    <p class="text-gray-600 leading-relaxed">بله، ما خدمات پشتیبانی و نگهداری سایت را به صورت ماهانه ارائه می‌دهیم.</p>
                                </div>
                            </div>
                        </div>

                        {{-- سوال 4 --}}
                        <div class="card accordion-item bg-white hover:bg-gray-50 rounded-2xl shadow-sm transition-all duration-300 hover:-translate-y-1">
                            <div class="card-header border-b border-gray-100" id="headingFour">
                                <button class="accordion-button collapsed w-full text-end py-5 px-6 text-base font-semibold flex items-center justify-between" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                    <span class="flex-1">آیا امکان سفارش طراحی اپلیکیشن موبایل وجود دارد؟</span>
                                </button>
                            </div>
                            <div id="collapseFour" class="accordion-collapse collapse transition-all duration-300" data-bs-parent="#accordionSimple">
                                <div class="card-body px-6 py-5">
                                    <p class="text-gray-600 leading-relaxed">بله، تیم ما در زمینه طراحی اپلیکیشن‌های موبایل نیز تخصص دارد و می‌توانیم نیازهای شما را در این زمینه برآورده کنیم.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
