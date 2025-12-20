<!-- Footer -->
<footer class="footer mt-auto">
    <!-- Container -->
    <div class="container-fluid px-3 lg:px-5">
        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5">
            
            <!-- Copyright -->
            <div class="flex order-2 md:order-1 gap-2 font-normal text-sm">
                <span class="text-gray-500 dark:text-gray-400">
                    {{ date('Y') }}©
                </span>
                <a 
                    class="text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary" 
                    href="{{ config('app.url') }}"
                >
                    {{ config('app.name') }}
                </a>
            </div>
            
            <!-- Footer Navigation -->
            <nav class="flex order-1 md:order-2 gap-4 font-normal text-sm text-gray-600 dark:text-gray-300">
                <a class="hover:text-primary" href="#">
                    Docs
                </a>
                <a class="hover:text-primary" href="#">
                    Purchase
                </a>
                <a class="hover:text-primary" href="#">
                    FAQ
                </a>
                <a class="hover:text-primary" href="#">
                    Support
                </a>
                <a class="hover:text-primary" href="#">
                    License
                </a>
            </nav>
            
        </div>
    </div>
    <!-- End of Container -->
</footer>
<!-- End of Footer -->

