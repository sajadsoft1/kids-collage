document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    // FIXED HEADER =====
    window.addEventListener("scroll", () => {
        const toFixHeaders = document.querySelectorAll(".to-be-fixed");
        toFixHeaders.forEach(toFixHeader => {
            if (window.scrollY > 100) {
                // document.body.style.paddingTop = toFixHeader.clientHeight + 'px';
                toFixHeader.classList.add("ed-sticky");
            } else {
                // document.body.style.paddingTop = 0;
                toFixHeader.classList.remove("ed-sticky");
            }
        })
    });
    //===== FIXED HEADER

    // SIDEBAR JS START
    console.log('sidebar js start');

    const edSidebar = document.querySelector(".ed-sidebar");
    const edSidebarOpenBtn = document.querySelector(".ed-header-sidebar-open-btn");
    const edSidebarCloseBtn = document.querySelector(".ed-sidebar-close-btn");
    const edOverlay = document.querySelector(".ed-overlay");

    if (edSidebarOpenBtn) {
        edSidebarOpenBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            edSidebar.classList.add("active");
            edOverlay.classList.add("active");
        });
    }
    edSidebarCloseBtn.addEventListener("click", () => {
        edSidebar.classList.remove("active");
        edOverlay.classList.remove("active");
    });

    document.addEventListener("click", (e) => {
        if (!edSidebar.contains(e.target) && edSidebar.classList.contains("active")) {
            edSidebar.classList.remove("active");
            edOverlay.classList.remove("active");
        }
    });
    // SIDEBAR JS END


    // MOBILE MENU SIDEBAR
    const edMobileMenuOpenBtn = document.querySelector(".ed-mobile-menu-open-btn");
    const edMobileMenuContents = document.querySelectorAll(".to-go-to-sidebar-in-mobile");
    const edMobileMenuContainer = document.querySelector(".ed-header-nav-in-mobile");
    const edHeaderNavContainer = document.querySelector(".ed-header-nav-container");
    const edHeaderNav = document.querySelectorAll(".ed-header-nav li");
    if (window.innerWidth < 1200) {
        edHeaderNav.forEach(edHeaderNavItem => {
            edHeaderNavItem.addEventListener("click", () => {
                edHeaderNavItem.classList.toggle("active");
            });
        });
    };

    if (window.innerWidth < 1200) {
        edMobileMenuContents.forEach(edMobileMenuContent => {
            edMobileMenuContainer.appendChild(edMobileMenuContent);
        });
    };

    window.addEventListener("resize", () => {
        edMobileMenuContents.forEach(edMobileMenuContent => {
            if (window.innerWidth < 1200) {
                edMobileMenuContainer.appendChild(edMobileMenuContent);
            }
        });
    });

    edMobileMenuOpenBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        edSidebar.classList.add("active");
        edOverlay.classList.add("active");
    });
    // MOBILE MENU SIDEBAR


    // BANNER SLIDER JS START
    new Swiper(".ed-banner-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        navigation: {
            prevEl: ".ed-banner-slider .prev",
            nextEl: ".ed-banner-slider .next",
        }
    });
    // BANNER SLIDER JS END


    // GALLERY SLIDER JS START
    new Swiper(".ed-gallery-slider", {
        slidesPerView: "auto",
        centeredSlides: true,
        loop: true,
        spaceBetween: 30,
        autoplay: true,
    });
    // GALLERY SLIDER JS END


    // TESTIMONIAL SLIDER JS START
    new Swiper(".ed-testimonial-slider", {
        autoplay: true,
        pagination: {
            el: ".ed-testimonial-slider-pagination",
            type: "fraction",
        },
        navigation: {
            prevEl: ".ed-testimonial-slider-controls .prev",
            nextEl: ".ed-testimonial-slider-controls .next",
        }
    });
    // TESTIMONIAL SLIDER JS END

    // PARTNER SLIDER JS START
    new Swiper(".ed-partners-slider", {
        slidesPerView: 2,
        spaceBetween: 130,
        autoplay: true,
        breakpoints: {
            0: { slidesPerView: 2, spaceBetween: 40, },
            480: { slidesPerView: 3, spaceBetween: 90, },
            576: { slidesPerView: 3, },
            768: { slidesPerView: 4, },
            1200: { slidesPerView: 5, },
            1400: { slidesPerView: 6, }
        }
    });
    // PARTNER SLIDER JS END


    // INDEX-2 COURSE FILTER JS
    const ed2CoursesContainer = document.querySelector(".ed-2-courses-container");
    if (ed2CoursesContainer) {
        mixitup(ed2CoursesContainer);
    };

    // INDEX-2 EVENT SLIDER
    new Swiper(".ed-2-events-slider", {
        autoplay: true,
        slidesPerView: 2,
        spaceBetween: 30,
        loop: true,
        navigation: {
            prevEl: ".ed-2-events-slider-nav .prev",
            nextEl: ".ed-2-events-slider-nav .next",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            }
        }
    });

    // INDEX-2 TESTIMONIAL SLIDER
    new Swiper(".ed-2-testimonial-slider", {
        slidesPerView: "auto",
        autoplay: true,
        spaceBetween: 30,
        loop: true,
        navigation: {
            prevEl: ".ed-2-testimonial-slider-nav .prev",
            nextEl: ".ed-2-testimonial-slider-nav .next",
        }
    });

    const ed2SearchCategory = document.querySelector('#ed-2-search-category');
    if (ed2SearchCategory) {
        new SlimSelect({
            select: ed2SearchCategory,
            settings: {
                showSearch: false,
            }
        })
    };


    // ABOUT PAGE PROGRAM SLIDER
    new Swiper(".ed-program-slider", {
        slidesPerView: 1,
        spaceBetween: 15,
        autoplay: true,
        loop: true,
        navigation: {
            prevEl: ".ed-program-slider-nav .prev",
            nextEl: ".ed-program-slider-nav .next",
        },
        breakpoints: {
            480: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        }
    });

    // TEACHER DETAILS PAGE TEACHERS SLIDER
    new Swiper(".ed-teachers-slider", {
        slidesPerView: 3,
        spaceBetween: 20,
        autoplay: true,
        loop: true,
        navigation: {
            prevEl: ".ed-teachers-slider-nav .prev",
            nextEl: ".ed-teachers-slider-nav .next",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                spaceBetween: 30,
            }
        }
    });


    // cart js
    const edCartOpenerBtn = document.querySelector(".ed-cart-opener-btn");
    const edCart = document.querySelector(".ed-cart-bar");

    if (edCartOpenerBtn) {
        edCartOpenerBtn.addEventListener("click", () => {
            edCart.classList.add("active");
            edOverlay.classList.add("active");
            document.body.style.overflow = "hidden";
        });
    };

    document.addEventListener("click", (e) => {
        if (!edCart.contains(e.target) && edCart.classList.contains("active") && !edCartOpenerBtn.contains(e.target)) {
            edCart.classList.remove("active");
            edOverlay.classList.remove("active");
            document.body.style.overflow = "";
        }
    });

    // search js
    const edSearchOpenerBtn = document.querySelector(".ed-search-opener-btn");
    const edSearch = document.querySelector(".ed-search");


    if (edSearchOpenerBtn) {
        edSearchOpenerBtn.addEventListener("click", () => {
            edSearch.classList.add("active");
            edOverlay.classList.add("active");
        });
    };

    document.addEventListener("click", (e) => {
        if (!edSearch.contains(e.target) && edSearch.classList.contains("active") && !edSearchOpenerBtn.contains(e.target)) {
            edSearch.classList.remove("active");
            edOverlay.classList.remove("active");
        }
    });
});
