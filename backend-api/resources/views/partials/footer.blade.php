<footer class="pt-5" data-aos="fade-up" data-aos-delay="100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="footer-title">FNFTour</h5>
                    <p class="small text-light" style="line-height: 1.8; opacity: 0.8;">
                        Your ultimate travel companion for exploring the world's most breathtaking destinations.
                        We specialize in curated holiday packages, seamless visa processing, and luxury stays
                        to ensure your journey is nothing short of extraordinary.
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#">f</a> <a href="#">in</a>
                        <a href="#">ig</a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('index') }}" class="footer-link">Home</a></li>
                        <li><a href="#" class="footer-link">Packages</a></li>
                        <li><a href="#" class="footer-link">My Booking</a></li>
                        <li><a href="#" class="footer-link">Our Services</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Help & Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Contact Us</a></li>
                        <li><a href="#" class="footer-link">Private Policy</a></li>
                        <li><a href="#" class="footer-link">Term & Condition</a></li>
                        <li><a href="#" class="footer-link">FAQ</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Contact Us</h5>
                    <p class="small mb-2 text-white">📍Malibagh, Dhaka, Bangladesh</p>
                    <p class="small mb-2 text-white">📞 +880 1234 567 890</p>
                    <p class="small text-white">✉️ support@fnftour.com</p>
                </div>
            </div>
        </div>

        <div class="copyright-section">
            <div class="container text-center">
                <p class="small mb-0 opacity-75">&copy; {{ date('Y') }} FNFTour. All rights reserved with <a
                        href="http://sohelit.com" target="_blank" rel="noopener noreferrer">Sohel IT</a></p>
            </div>
        </div>
    </footer>

<style>
    /* Footer Hover Effect */
    .hover-link {
        transition: all 0.3s ease;
    }
    .hover-link:hover {
        color: #0d6efd !important;
        padding-left: 5px;
    }
    footer i {
        font-size: 1.1rem;
    }
</style>
