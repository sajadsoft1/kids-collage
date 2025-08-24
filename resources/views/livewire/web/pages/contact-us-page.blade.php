<div>
    <section class="wrapper !bg-[#ffffff]">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] !mt-[-50px] xl:mx-[-35px] lg:mx-[-20px] items-center">
                <div
                    class="xl:w-7/12 lg:w-7/12 flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full xl:!order-2 lg:!order-2 !relative !mt-[50px]">
                    <figure class="m-0 p-0 relative">
                        <div
                            class="absolute top-0 right-0 bottom-0 left-0 bg-gradient-to-br from-[#fef4f2] via-transparent to-transparent rounded-3xl">
                        </div>
                        <img class="w-auto relative z-10" src="/assets/img/illustrations/i14.png" alt="تصویر تماس با ما">
                    </figure>
                </div>
                <div
                    class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <span
                        class="bg-[#fef4f2] text-[#f78b77] px-4 py-2 rounded-full text-sm font-medium inline-block mb-4">{{ trans('web/contact_us.hero_section.tag') }}</span>
                    <h2 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-3">
                        {{ trans('web/contact_us.hero_section.title') }}
                    </h2>
                    <p class="lead !text-[1.05rem] !leading-[1.6] font-medium">
                        {{ trans('web/contact_us.hero_section.description') }}
                    </p>
                    <p class="text-gray-600 mb-6">
                        {{ trans('web/contact_us.hero_section.more_description') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="#contact-form"
                            class="btn !text-white !bg-[#f78b77] border-[#f78b77] hover:text-white hover:bg-[#f67a63] hover:!border-[#f67a63] active:text-white active:bg-[#f78b77] active:border-[#f78b77] !rounded-[50rem] !mt-2 transition-all duration-300 hover:-translate-y-1">
                            {{ trans('web/contact_us.hero_section.contact_btn') }}
                        </a>
                        <a href="#contact-info"
                            class="btn !text-[#f78b77] !bg-[#fef4f2] border-[#fef4f2] hover:!text-white hover:!bg-[#f78b77] hover:!border-[#f78b77] active:text-white active:bg-[#f78b77] active:border-[#f78b77] !rounded-[50rem] !mt-2 transition-all duration-300 hover:-translate-y-1">
                            {{ trans('web/contact_us.hero_section.info_btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT_FORM --}}
    <section class="wrapper !bg-gradient-to-b from-[#fef4f2] to-[#fff8f6]">
        <div class="container py-[4.5rem] xl:!py-28 lg:!py-28 md:!py-28">
            <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px] !mt-[-50px] items-center">
                <div
                    class="xl:w-6/12 lg:w-4/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <div class="flex bg-white rounded-4xl flex-wrap">
                        <!-- آدرس -->
                        <a href="https://maps.google.com/?q={{ trans('web/contact_us.contact_info.office_address.value') }}"
                            target="_blank"
                            class="p-6 flex justify-center items-center transition-all hover:shadow-lg hover:bg-gray-50 hover:-translate-y-1 duration-300 flex-col w-1/2 border-e-1 border-b-1 border-gray-50 gap-y-2">
                            <div
                                class="bg-[#3f78e0] rounded-full h-12 w-12 flex items-center justify-center hover:bg-[#2d5eb3] transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="#fff"
                                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7m0 9.5a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5" />
                                </svg>
                            </div>
                            <p class="font-bold !m-0 text-lg">
                                {{ trans('web/contact_us.contact_info.office_address.title') }}</p>
                            <p class="text-gray-600 text-center">
                                {{ trans('web/contact_us.contact_info.office_address.value') }}</p>
                        </a>

                        <!-- شماره تماس -->
                        <a href="tel:{{ trans('web/contact_us.contact_info.phone.value') }}"
                            class="p-6 flex justify-center items-center transition-all hover:shadow-lg hover:bg-gray-50 hover:-translate-y-1 duration-300 flex-col w-1/2 border-b-1 border-gray-50 gap-y-2">
                            <div
                                class="bg-[#3f78e0] rounded-full h-12 w-12 flex items-center justify-center hover:bg-[#2d5eb3] transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="#fff"
                                        d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z" />
                                </svg>
                            </div>
                            <p class="font-bold !m-0 text-lg">{{ trans('web/contact_us.contact_info.phone.title') }}
                            </p>
                            <p class="text-gray-600 text-center" dir="ltr">
                                {{ trans('web/contact_us.contact_info.phone.value') }}</p>
                        </a>

                        <!-- تلگرام -->
                        <a href="https://t.me/{{ trans('web/contact_us.contact_info.telegram.value') }}"
                            target="_blank"
                            class="p-6 flex justify-center items-center transition-all hover:shadow-lg hover:bg-gray-50 hover:-translate-y-1 duration-300 flex-col w-1/2 border-e-1 gap-y-2">
                            <div
                                class="bg-[#3f78e0] rounded-full h-12 w-12 flex items-center justify-center hover:bg-[#2d5eb3] transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="#fff"
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19c-.14.75-.42 1-.68 1.03c-.58.05-1.02-.38-1.58-.75c-.88-.58-1.38-.94-2.23-1.5c-.99-.65-.35-1.01.22-1.59c.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02c-.09.02-1.49.95-4.22 2.79c-.4.27-.76.41-1.08.4c-.36-.01-1.04-.2-1.55-.37c-.63-.2-1.12-.31-1.08-.66c.02-.18.27-.36.74-.55c2.92-1.27 4.86-2.11 5.83-2.51c2.78-1.16 3.35-1.36 3.73-1.36c.08 0 .27.02.39.12c.1.08.13.19.14.27c-.01.06.01.24 0 .24" />
                                </svg>
                            </div>
                            <p class="font-bold !m-0 text-lg">{{ trans('web/contact_us.contact_info.telegram.title') }}
                            </p>
                            <p class="text-[#3f78e0]">{{ trans('web/contact_us.contact_info.telegram.value') }}</p>
                        </a>

                        <!-- اینستاگرام -->
                        <a href="https://www.instagram.com/{{ trans('web/contact_us.contact_info.instagram.value') }}"
                            target="_blank"
                            class="p-6 flex justify-center items-center transition-all hover:shadow-lg hover:bg-gray-50 hover:-translate-y-1 duration-300 flex-col w-1/2 gap-y-2">
                            <div
                                class="bg-[#3f78e0] rounded-full h-12 w-12 flex items-center justify-center hover:bg-[#2d5eb3] transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="#fff"
                                        d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4zm9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8A1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5a5 5 0 0 1-5 5a5 5 0 0 1-5-5a5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3" />
                                </svg>
                            </div>
                            <p class="font-bold !m-0 text-lg">
                                {{ trans('web/contact_us.contact_info.instagram.title') }}</p>
                            <p class="text-[#3f78e0]">{{ trans('web/contact_us.contact_info.instagram.value') }}</p>
                        </a>
                    </div>
                </div>

                <div
                    class="xl:w-6/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <div class="contact-form">
                        <div class="flex flex-wrap mx-[-10px]">
                            @foreach (trans('web/contact_us.contact_form.input') as $row)
                                <div class="xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] max-w-full">
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
                                        class="!text-[#959ca9] !mb-2 !inline-block text-[.75rem] !absolute !z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">{{ trans('web/contact_us.contact_form.text_aria') }}
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
                                            href="{{ trans('web/contact_us.contact_form.terms_link_text') }}"
                                            class="hover">{{ trans('web/contact_us.contact_form.terms_link_text') }}</a>
                                        {{ trans('web/contact_us.contact_form.terms') }} </label>
                                </div>
                            </div>

                            <div class="w-full flex-[0_0_auto] !px-[15px] max-w-full">
                                <input type="submit"
                                    class="btn btn-primary !text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:!border-[#3f78e0]   active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-send !mb-3 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]"
                                    value="{{ trans('web/contact_us.contact_form.btn_text') }}">
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
</div>
