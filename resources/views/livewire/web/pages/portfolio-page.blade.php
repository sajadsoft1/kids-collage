<div>
    <section class="wrapper !bg-[#ffffff]">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28">
            <div class="flex flex-wrap mx-[-15px] !mt-[-50px] xl:mx-[-35px] lg:mx-[-20px] items-center">
                <div class="xl:w-7/12 lg:w-7/12 flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full xl:!order-2 lg:!order-2 !relative !mt-[50px]">
                    <figure class="m-0 p-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#fef4f2] via-transparent to-transparent rounded-3xl"></div>
                        <img class="w-auto relative z-10" src="/assets/img/illustrations/i19.png" alt="تصویر نمونه کارها">
                    </figure>
                </div>
                <div class="xl:w-5/12 lg:w-5/12 w-full flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full !mt-[50px]">
                    <span class="bg-[#fef4f2] text-[#f78b77] px-4 py-2 rounded-full text-sm font-medium inline-block mb-4">{{ trans('web/portfolio.hero_section.tag') }}</span>
                    <h2 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-3">
                        {{ trans('web/portfolio.hero_section.title') }}
                    </h2>
                    <p class="text-gray-600 mb-6">
                        {{ trans('web/portfolio.hero_section.description') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="/contact-us" class="btn !text-white !bg-[#f78b77] border-[#f78b77] hover:text-white hover:bg-[#f67a63] hover:!border-[#f67a63] active:text-white active:bg-[#f78b77] active:border-[#f78b77] !rounded-[50rem] !mt-2 transition-all duration-300 hover:-translate-y-1">
                            {{ trans('web/portfolio.hero_section.contact_btn') }}
                        </a>
                        <a href="/services" class="btn !text-[#f78b77] !bg-[#fef4f2] border-[#fef4f2] hover:!text-white hover:!bg-[#f78b77] hover:!border-[#f78b77] active:text-white active:bg-[#f78b77] active:border-[#f78b77] !rounded-[50rem] !mt-2 transition-all duration-300 hover:-translate-y-1">
                            {{ trans('web/portfolio.hero_section.services_btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container  lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20 !text-center">
        <!-- /.row -->
        <div class="itemgrid grid-view projects-masonry">
            <div class="isotope-filter !relative z-[5] filter !mb-10">
                <ul class=" inline m-0 p-0 list-none">
                    <li class="inline"><a
                            class="filter-item uppercase !tracking-[0.02rem] text-[0.7rem] font-bold  cursor-pointer active"
                            data-filter="*">All</a></li>
                    <li
                        class="inline before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:ms-2 before:me-[0.8rem] before:my-0 before:rounded-[100%] before:bg-[rgba(30,34,40,.2)] before:align-[.15rem]">
                        <a class="filter-item uppercase !tracking-[0.02rem] text-[0.7rem] font-bold cursor-pointer hover:!text-[#3f78e0]"
                            data-filter=".foods">Foods</a></li>
                    <li
                        class="inline before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:ms-2 before:me-[0.8rem] before:my-0 before:rounded-[100%] before:bg-[rgba(30,34,40,.2)] before:align-[.15rem]">
                        <a class="filter-item uppercase !tracking-[0.02rem] text-[0.7rem] font-bold cursor-pointer hover:!text-[#3f78e0]"
                            data-filter=".drinks">Drinks</a></li>
                    <li
                        class="inline before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:ms-2 before:me-[0.8rem] before:my-0 before:rounded-[100%] before:bg-[rgba(30,34,40,.2)] before:align-[.15rem]">
                        <a class="filter-item uppercase !tracking-[0.02rem] text-[0.7rem] font-bold cursor-pointer hover:!text-[#3f78e0]"
                            data-filter=".events">Events</a></li>
                    <li
                        class="inline before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:ms-2 before:me-[0.8rem] before:my-0 before:rounded-[100%] before:bg-[rgba(30,34,40,.2)] before:align-[.15rem]">
                        <a class="filter-item uppercase !tracking-[0.02rem] text-[0.7rem] font-bold cursor-pointer hover:!text-[#3f78e0]"
                            data-filter=".pastries">Pastries</a></li>
                </ul>
            </div>
            <div class="flex flex-wrap mx-[-15px] md:mx-[-15px] !mt-[-30px] isotope">
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full drinks events">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf1-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf1.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Fringilla Nullam</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full events">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf2-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf2.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Ridiculus Parturient</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full drinks foods">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf3-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf3.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Ornare Ipsum</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full events">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf4-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf4.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Nullam Mollis</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full pastries events">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf5-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf5.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Euismod Risus</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full foods">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf6-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf6.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Ridiculus Tristique</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full foods drinks">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf7-full.jpg" data-glightbox data-gallery="shots-group"> <img
                                src="../../assets/img/photos/pf7.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Sollicitudin Pharetra</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full pastries">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf8-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf8.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Tristique Venenatis</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full events">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf9-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf9.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Cursus Fusce</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full foods">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf10-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf10.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Consectetur Malesuada</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full drinks">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf11-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf11.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Ultricies Aenean</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full foods">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf12-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf12.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Pellentesque Commodo</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
                <div
                    class="project item xl:w-4/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] !px-[15px] !mt-[30px] max-w-full drinks">
                    <figure class="overlay overlay-1 rounded group relative"><a
                            class=" relative block z-[3] cursor-pointer inset-0"
                            href="../../assets/img/photos/pf13-full.jpg" data-glightbox data-gallery="shots-group">
                            <img src="../../assets/img/photos/pf13.jpg" alt="image"></a>
                        <figcaption
                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center z-[5] pointer-events-none px-4 py-3 inset-0">
                            <h5
                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] px-4 py-3 left-0 top-2/4 group-hover:-translate-y-2/4">
                                Ultricies Aenean</h5>
                        </figcaption>
                    </figure>
                </div>
                <!-- /.project -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.grid -->
    </div>
</div>
