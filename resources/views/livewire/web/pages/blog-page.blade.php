@php use App\Helpers\Constants; @endphp
<div class="">
    <!-- BREADCRUMB SECTION START -->
    <section
        class="pt-[327px] xl:pt-[287px] lg:pt-[237px] sm:pt-[200px] xxs:pt-[180px] pb-[158px] xl:pb-[118px]
         lg:pb-[98px] sm:pb-[68px] xs:pb-[48px] text-center bg-no-repeat bg-cover bg-center relative z-[1] overflow-hidden before:absolute before:-z-[1] before:inset-0 before:bg-edblue/70 before:pointer-events-none"
        style="background-image: url('/assets/web/img/breadcrumb-bg.jpg');">
        <div class="mx-[19.71%] xxxl:mx-[14.71%] xxl:mx-[9.71%] xl:mx-[5.71%] md:mx-[12px]">
            <h1 class="font-semibold text-[clamp(35px,6vw,56px)] text-white">{{ trans('web/blog.title') }}</h1>
            <ul class="flex items-center justify-center gap-[10px] text-white">
                <li><a href="{{ localized_route('home-page') }}"
                        class="text-edyellow">{{ trans('web/general.header.home') }}</a></li>
                <li><span class="text-[12px]"><i class="fa-solid fa-angle-double-right"></i></span></li>
                <li>{{ trans('web/general.header.blogs') }}</li>
            </ul>
        </div>

        <div class="vectors">
            <img src="/assets/web/img/breadcrumb-vector-1.svg" alt="vector"
                class="absolute -z-[1] pointer-events-none bottom-[34px] left-0 xl:left-auto xl:right-[90%]">
            <img src="/assets/web/img/breadcrumb-vector-2.svg" alt="vector"
                class="absolute -z-[1] pointer-events-none bottom-0 right-0 xl:right-auto xl:left-[60%]">
        </div>
    </section>
    <!-- BREADCRUMB SECTION END -->


    <!-- MAIN CONTENT START -->
    <div class="ed-event-details-content py-[120px] xl:py-[80px] md:py-[60px]">
        <div class="mx-[19.71%] xxxl:mx-[14.71%] xxl:mx-[9.71%] xl:mx-[5.71%] md:mx-[12px]">
            <div class="flex gap-[30px] lg:gap-[20px] md:flex-col md:items-center">
                <div class="left grow space-y-[30px] md:space-y-[20px]">

                    <!-- single blog -->
                    @foreach ($blogs as $blog)
                        <div class="border border-[#D9D9D9] rounded-[8px] p-[24px] xxs:p-[18px]">
                            <div class="img overflow-hidden rounded-[8px] mb-[30px] relative">
                                <img src="{{ $blog->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                    alt="blog-cover" class="w-full">

                                <div
                                    class="bg-[#ECF0F5] rounded-[10px] font-medium text-[14px] text-black inline-block uppercase overflow-hidden text-center absolute top-[20px] left-[20px]">
                                    <span class="bg-edpurple text-white block py-[3px] rounded-[10px]">
                                        {{ jdate($blog->created_at)->format('%d') }}
                                    </span>
                                    <span class="px-[11px] py-[2px] block">
                                        {{ jdate($blog->created_at)->format('%B') }}
                                    </span>
                                </div>
                            </div>

                            <!-- txt -->
                            <div>
                                <!-- infos -->
                                <div class="flex items-center gap-[30px] mb-[7px]">
                                    <!-- single info -->
                                    <div class="flex gap-[10px] items-center">
                                        <span class="icon shrink-0"><img class="w-10 h-10 rounded-full"
                                                src="{{ $blog->user->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) }}"
                                                alt="icon"></span>
                                        <span class="text-[14px] text-edgray">{{ trans('web/general.by') }}
                                            <a href="#">{{ $blog->user->full_name }}</a>
                                        </span>
                                    </div>

                                    <!-- single info -->
                                    <div class="flex gap-[10px] items-center">
                                        <span class="icon shrink-0"><i class="fa fa-layer-group"></i></span>
                                        <span class="text-[14px] text-edgray">
                                            <a
                                                href="{{ localized_route('search', ['category' => $blog->category->slug]) }}">{{ $blog->category->title }}</a>
                                        </span>
                                    </div>
                                </div>

                                <h3
                                    class="text-[30px] lg:text-[27px] sm:text-[24px] xxs:text-[22px] font-semibold text-black mb-[10px]">
                                    <a href="{{ $blog->path() }}" class="hover:text-edpurple">
                                        {{ $blog->title }}
                                    </a>
                                </h3>

                                <p class="font-normal text-[16px] text-edgray mb-[16px]">
                                    {{ $blog->description }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <a href="{{ $blog->path() }}"
                                        class="text-edpurple text-[16px] hover:text-edyellow">
                                        {{ trans('web/general.read_more') }} <span class="ps-[5px]">
                                            <i class="fa-solid ltr:fa-arrow-right-long rtl:fa-arrow-left-long"></i>
                                        </span>
                                    </a>

                                    <button
                                        class="h-[50px] px-[22px] border border-edpurple rounded-[8px] flex gap-[8px] items-center justify-center group hover:text-white hover:bg-edpurple">
                                        <span>{{ trans('web/general.share') }}</span>
                                        <span class="icon">
                                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.5471 14.7982C14.493 14.7982 13.5433 15.2534 12.8842 15.9775L6.95572 12.3057C7.11849 11.8896 7.20194 11.4467 7.20176 11C7.20197 10.5532 7.11852 10.1103 6.95572 9.69429L12.8842 6.02232C13.5434 6.74642 14.493 7.20181 15.5471 7.20181C17.5327 7.20181 19.1481 5.58644 19.1481 3.60082C19.1481 1.61519 17.5327 0 15.5471 0C13.5615 0 11.9461 1.61536 11.9461 3.60099C11.9461 4.04774 12.0295 4.49056 12.1922 4.90663L6.26384 8.57848C5.6047 7.85437 4.65505 7.39899 3.60099 7.39899C1.61536 7.39899 0 9.01453 0 11C0 12.9856 1.61536 14.601 3.60099 14.601C4.65501 14.601 5.60475 14.1458 6.26384 13.4215L12.1922 17.0933C12.0295 17.5094 11.9461 17.9523 11.9461 18.3991C11.9461 20.3846 13.5615 22 15.5471 22C17.5327 22 19.1481 20.3846 19.1481 18.3992C19.1481 16.4135 17.5327 14.7982 15.5471 14.7982ZM13.2592 3.60099C13.2592 2.33943 14.2856 1.31308 15.5471 1.31308C16.8086 1.31308 17.835 2.33943 17.835 3.60099C17.835 4.86255 16.8087 5.8889 15.5471 5.8889C14.2855 5.8889 13.2592 4.86251 13.2592 3.60099ZM3.60099 13.2879C2.33926 13.2879 1.31291 12.2615 1.31291 11C1.31291 9.73846 2.33926 8.71207 3.60099 8.71207C4.86255 8.71207 5.88873 9.73846 5.88873 11C5.88873 12.2615 4.86251 13.2879 3.60099 13.2879ZM13.2592 18.399C13.2592 17.1375 14.2856 16.1111 15.5471 16.1111C16.8086 16.1111 17.835 17.1375 17.835 18.399C17.835 19.6605 16.8087 20.6869 15.5471 20.6869C14.2855 20.6869 13.2592 19.6605 13.2592 18.399V18.399Z"
                                                    class="fill-edpurple group-hover:fill-white" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <!-- PAGINATION START -->
                    {{ $blogs->links() }}
                    <!-- PAGINATION END -->
                </div>

                <!-- right sidebar -->
                <div class="right max-w-full w-[370px] lg:w-[360px] shrink-0 space-y-[30px] md:space-y-[25px]">
                    <!-- search widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            {{ trans('web/general.search') }}</h4>

                        <!-- search form -->
                        <form action="{{ localized_route('search') }}"
                            class="border border-[#e5e5e5] rounded-[8px] flex h-[60px] px-[20px] mt-[30px]">
                            <input type="search" name="q" id="ed-news-search"
                                class="w-full bg-transparent text-[16px] focus:outline-none"
                                placeholder="{{ trans('web/general.search_here') }}">
                            <button type="submit" class="text-[16px] hover:text-edpurple"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <!-- Categories widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            {{ trans('web/general.categories') }}</h4>

                        <ul class="mt-[30px] text-[16px]">
                            @foreach ($categories as $category)
                                <li class="text-black py-[16px] border-b border-[#D9D9D9]" @class(['!border-b-0' => $loop->last])>
                                    <a href="{{ localized_route('search', ['category' => $category->slug]) }}"
                                        class="flex items-center justify-between hover:text-edpurple">
                                        <span>{{ $category->title }}</span>
                                        <span>({{ $category->blogs()->count() }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recent Post widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            {{ trans('web/general.latest_blogs') }}</h4>

                        <div class="posts mt-[30px] space-y-[24px]">
                            <!-- single post -->
                            @foreach ($latest_blogs as $blog)
                                <div class="flex items-center gap-[16px]">
                                    <div class="rounded-[6px] w-[78px] h-[79px] overflow-hidden shrink-0">
                                        <a href="{{ $blog->path() }}">
                                            <img src="{{ $blog->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                                alt="Post Image" class="object-cover h-full">
                                        </a>
                                    </div>

                                    <div>
                                        <span
                                            class="date text-[14px] text-edgray flex items-center gap-[12px] mb-[3px]">
                                            <i class="fa-solid fa-calendar-days text-edpurple"></i>
                                            <span>{{ jdate($blog->created_at)->format('%d %B %Y') }}</span>
                                        </span>

                                        <h6 class="font-semibold text-[15px] text-black"><a href="{{ $blog->path() }}"
                                                class="hover:text-edpurple">{{ $blog->title }}</a></h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            {{ trans('web/general.tags') }}</h4>

                        <div class="flex flex-wrap gap-[10px] mt-[30px]">
                            @foreach ($tags as $tag)
                                <a href="{{ localized_route('search', ['tag' => $tag->slug]) }}"
                                    class="border border-[#e5e5e5] text-[14px] text-[#181818] px-[12px] py-[5px] rounded-[4px] hover:bg-edpurple hover:border-edpurple hover:text-white">{{ $tag->title }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
</div>
