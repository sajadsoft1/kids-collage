<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('100_100.png') }}">
    <link rel="stylesheet" type="text/css" href="/assets/fonts/unicons/unicons.css">
    <link rel="stylesheet" href="/assets/css/plugins.css">
    <link rel="stylesheet" href="/assets/fonts/iranSans/style.css" />
    @vite(['resources/css/web.css'])
    <link rel="stylesheet" href="/assets/css/colors/orange.css">

    <style>
        .accordion-wrapper .card-header button {
            color: #f78b77;
        }

        @media (max-width: 991.98px) {
            .navbar-expand-lg .navbar-collapse .dropdown-toggle:after {
                color: #ffffff !important;
            }
        }
    </style>
    {!! app('seotools')->generate() !!}
    @livewireStyles
</head>

<body>
    <div class="grow shrink-0">
        @include('web.layouts.header')
        <!-- /header -->
        {{-- <div class="modal fade modal-popup" id="modal-02" tabindex="-1"> --}}
        {{-- <div class="modal-dialog modal-dialog-centered modal-md"> --}}
        {{-- <div class="modal-content !text-center"> --}}
        {{-- <div class="relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] !pl-[2.5rem]"> --}}
        {{-- <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        {{-- <div class="flex flex-wrap mx-[-15px]"> --}}
        {{-- <div
                                class="xl:w-10/12 lg:w-10/12 md:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full xl:!ml-[8.33333333%] lg:!ml-[8.33333333%] md:!ml-[8.33333333%]">
                                --}}
        {{-- <figure class="!mb-6"><img src="/assets/img/illustrations/i7.png"
                                        srcset="/assets/img/illustrations/i7@2x.png 2x" alt="image"></figure> --}}
        {{-- </div> --}}
        {{--
                            <!-- /column --> --}}
        {{--
                        </div> --}}
        {{--
                        <!-- /.row --> --}}
        {{-- <h3>Join the mailing list and get %10 off</h3> --}}
        {{-- <p class="!mb-6">Nullam quis risus eget urna mollis ornare vel eu leo. Donec ullamcorper
                            nulla non metus auctor fringilla.</p> --}}
        {{-- <div class="newsletter-wrapper"> --}}
        {{-- <div class="flex flex-wrap mx-[-15px]"> --}}
        {{-- <div
                                    class="xl:w-10/12 lg:w-10/12 md:w-10/12 w-full flex-[0_0_auto] !px-[15px] max-w-full xl:!ml-[8.33333333%] lg:!ml-[8.33333333%] md:!ml-[8.33333333%]">
                                    --}}
        {{--
                                    <!-- Begin Mailchimp Signup Form --> --}}
        {{-- <div id="mc_embed_signup"> --}}
        {{-- <form
                                            action="https://elemisfreebies.us20.list-manage.com/subscribe/post?u=aa4947f70a475ce162057838d&amp;id=b49ef47a9a"
                                            method="post" id="mc-embedded-subscribe-form"
                                            name="mc-embedded-subscribe-form" class="validate" target="_blank"
                                            novalidate> --}}
        {{-- <div id="mc_embed_signup_scroll"> --}}
        {{-- <div class="!text-left input-group !relative form-floating"> --}}
        {{-- <input type="email" value="" name="EMAIL"
                                                        class="required email form-control  relative block w-full text-[.75rem] font-medium !text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out     focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset]   focus-visible:!border-[rgba(63,120,224,0.5)]   placeholder:!text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] !leading-[1.25]"
                                                        placeholder="" id="mce-EMAIL"> --}}
        {{-- <label for="mce-EMAIL"
                                                        class="!text-[#959ca9] !mb-2 !inline-block text-[.75rem] !absolute !z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope">Email
                                                        Address</label> --}}
        {{-- <input type="submit" value="Join" name="subscribe"
                                                        id="mc-embedded-subscribe"
                                                        class="btn btn-orange !text-white !bg-[#f78b77] border-[#f78b77] hover:text-white hover:bg-[#f78b77] hover:!border-[#f78b77] hover:!translate-none active:text-white active:bg-[#f78b77] active:border-[#f78b77] disabled:text-white disabled:bg-[#f78b77] disabled:border-[#f78b77]"> --}}
        {{-- </div> --}}
        {{-- <div id="mce-responses" class="clear"> --}}
        {{-- <div class="response" id="mce-error-response"
                                                        style="display:none"></div> --}}
        {{-- <div class="response" id="mce-success-response"
                                                        style="display:none"></div> --}}
        {{-- </div>
                                                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups--> --}}
        {{-- <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                                    <input type="text" name="b_ddc180777a163e0f9f66ee014_4b1bcfa0bc"
                                                        tabindex="-1" value="">
                                                </div> --}}
        {{-- <div class="clear"></div> --}}
        {{-- </div> --}}
        {{-- </form> --}}
        {{-- </div> --}}
        {{--
                                    <!--End mc_embed_signup--> --}}
        {{--
                                </div> --}}
        {{--
                                <!-- /.newsletter-wrapper --> --}}
        {{--
                            </div> --}}
        {{--
                            <!-- /column --> --}}
        {{--
                        </div> --}}
        {{--
                        <!-- /.row --> --}}
        {{--
                    </div> --}}
        {{--
                    <!--/.modal-body --> --}}
        {{--
                </div> --}}
        {{--
                <!--/.modal-content --> --}}
        {{--
            </div> --}}
        {{--
            <!--/.modal-dialog --> --}}
        {{--
        </div> --}}
        {{ $slot }}
    </div>
    <!-- /.content-wrapper -->
    @include('web.layouts.footer')
    <!-- progress wrapper -->
    <div
        class="progress-wrap fixed w-[2.3rem] h-[2.3rem] cursor-pointer block shadow-[inset_0_0_0_0.1rem_rgba(128,130,134,0.25)] z-[1010] opacity-0 invisible translate-y-3 transition-all duration-[0.2s] ease-[linear,margin-right] delay-[0s] rounded-[100%] right-6 bottom-6 motion-reduce:transition-none after:absolute after:content-['\e951'] after:text-center after:leading-[2.3rem] after:text-[1.2rem] after:!text-[#f78b77] after:h-[2.3rem] after:w-[2.3rem] after:cursor-pointer after:block after:z-[1] after:transition-all after:duration-[0.2s] after:ease-linear after:left-0 after:top-0 motion-reduce:after:transition-none after:font-Unicons">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path
                class="fill-none stroke-[#f78b77] stroke-[4] box-border transition-all duration-[0.2s] ease-linear motion-reduce:transition-none"
                d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/theme.js"></script>
    @vite(['resources/js/web/web.js'])
    @livewireScripts
</body>

</html>
