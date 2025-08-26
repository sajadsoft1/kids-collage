@php use App\Helpers\Constants; @endphp
<div class="">
    <!-- BREADCRUMB SECTION START -->
    <section
        class="pt-[327px] xl:pt-[287px] lg:pt-[237px] sm:pt-[200px] xxs:pt-[180px] pb-[158px] xl:pb-[118px]
         lg:pb-[98px] sm:pb-[68px] xs:pb-[48px] text-center bg-no-repeat bg-cover bg-center relative z-[1] overflow-hidden before:absolute before:-z-[1] before:inset-0 before:bg-edblue/70 before:pointer-events-none"
        style="background-image: url('/assets/web/img/breadcrumb-bg.jpg');">
        <div class="mx-[19.71%] xxxl:mx-[14.71%] xxl:mx-[9.71%] xl:mx-[5.71%] md:mx-[12px]">
            <h1 class="font-semibold text-[clamp(35px,6vw,56px)] text-white">News List</h1>
            <ul class="flex items-center justify-center gap-[10px] text-white">
                <li><a href="index.html" class="text-edyellow">Home</a></li>
                <li><span class="text-[12px]"><i class="fa-solid fa-angle-double-right"></i></span></li>
                <li>News List</li>
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
                                        {{ $blog->created_at->format('d') }}
                                    </span>
                                    <span class="px-[11px] py-[2px] block">
                                        {{ $blog->created_at->format('F') }}
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
                                        <span class="text-[14px] text-edgray">by <a
                                                href="#">{{ $blog->user->full_name }}</a></span>
                                    </div>

                                    <!-- single info -->
                                    <div class="flex gap-[10px] items-center">
                                        <span class="icon shrink-0"><img src="assets/img/icon/tag.svg"
                                                alt="icon"></span>
                                        <span class="text-[14px] text-edgray"><a
                                                href="#">{{ $blog->category->name }}</a></span>
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
                                    <a href="{{ $blog->path() }}" class="text-edpurple text-[16px] hover:text-edyellow">
                                        {{ trans('web/general.read_more') }} <span class="pl-[5px]"><i
                                                class="fa-solid fa-arrow-right-long"></i></span></a>

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
                        <form action="#"
                            class="border border-[#e5e5e5] rounded-[8px] flex h-[60px] px-[20px] mt-[30px]">
                            <input type="search" name="search" id="ed-news-search"
                                class="w-full bg-transparent text-[16px] focus:outline-none"
                                placeholder="Search here..">
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
                            @foreach (range(1, 10) as $index)
                                <li class="text-black py-[16px] border-b border-t border-[#D9D9D9]">
                                    <a href="#" class="flex items-center justify-between hover:text-edpurple">
                                        <span>Art & Design</span>
                                        <span>(2)</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recent Post widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            {{ trans('web/general.recent_posts') }}</h4>

                        <div class="posts mt-[30px] space-y-[24px]">
                            <!-- single post -->
                            @foreach (range(1, 10) as $index)
                                <div class="flex items-center gap-[16px]">
                                    <div class="rounded-[6px] w-[78px] h-[79px] overflow-hidden shrink-0">
                                        <img src="/assets/web/img/blog-1.png" alt="Post Image"
                                            class="object-cover h-full">
                                    </div>

                                    <div>
                                        <span
                                            class="date text-[14px] text-edgray flex items-center gap-[12px] mb-[3px]">
                                            <span class="icon">
                                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M15.6756 4.42875C15.5769 2.78656 14.2056 1.5 12.5541 1.5H11.75V0.875C11.75 0.70924 11.6842 0.550268 11.5669 0.433058C11.4497 0.315848 11.2908 0.25 11.125 0.25C10.9592 0.25 10.8003 0.315848 10.6831 0.433058C10.5659 0.550268 10.5 0.70924 10.5 0.875V1.5H5.50001V0.875C5.50001 0.70924 5.43416 0.550268 5.31695 0.433058C5.19974 0.315848 5.04077 0.25 4.87501 0.25C4.70924 0.25 4.55027 0.315848 4.43306 0.433058C4.31585 0.550268 4.25001 0.70924 4.25001 0.875V1.5H3.44594C1.79407 1.5 0.422818 2.78656 0.32438 4.42875C0.139068 7.5175 0.142505 10.6506 0.334693 13.7409C0.432193 15.3103 1.68938 16.5675 3.25875 16.665C4.83157 16.7628 6.41563 16.8116 7.99969 16.8116C9.58344 16.8116 11.1678 16.7628 12.7406 16.665C14.31 16.5675 15.5672 15.3103 15.6647 13.7409C15.8572 10.6522 15.8606 7.51937 15.6756 4.42875ZM14.4175 13.6634C14.3885 14.1191 14.1944 14.5487 13.8716 14.8716C13.5487 15.1944 13.1192 15.3885 12.6634 15.4175C9.56907 15.6097 6.43094 15.6097 3.33657 15.4175C2.88086 15.3885 2.45134 15.1944 2.12845 14.8716C1.80557 14.5487 1.61147 14.1191 1.58251 13.6634C1.43601 11.2785 1.40296 8.88803 1.48344 6.5H14.5169C14.5956 8.8875 14.5653 11.2884 14.4175 13.6634ZM4.87501 4C5.04077 4 5.19974 3.93415 5.31695 3.81694C5.43416 3.69973 5.50001 3.54076 5.50001 3.375V2.75H10.5V3.375C10.5 3.54076 10.5659 3.69973 10.6831 3.81694C10.8003 3.93415 10.9592 4 11.125 4C11.2908 4 11.4497 3.93415 11.5669 3.81694C11.6842 3.69973 11.75 3.54076 11.75 3.375V2.75H12.5541C13.5456 2.75 14.3688 3.52031 14.4278 4.50344C14.4425 4.75156 14.4488 5.00125 14.4609 5.25H1.53907C1.55157 5.00125 1.55751 4.75156 1.57219 4.50344C1.63126 3.52031 2.45407 2.75 3.44594 2.75H4.25001V3.375C4.25001 3.54076 4.31585 3.69973 4.43306 3.81694C4.55027 3.93415 4.70924 4 4.87501 4Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M4.875 10.25C5.39277 10.25 5.8125 9.83027 5.8125 9.3125C5.8125 8.79473 5.39277 8.375 4.875 8.375C4.35723 8.375 3.9375 8.79473 3.9375 9.3125C3.9375 9.83027 4.35723 10.25 4.875 10.25Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M8 10.25C8.51777 10.25 8.9375 9.83027 8.9375 9.3125C8.9375 8.79473 8.51777 8.375 8 8.375C7.48223 8.375 7.0625 8.79473 7.0625 9.3125C7.0625 9.83027 7.48223 10.25 8 10.25Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M4.875 13.375C5.39277 13.375 5.8125 12.9553 5.8125 12.4375C5.8125 11.9197 5.39277 11.5 4.875 11.5C4.35723 11.5 3.9375 11.9197 3.9375 12.4375C3.9375 12.9553 4.35723 13.375 4.875 13.375Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M11.125 10.25C11.6428 10.25 12.0625 9.83027 12.0625 9.3125C12.0625 8.79473 11.6428 8.375 11.125 8.375C10.6072 8.375 10.1875 8.79473 10.1875 9.3125C10.1875 9.83027 10.6072 10.25 11.125 10.25Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M11.125 13.375C11.6428 13.375 12.0625 12.9553 12.0625 12.4375C12.0625 11.9197 11.6428 11.5 11.125 11.5C10.6072 11.5 10.1875 11.9197 10.1875 12.4375C10.1875 12.9553 10.6072 13.375 11.125 13.375Z"
                                                        class="fill-edpurple" />
                                                    <path
                                                        d="M8 13.375C8.51777 13.375 8.9375 12.9553 8.9375 12.4375C8.9375 11.9197 8.51777 11.5 8 11.5C7.48223 11.5 7.0625 11.9197 7.0625 12.4375C7.0625 12.9553 7.48223 13.375 8 13.375Z"
                                                        class="fill-edpurple" />
                                                </svg>
                                            </span>
                                            <span>18 Dec, 2024</span>
                                        </span>

                                        <h6 class="font-semibold text-[15px] text-black"><a href="news-details.html"
                                                class="hover:text-edpurple">Keep Your Business Safe & Endure High
                                                Availability</a></h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags widget -->
                    <div class="bg-edoffwhite rounded-[10px] p-[30px] xxs:px-[20px] pt-[20px] xxs:pt-[10px]">
                        <h4
                            class="font-semibold text-[18px] text-black border-b border-[#dddddd] relative pb-[11px] before:content-normal before:absolute before:left-0 before:bottom-0 before:w-[50px] before:h-[2px] before:bg-edpurple">
                            Tags</h4>

                        <div class="flex flex-wrap gap-[10px] mt-[30px]">
                            @foreach (range(1, 10) as $index)
                                <a href="#"
                                    class="border border-[#e5e5e5] text-[14px] text-[#181818] px-[12px] py-[5px] rounded-[4px] hover:bg-edpurple hover:border-edpurple hover:text-white">Technology</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
</div>
