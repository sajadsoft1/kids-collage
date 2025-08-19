<div>
    <div class="wrapper !bg-[#fef4f2]">
        <div class="container py-[4.5rem] xl:!py-24 lg:!py-24 md:!py-24">
            <div class="flex flex-wrap items-start mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
                <div
                    class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!px-[20px] lg:!px-[20px] xl:!px-[35px]">
                    <div class="blog classic-view">
                        @foreach (range(1,3) as $value)
                            <article class="post !mb-8">
                                <div class="card">
                                    <figure class="card-img-top overlay overlay-1 hover-scale group"><a
                                            class="!text-[#343f52] hover:!text-[#3f78e0]" href="./blog-post.html"><img
                                                class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105"
                                                src="/assets/img/photos/b1.jpg" alt="image"></a>
                                        <figcaption
                                            class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                            <h5
                                                class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">
                                                Read More</h5>
                                        </figcaption>
                                    </figure>
                                    <div
                                        class="card-body flex-[1_1_auto] p-[40px] xl:!p-[2rem_2.5rem_1.25rem] lg:!p-[2rem_2.5rem_1.25rem] md:!p-[2rem_2.5rem_1.25rem] max-md:pb-4  ">
                                        <div class="post-header !mb-[.9rem]">
                                            <div
                                                class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                <a href="#" class="hover" rel="category">Teamwork</a>
                                            </div>
                                            <!-- /.post-category -->
                                            <h2 class="post-title !mt-1 !leading-[1.35] !mb-0"><a
                                                    class="!text-[#343f52] hover:!text-[#3f78e0]"
                                                    href="./blog-post.html">Amet Dolor Bibendum Parturient Cursus</a></h2>
                                        </div>
                                        <!-- /.post-header -->
                                        <div class="!relative">
                                            <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia
                                                odio sem nec elit. Nullam quis risus eget urna mollis ornare vel. Nulla
                                                vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel
                                                scelerisque nisl consectetur et. Sed posuere consectetur est at lobortis.
                                                Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac
                                                cursus commodo, tortor mauris condimentum nibh. Cras mattis consectetur
                                                purus.</p>
                                        </div>
                                        <!-- /.post-content -->
                                    </div>
                                    <!--/.card-body -->
                                    <div
                                        class="card-footer xl:!p-[1.25rem_2.5rem_1.25rem] lg:!p-[1.25rem_2.5rem_1.25rem] md:!p-[1.25rem_2.5rem_1.25rem] p-[18px_40px]">
                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none flex  !mb-0">
                                            <li class="post-date inline-block"><i
                                                    class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>5
                                                    Jul 2022</span></li>
                                            <li
                                                class="post-author inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]">
                                                <a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                    href="#"><i
                                                        class="uil uil-user pr-[0.2rem] align-[-.05rem] before:content-['\ed6f']"></i><span>By
                                                        Sandbox</span></a></li>
                                            <li
                                                class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]">
                                                <a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                    href="#"><i
                                                        class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>3<span>
                                                        Comments</span></a></li>
                                            <li class="post-likes !ms-auto inline-block"><a
                                                    class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                    href="#"><i
                                                        class="uil uil-heart-alt pr-[0.2rem] align-[-.05rem] before:content-['\eb60']"></i>3</a>
                                            </li>
                                        </ul>
                                        <!-- /.post-meta -->
                                    </div>
                                    <!-- /.card-footer -->
                                </div>
                                <!-- /.card -->
                            </article>
                        @endforeach
                    </div>
                    <!-- /.blog -->
                    <div class="blog itemgrid grid-view">
                        <div
                            class="flex flex-wrap mx-[-15px] isotope xl:mx-[-20px] lg:mx-[-20px] md:mx-[-20px] !mt-[-40px] !mb-8">
                            @foreach (range(1,3) as $value)
                                <article
                                    class="item post xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:!px-[20px] lg:!px-[20px] md:!px-[20px] !mt-[40px] !px-[15px] max-w-full">
                                    <div class="card">
                                        <figure class="card-img-top overlay overlay-1 hover-scale group"><a
                                                href="#"> <img
                                                    class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105"
                                                    src="/assets/img/photos/b4.jpg" alt="image"></a>
                                            <figcaption
                                                class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                                <h5
                                                    class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">
                                                    Read More</h5>
                                            </figcaption>
                                        </figure>
                                        <div
                                            class="card-body flex-[1_1_auto] p-[40px] xl:!p-[1.75rem_1.75rem_1rem_1.75rem] lg:!p-[1.75rem_1.75rem_1rem_1.75rem] md:!p-[1.75rem_1.75rem_1rem_1.75rem] max-md:pb-4  ">
                                            <div class="post-header !mb-[.9rem]">
                                                <div
                                                    class="inline-flex !mb-[.4rem] uppercase !tracking-[0.02rem] text-[0.7rem] font-bold !text-[#aab0bc] relative align-top !pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 before:bg-[#3f78e0]">
                                                    <a href="#" class="hover" rel="category">Coding</a>
                                                </div>
                                                <!-- /.post-category -->
                                                <h2 class="post-title h3 !mt-1 !mb-3"><a
                                                        class="!text-[#343f52] hover:!text-[#3f78e0]"
                                                        href="./blog-post.html">Ligula tristique quis risus</a></h2>
                                            </div>
                                            <!-- /.post-header -->
                                            <div class="!relative">
                                                <p>Mauris convallis non ligula non interdum. Gravida vulputate convallis
                                                    tempus vestibulum cras imperdiet nun eu dolor. Aenean lacinia bibendum
                                                    nulla sed.</p>
                                            </div>
                                            <!-- /.post-content -->
                                        </div>
                                        <!--/.card-body -->
                                        <div
                                            class="card-footer xl:!p-[1.25rem_1.75rem_1.25rem] lg:!p-[1.25rem_1.75rem_1.25rem] md:!p-[1.25rem_1.75rem_1.25rem] p-[18px_40px]">
                                            <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none flex  !mb-0">
                                                <li class="post-date inline-block"><i
                                                        class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>14
                                                        Apr 2022</span></li>
                                                <li
                                                    class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]">
                                                    <a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                        href="#"><i
                                                            class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>4</a>
                                                </li>
                                                <li class="post-likes !ms-auto inline-block"><a
                                                        class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                        href="#"><i
                                                            class="uil uil-heart-alt pr-[0.2rem] align-[-.05rem] before:content-['\eb60']"></i>5</a>
                                                </li>
                                            </ul>
                                            <!-- /.post-meta -->
                                        </div>
                                        <!-- /.card-footer -->
                                    </div>
                                    <!-- /.card -->
                                </article>
                            @endforeach
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.blog -->
                    <nav class="flex" aria-label="pagination">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true"><i
                                            class="uil uil-arrow-left before:content-['\e949']"></i></span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true"><i
                                            class="uil uil-arrow-right before:content-['\e94c']"></i></span>
                                </a>
                            </li>
                        </ul>
                        <!-- /.pagination -->
                    </nav>
                    <!-- /nav -->
                </div>
                <!-- /column -->
                <aside
                    class="sticky top-10 xl:w-4/12 lg:w-4/12 w-full bg-white py-8 rounded-xl flex-[0_0_auto] xl:!px-[35px] lg:!px-[20px] !px-[15px] max-w-full sidebar">
                    <div class="widget">
                        <form
                            class="search-form relative before:content-['\eca5'] before:block before:absolute before:-translate-y-2/4 before:text-[0.9rem] before:!text-[#959ca9] before:z-[9] before:right-3 before:top-2/4 font-Unicons">
                            <div class="form-floating relative !mb-0">
                                <input id="search-form" type="text"
                                    class="form-control relative block w-full text-[.75rem] font-medium !text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out     focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset]   focus-visible:!border-[rgba(63,120,224,0.5)]   placeholder:!text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] !leading-[1.25]"
                                    placeholder="">
                                <label for="search-form"
                                    class="inline-block !text-[#959ca9] text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope"
                                >
                                    {{ trans('web/blogs.sidebar.search') }}
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="widget !mt-[40px]">
                        <h4 class="widget-title !mb-3">{{ trans('web/blogs.sidebar.popular') }}</h4>
                        <ul class="m-0 p-0 after:content-[''] after:block after:h-0 after:clear-both after:invisible">
                            @foreach (range(1,3) as $value)
                                <li class=" clear-both block overflow-hidden">
                                    <figure class="!rounded-[.4rem] float-left w-14 !h-[4.5rem]"><a
                                            href="./blog-post.html"><img class="!rounded-[.4rem]"
                                                src="/assets/img/photos/a1.jpg" alt="image"></a></figure>
                                    <div class="!relative !ms-[4.25rem] !mb-0">
                                        <h6 class="!mb-2"> <a class="!text-[#343f52] hover:!text-[#3f78e0]"
                                                href="./blog-post.html">Magna Mollis Ultricies</a> </h6>
                                        <ul class="!text-[0.7rem] !text-[#aab0bc] m-0 p-0 list-none">
                                            <li class="post-date inline-block"><i
                                                    class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>26
                                                    Mar 2022</span></li>
                                            <li
                                                class="post-comments inline-block before:content-[''] before:inline-block before:w-[0.2rem] before:h-[0.2rem] before:opacity-50 before:m-[0_.6rem_0] before:rounded-[100%] before:align-[.15rem] before:bg-[#aab0bc]">
                                                <a class="!text-[#aab0bc] hover:!text-[#3f78e0] hover:!border-[#3f78e0]"
                                                    href="#"><i
                                                        class="uil uil-comment pr-[0.2rem] align-[-.05rem] before:content-['\ea54']"></i>3</a>
                                            </li>
                                        </ul>
                                        <!-- /.post-meta -->
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.widget -->
                    <div class="widget !mt-[40px]">
                        <h4 class="widget-title !mb-3">{{ trans('web/blogs.sidebar.categories') }}</h4>
                        <ul class="pl-0 list-none bullet-primary !text-inherit">
                            @foreach (range(1,3) as $value)
                                <li class="relative !pl-[1rem] before:absolute  before:top-[-0.15rem] before:text-[1rem] before:content-['\2022'] before:left-0 before:font-SansSerif">
                                    <a class="!text-[#60697b] hover:!text-[#3f78e0]" href="#">Teamwork (21)</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.widget -->
                    <div class="widget !mt-[40px]">
                        <h4 class="widget-title !mb-3">{{ trans('web/blogs.sidebar.tags') }}</h4>
                        <ul class="pl-0 list-none tag-list">
                            @foreach (range(1,3) as $value)
                                <li class="!mt-0 !mb-[0.45rem] !me-[0.2rem] inline-block">
                                    <a href="#"
                                        class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic before:content-['#'] before:font-normal before:!pr-[0.2rem]">Still
                                        Life
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.widget -->
                </aside>
                <!-- /column .sidebar -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </div>
</div>
