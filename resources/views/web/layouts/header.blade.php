<header class="relative wrapper !bg-[#fef4f2] !pb-4">
    <nav class="navbar navbar-expand-lg extended navbar-light caret-none">
        <div class="container xl:!flex-col lg:!flex-col">
            <div class="topbar flex flex-row w-full justify-between items-center">
                <div class="navbar-brand"><a href="{{ localized_route('home-page') }}"><img
                            src="/assets/img/logo-dark.png" srcset="/assets/img/logo-dark@2x.png 2x" alt="image"></a>
                </div>
                <div class="navbar-other !ms-auto">
                    <ul class="navbar-nav !flex-row items-center">
                        <li class="nav-item"><a class="nav-link hover:!text-[#f78b77]" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvas-info"><i
                                    class="uil uil-info-circle before:content-['\eb99'] !text-[1.1rem]"></i></a></li>
                        <li class="nav-item dropdown language-select uppercase">
                            <a class="nav-link dropdown-item dropdown-toggle hover:!text-[#f78b77] after:!text-[#f78b77]"
                                href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">{{ app()->getLocale() }}</a>
                            <ul class="dropdown-menu">
                                @foreach (config('app.supported_locales') as $locale)
                                <li class="nav-item"><a class="dropdown-item hover:!text-[#f78b77]"
                                        href="{{ route('change-locale', ['lang' => $locale]) }}">{{ $locale
                                        }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item xl:!hidden lg:!hidden">
                            <button class="hamburger offcanvas-nav-btn"><span></span></button>
                        </li>
                    </ul>
                    <!-- /.navbar-nav -->
                </div>
                <!-- /.navbar-other -->
            </div>
            <!-- /.flex -->
            <div class="navbar-collapse-wrapper bg-[rgba(255,255,255)] opacity-100 flex flex-row items-center">
                <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                    <div class="offcanvas-header xl:!hidden lg:!hidden flex items-center justify-between flex-row p-6">
                        <h3 class="!text-white xl:!text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !mb-0">Sandbox</h3>
                        <button type="button"
                            class="btn-close btn-close-white !mr-[-0.75rem] m-0 p-0 leading-none !text-[#343f52] transition-all duration-[0.2s] ease-in-out border-0 motion-reduce:transition-none before:text-[1.05rem] before:text-white before:content-['\ed3b'] before:w-[1.8rem] before:h-[1.8rem] before:leading-[1.8rem] before:shadow-none before:transition-[background] before:duration-[0.2s] before:ease-in-out before:!flex before:justify-center before:items-center before:m-0 before:p-0 before:rounded-[100%] hover:no-underline bg-inherit before:bg-[rgba(255,255,255,.08)] before:font-Unicons hover:before:bg-[rgba(0,0,0,.11)]  "
                            data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body flex flex-col !h-full">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown dropdown-mega">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('home-page')}}">Home</a>

                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('portfolio')}}">Portfolio</a>

                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('faq')}}">Faq</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('faq')}}">Faq</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('portfolio.detail')}}">Portfolio detail</a>

                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="#" data-bs-toggle="dropdown">Services</a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item"><a class="dropdown-item hover:!text-[#f78b77]" wire:navigate
                                            href="{{localized_route('services.website')}}">Website</a></li>
                                    <li class="nav-item"><a class="dropdown-item hover:!text-[#f78b77]" wire:navigate
                                            href="{{localized_route('services.seo')}}">Seo</a></li>
                                    <li class="nav-item"><a class="dropdown-item hover:!text-[#f78b77]" wire:navigate
                                            href="{{localized_route('services.application')}}">Application</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('blog')}}">Blog</a>
                            </li>
                            <li class="nav-item dropdown dropdown-mega">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('blog.detail')}}">BlogDetail</a>

                            </li>
                            <li class="nav-item dropdown dropdown-mega">
                                <a class="nav-link dropdown-toggle !tracking-[-.01rem] hover:!text-[#f78b77] after:!text-[#f78b77]"
                                    wire:navigate href="{{localized_route('contact-us')}}">Contact us</a>

                            </li>
                        </ul>
                        <!-- /.navbar-nav -->
                        <div class="offcanvas-footer xl:!hidden lg:!hidden">
                            <div>
                                <a href="mailto:first.last@email.com" class="link-inverse">info@email.com</a>
                                <br> 00 (123) 456 78 90 <br>
                                <nav class="nav social social-white !mt-4">
                                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                        href="#"><i
                                            class="uil uil-twitter before:content-['\ed59'] !text-white text-[1rem]"></i></a>
                                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                        href="#"><i
                                            class="uil uil-facebook-f before:content-['\eae2'] !text-white text-[1rem]"></i></a>
                                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                        href="#"><i
                                            class="uil uil-dribbble before:content-['\eaa2'] !text-white text-[1rem]"></i></a>
                                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                        href="#"><i
                                            class="uil uil-instagram before:content-['\eb9c'] !text-white text-[1rem]"></i></a>
                                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                        href="#"><i
                                            class="uil uil-youtube before:content-['\edb5'] !text-white text-[1rem]"></i></a>
                                </nav>
                                <!-- /.social -->
                            </div>
                        </div>
                        <!-- /.offcanvas-footer -->
                    </div>
                    <!-- /.offcanvas-body -->
                </div>
                <!-- /.navbar-collapse -->
                <div class="navbar-other !ml-auto w-full hidden xl:block lg:block">
                    <nav class="nav social social-muted justify-end text-right">
                        <a class="m-[0_0_0_.7rem] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 hover:translate-y-[-0.15rem]"
                            href="#"><i
                                class="uil uil-twitter before:content-['\ed59'] text-[1rem] !text-[#5daed5]"></i></a>
                        <a class="m-[0_0_0_.7rem] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 hover:translate-y-[-0.15rem]"
                            href="#"><i
                                class="uil uil-facebook-f before:content-['\eae2'] text-[1rem] !text-[#4470cf]"></i></a>
                        <a class="m-[0_0_0_.7rem] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 hover:translate-y-[-0.15rem]"
                            href="#"><i
                                class="uil uil-dribbble before:content-['\eaa2'] text-[1rem] !text-[#e94d88]"></i></a>
                        <a class="m-[0_0_0_.7rem] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 hover:translate-y-[-0.15rem]"
                            href="#"><i
                                class="uil uil-instagram before:content-['\eb9c'] text-[1rem] !text-[#d53581]"></i></a>
                    </nav>
                    <!-- /.social -->
                </div>
                <!-- /.navbar-other -->
            </div>
            <!-- /.navbar-collapse-wrapper -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- /.navbar -->
    <div class="offcanvas offcanvas-end text-inverse !text-[#cacaca] opacity-100" id="offcanvas-info"
        data-bs-scroll="true">
        <div class="offcanvas-header flex flex-row items-center justify-between p-[1.5rem]">
            <h3 class="!text-white xl:!text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !leading-[1.4] !mb-0">Sandbox</h3>
            <button type="button" class="btn-close btn-close-white !mr-[-0.5rem] m-0 p-0" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body !pb-[1.5rem]">
            <div class="widget !mb-8">
                <p>Sandbox is a multipurpose HTML5 template with various layouts which will be a great solution for your
                    business.</p>
            </div>
            <!-- /.widget -->
            <div class="widget !mb-8">
                <h4 class="widget-title !text-white !mb-[0.75rem] !text-[.95rem] !leading-[1.45]">Contact Info</h4>
                <address class=" not-italic !leading-[inherit] !mb-[1rem]"> Moonshine St. 14/05 <br> Light City, London
                </address>
                <a class="!text-[#cacaca] hover:!text-[#fab758]"
                    href="mailto:first.last@email.com">info@email.com</a><br> 00 (123) 456 78 90
            </div>
            <!-- /.widget -->
            <div class="widget !mb-8">
                <h4 class="widget-title !text-white !mb-[0.75rem] !text-[.95rem] !leading-[1.45]">Learn More</h4>
                <ul class="list-unstyled !pl-0">
                    <li><a class="!text-[#cacaca] hover:!text-[#fab758]" href="#">Our Story</a></li>
                    <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#fab758]" href="#">Terms of Use</a>
                    </li>
                    <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#fab758]" href="#">Privacy
                            Policy</a></li>
                    <li class="!mt-[.35rem]"><a class="!text-[#cacaca] hover:!text-[#fab758]" href="#">Contact Us</a>
                    </li>
                </ul>
            </div>
            <!-- /.widget -->
            <div class="widget">
                <h4 class="widget-title !text-white !mb-[0.75rem] !text-[.95rem] !leading-[1.45]">Follow Us</h4>
                <nav class="nav social social-white">
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                        href="#"><i class="uil uil-twitter before:content-['\ed59'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                        href="#"><i class="uil uil-facebook-f before:content-['\eae2'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                        href="#"><i class="uil uil-dribbble before:content-['\eaa2'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                        href="#"><i class="uil uil-instagram before:content-['\eb9c'] !text-white text-[1rem]"></i></a>
                    <a class="!text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                        href="#"><i class="uil uil-youtube before:content-['\edb5'] !text-white text-[1rem]"></i></a>
                </nav>
                <!-- /.social -->
            </div>
            <!-- /.widget -->
        </div>
        <!-- /.offcanvas-body -->
    </div>
    <!-- /.offcanvas -->
</header>