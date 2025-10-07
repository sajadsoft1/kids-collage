@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
        <div class="col-span-12 xl:col-span-8">
            <div class="flex flex-col p-5 sm:p-14">
                <div class="flex flex-col gap-y-7 rounded-lg bg-base-100 border border-primary px-8 py-12 sm:-mx-10 sm:-mt-10 sm:px-10 sm:py-16 md:flex-row">
                    <div class="flex flex-col justify-center">
                        <div class="flex h-[50px] w-[50px] items-center justify-center rounded-[0.6rem] border border-primary/50">
                            <div class="flex h-[45px] w-[45px] items-center justify-center rounded-lg rtl:bg-gradient-to-l ltr:bg-gradient-to-r from-theme-1/90 to-theme-2/90 transition-transform ease-in-out group-[.side-menu--collapsed.side-menu--on-hover]:-rotate-180">
                                <div class="relative h-[23px] w-[23px] -rotate-45 [&_div]:bg-white">
                                    <div class="absolute inset-y-0 rtl:right-0 ltr:left-0 my-auto h-[75%] w-[21%] rounded-full opacity-50">
                                    </div>
                                    <div class="absolute inset-0 m-auto h-[120%] w-[21%] rounded-full"></div>
                                    <div class="absolute inset-y-0 rtl:left-0 ltr:right-0 my-auto h-[75%] w-[21%] rounded-full opacity-50">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3.5 text-lg font-medium text-slate-600/90">
                            شرکت خصوصی تیلوایس
                        </div>
                    </div>
                    <div class="rtl:md:mr-auto ltr:md:ml-auto rtl:md:text-left ltr:md:text-right">
                        <div class="-mt-1 text-lg font-medium text-primary">
                            # صورتحساب
                        </div>
                        <div class="mt-1">
                            IVR/20240301/X/VIII/527300113
                        </div>
                        <div class="mt-7 flex flex-col gap-1">
                            <div>خیابان بیرچ ۲۳۴</div>
                            <div>آپارتمان 301</div>
                            <div>میامی، ایالات متحده</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col px-8 pt-4 sm:flex-row sm:px-0 bg-base-100">
                    <div>
                        <div class="text-slate-500">صورتحساب برای :</div>
                        <div class="mt-1.5 text-base font-medium text-primary">
                            لئوناردو دی‌کاپریو
                        </div>
                        <div class="mt-1.5 flex flex-col gap-1">
                            <div>خیابان میپل ۶۷۸</div>
                            <div>طبقه ۲A</div>
                            <div>هیوستون، ایالات متحده</div>
                        </div>
                    </div>
                    <div class="mt-7 flex flex-col gap-4 rtl:sm:mr-auto ltr:sm:ml-auto sm:mt-0 rtl:sm:text-left ltr:sm:text-right">
                        <div>
                            <div class="text-slate-500">تاریخ فاکتور :</div>
                            <div class="mt-1.5 font-medium text-slate-600">
                                شنبه تیر ۱۴۰۲
                            </div>
                        </div>
                        <div>
                            <div class="text-slate-500">تاریخ سررسید :</div>
                            <div class="mt-1.5 font-medium text-slate-600">
                                شنبه تیر ۱۴۰۲
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-10 rounded-[0.6rem] border border-slate-200/80 bg-base-100">
                    <div class="overflow-auto xl:overflow-visible">
                        <table data-tw-merge="" class="w-full rtl:text-right ltr:text-left">
                            <thead data-tw-merge="" class="">
                            <tr data-tw-merge="" class="">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 font-medium text-slate-500 rtl:first:rounded-tr-[0.6rem] ltr:first:rounded-tl-[0.6rem] rtl:last:rounded-tl-[0.6rem] ltr:last:rounded-tr-[0.6rem]">
                                    مورد
                                </td>
                                <td data-tw-merge=""
                                    class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 rtl:text-left ltr:text-right font-medium text-slate-500 rtl:first:rounded-tr-[0.6rem] ltr:first:rounded-tl-[0.6rem] rtl:last:rounded-tl-[0.6rem] ltr:last:rounded-tr-[0.6rem]">
                                    تعداد
                                </td>
                                <td data-tw-merge=""
                                    class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 rtl:text-left ltr:text-right font-medium text-slate-500 rtl:first:rounded-tr-[0.6rem] ltr:first:rounded-tl-[0.6rem] rtl:last:rounded-tl-[0.6rem] ltr:last:rounded-tr-[0.6rem]">
                                    نرخ
                                </td>
                                <td data-tw-merge=""
                                    class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 rtl:text-left ltr:text-right font-medium text-slate-500 rtl:first:rounded-tr-[0.6rem] ltr:first:rounded-tl-[0.6rem] rtl:last:rounded-tl-[0.6rem] ltr:last:rounded-tr-[0.6rem]">
                                    مقدار
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr data-tw-merge="" class="[&_td]:last:border-b-0">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="image-fit zoom-in box rtl:ml-2.5 ltr:mr-2.5 h-6 w-6 overflow-hidden rounded-full border-2 border-slate-200/70">
                                            <img src="dist/images/products/product10-400x400.jpg" alt="تیل وایز - قالب داشبورد مدیریتی">
                                        </div>
                                        <div class="whitespace-nowrap">
                                            تلویزیون هوشمند 4K Ultra HD
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        3
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        599 تومان
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium">
                                        1.799 تومان
                                    </div>
                                </td>
                            </tr>
                            <tr data-tw-merge="" class="[&_td]:last:border-b-0">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="image-fit zoom-in box rtl:ml-2.5 ltr:mr-2.5 h-6 w-6 overflow-hidden rounded-full border-2 border-slate-200/70">
                                            <img src="dist/images/products/product10-400x400.jpg" alt="تیل وایز - قالب داشبورد مدیریتی">
                                        </div>
                                        <div class="whitespace-nowrap">
                                            دوربین امنیتی خانه هوشمند
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        3
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        129 تومان
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium">
                                        389 تومان
                                    </div>
                                </td>
                            </tr>
                            <tr data-tw-merge="" class="[&_td]:last:border-b-0">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="image-fit zoom-in box rtl:ml-2.5 ltr:mr-2.5 h-6 w-6 overflow-hidden rounded-full border-2 border-slate-200/70">
                                            <img src="dist/images/products/product6-400x400.jpg" alt="تیل وایز - قالب داشبورد مدیریتی">
                                        </div>
                                        <div class="whitespace-nowrap">
                                            تلویزیون هوشمند 4K Ultra HD
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        4
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        599 تومان
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium">
                                        1.199 تومان
                                    </div>
                                </td>
                            </tr>
                            <tr data-tw-merge="" class="[&_td]:last:border-b-0">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="image-fit zoom-in box rtl:ml-2.5 ltr:mr-2.5 h-6 w-6 overflow-hidden rounded-full border-2 border-slate-200/70">
                                            <img src="dist/images/products/product1-400x400.jpg" alt="تیل وایز - قالب داشبورد مدیریتی">
                                        </div>
                                        <div class="whitespace-nowrap">
                                            بلندگوی بلوتوث با تقویت بیس
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        2
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        79 تومان
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium">
                                        239 تومان
                                    </div>
                                </td>
                            </tr>
                            <tr data-tw-merge="" class="[&_td]:last:border-b-0">
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="image-fit zoom-in box rtl:ml-2.5 ltr:mr-2.5 h-6 w-6 overflow-hidden rounded-full border-2 border-slate-200/70">
                                            <img src="dist/images/products/product4-400x400.jpg" alt="تیل وایز - قالب داشبورد مدیریتی">
                                        </div>
                                        <div class="whitespace-nowrap">
                                            دوربین حرفه‌ای دیجیتال
                                        </div>
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        3
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap">
                                        799 تومان
                                    </div>
                                </td>
                                <td data-tw-merge="" class="px-5 border-b dark:border-darkmode-300 border-dashed py-4 rtl:text-left ltr:text-right dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium">
                                        2.399 تومان
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="my-10 rtl:mr-auto ltr:ml-auto flex flex-col gap-3.5 rtl:pl-5 ltr:pr-5 rtl:text-left ltr:text-right bg-base-100">
                    <div class="flex items-center justify-end">
                        <div class="text-slate-500">جمع جزئی:</div>
                        <div class="w-20 font-medium text-slate-600 sm:w-52">
                            1.580 تومان
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <div class="text-slate-500">جمع:</div>
                        <div class="w-20 font-medium text-slate-600 sm:w-52">
                            1.466 تومان
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <div class="text-slate-500">مالیات:</div>
                        <div class="w-20 font-medium text-slate-600 sm:w-52">
                            38 تومان
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <div class="text-slate-500">مقدار پرداخت شده:</div>
                        <div class="w-20 font-medium text-slate-600 sm:w-52">
                            1.494 تومان
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <div class="text-slate-500">مانده بدهی:</div>
                        <div class="w-20 font-medium text-slate-600 sm:w-52">
                            24 تومان
                        </div>
                    </div>
                </div>
                <div class="-mx-8 border-t border-dashed border-slate-200/80 px-10 pt-6 bg-base-100">
                    <div class="text-base font-medium">
                        سوالی درباره صورتحساب خود دارید؟
                    </div>
                    <div class="mt-1 text-slate-500">
                        با ما تماس بگیرید برای کمک در هر مسأله‌ای مربوط به صورتحساب.
                    </div>
                    <div class="mt-5 text-slate-500">© 2046 left4code.</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-4">
            <div class="flex flex-col p-5 bg-base-100">
                <div class="mb-5 border-b border-dashed border-slate-300/70 pb-5 text-[0.94rem] font-medium">
                    تاریخچه
                </div>
                <div>
                    <div class="flex">
                        <div>
                                                    <span class="text-lg font-medium">
                                                        2.565
                                                    </span>
                            <span>فاکتور</span>
                        </div>
                    </div>
                    <div class="mt-3.5 flex h-2">
                        <div class="h-full w-[35%] border border-primary/50 bg-primary/50 rtl:first:rounded-r ltr:first:rounded-l rtl:last:rounded-l ltr:last:rounded-r">
                        </div>
                        <div class="h-full w-[20%] border border-info/50 bg-info/50 rtl:first:rounded-r ltr:first:rounded-l rtl:last:rounded-l ltr:last:rounded-r">
                        </div>
                        <div class="h-full w-[45%] border border-success/50 bg-success/50 rtl:first:rounded-r ltr:first:rounded-l rtl:last:rounded-l ltr:last:rounded-r">
                        </div>
                    </div>
                    <div class="mt-8">
                        <ul data-tw-merge="" role="tablist" class="p-0.5 border dark:border-darkmode-400 w-full flex rounded-[0.6rem] border-slate-200 bg-white shadow-sm">
                            <li id="example-1-tab" data-tw-merge="" role="presentation"
                                class="focus-visible:outline-none flex-1 bg-slate-50 rtl:first:rounded-r-[0.6rem] ltr:first:rounded-l-[0.6rem] rtl:last:rounded-l-[0.6rem] ltr:last:rounded-r-[0.6rem] [&[aria-selected='true']_button]:text-current">
                                <button data-tw-merge="" data-tw-target="#example-1" role="tab"
                                        class="cursor-pointer appearance-none px-3 border border-transparent transition-colors dark:text-slate-400 [&.active]:text-slate-700 py-1.5 dark:border-transparent [&.active]:border [&.active]:shadow-sm [&.active]:font-medium [&.active]:border-slate-200 [&.active]:bg-white [&.active]:dark:text-slate-300 [&.active]:dark:bg-darkmode-400 [&.active]:dark:border-darkmode-400 active flex w-full items-center justify-center whitespace-nowrap rounded-[0.6rem] text-slate-500">
                                    <span class="rtl:ml-2 ltr:mr-2 h-2 w-2 rounded-full border border-primary/60 bg-primary/60"></span>
                                    در انتظار (127)
                                </button>
                            </li>
                            <li id="example-2-tab" data-tw-merge="" role="presentation"
                                class="focus-visible:outline-none flex-1 bg-slate-50 rtl:first:rounded-r-[0.6rem] ltr:first:rounded-l-[0.6rem] rtl:last:rounded-l-[0.6rem] ltr:last:rounded-r-[0.6rem] [&[aria-selected='true']_button]:text-current">
                                <button data-tw-merge="" data-tw-target="#example-2" role="tab"
                                        class="cursor-pointer appearance-none px-3 border border-transparent transition-colors dark:text-slate-400 [&.active]:text-slate-700 py-1.5 dark:border-transparent [&.active]:border [&.active]:shadow-sm [&.active]:font-medium [&.active]:border-slate-200 [&.active]:bg-white [&.active]:dark:text-slate-300 [&.active]:dark:bg-darkmode-400 [&.active]:dark:border-darkmode-400 flex w-full items-center justify-center whitespace-nowrap rounded-[0.6rem] text-slate-500">
                                    <span class="rtl:ml-2 ltr:mr-2 h-2 w-2 rounded-full border border-danger/60 bg-danger/60"></span>
                                    لغو شده (۱۳۲)
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3">
                            <div data-transition="" data-selector=".active" data-enter="transition-[visibility,opacity] ease-linear duration-150" data-enter-from="!p-0 !h-0 overflow-hidden invisible opacity-0" data-enter-to="visible opacity-100"
                                 data-leave="transition-[visibility,opacity] ease-linear duration-150" data-leave-from="visible opacity-100" data-leave-to="!p-0 !h-0 overflow-hidden invisible opacity-0" id="example-1" role="tabpanel" aria-labelledby="example-1-tab" class="tab-pane active">
                                <div class="rounded-[0.6rem] border border-dashed border-slate-300/80">
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                برد پیت
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                خانه و باغ
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                شنبه تیر ۱۴۰۲
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.312 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                لئوناردو دی‌کاپریو
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                کتاب‌ها
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                یکشنبه بهمن ۱۳۹۸
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.772 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                مریل استریپ
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                الکترونیک
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                چهارشنبه، آبان ۱۴۰۱
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.253 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                تام هنکس
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                الکترونیک
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                Thu سپتامبر 2022
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.274 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                کیت بلانچت
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                جواهرات
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                یکشنبه، مهر ۱۴۰۲
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.371 ریال ایران
                                        </div>
                                    </div>
                                </div>
                                <button data-tw-merge=""
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed dark:border-primary mt-3 w-full border-primary/[0.15] bg-white text-primary hover:bg-primary/20">
                                    مشاهده تمام صورتحساب‌ها
                                    <i data-tw-merge="" data-lucide="arrow-right" class="rtl:mr-2 ltr:ml-2 h-4 w-4 stroke-[1.3] rtl:rotate-180"></i></button>
                            </div>
                            <div data-transition="" data-selector=".active" data-enter="transition-[visibility,opacity] ease-linear duration-150" data-enter-from="!p-0 !h-0 overflow-hidden invisible opacity-0" data-enter-to="visible opacity-100"
                                 data-leave="transition-[visibility,opacity] ease-linear duration-150" data-leave-from="visible opacity-100" data-leave-to="!p-0 !h-0 overflow-hidden invisible opacity-0" id="example-2" role="tabpanel" aria-labelledby="example-2-tab" class="tab-pane">
                                <div class="rounded-[0.6rem] border border-dashed border-slate-300/80">
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                برد پیت
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                خانه و باغ
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                شنبه تیر ۱۴۰۲
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.312 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                لئوناردو دی‌کاپریو
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                کتاب‌ها
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                یکشنبه بهمن ۱۳۹۸
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.772 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                مریل استریپ
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                الکترونیک
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                چهارشنبه، آبان ۱۴۰۱
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.253 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                تام هنکس
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                الکترونیک
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                Thu سپتامبر 2022
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.274 ریال ایران
                                        </div>
                                    </div>
                                    <div class="flex cursor-pointer items-center border-b border-dashed border-slate-300/80 px-5 py-4 last:border-0 last:border-b-0 hover:bg-slate-50">
                                        <div>
                                            <div class="max-w-[12rem] truncate font-medium text-primary">
                                                کیت بلانچت
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-slate-500">
                                                جواهرات
                                            </div>
                                            <div class="mt-1.5 text-xs text-slate-500">
                                                یکشنبه، مهر ۱۴۰۲
                                            </div>
                                        </div>
                                        <div class="rtl:mr-auto ltr:ml-auto whitespace-nowrap font-medium">
                                            1.371 ریال ایران
                                        </div>
                                    </div>
                                </div>
                                <button data-tw-merge=""
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed dark:border-primary mt-3 w-full border-primary/[0.15] bg-white text-primary hover:bg-primary/20">
                                    مشاهده تمام صورتحساب‌ها
                                    <i data-tw-merge="" data-lucide="arrow-right" class="rtl:mr-2 ltr:ml-2 h-4 w-4 stroke-[1.3] rtl:rotate-180"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
