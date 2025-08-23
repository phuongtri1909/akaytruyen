<footer id="donate">
    <div class="footer-custom bg-gradient">
        <div class="container ">
            <div class="row py-3 text-white g-3">
                <div class="col-12 col-md-5">
                    <h5 class="mb-4">AKAY TRUYỆN</h5>
                    <div class="social-links-container row">
                        <div class="social-link-item col-6">
                            <a target="_blank" href="https://youtube.com/@AkayTruyen?sub_confirmation=1"
                               class="social-link youtube-link">
                                <div class="social-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                        <path d="M22.54 6.42C22.4212 5.94541 22.1793 5.51057 21.8387 5.15941C21.4981 4.80824 21.0708 4.55318 20.6 4.42C18.88 4 12 4 12 4S5.12 4 3.4 4.46C2.92921 4.59318 2.50191 4.84824 2.16131 5.19941C1.82071 5.55057 1.57879 5.98541 1.46 6.46C1.14521 8.20556 0.991197 9.97631 1 11.75C0.988726 13.537 1.13977 15.3213 1.46 17.08C1.59095 17.5398 1.8379 17.9581 2.17774 18.2945C2.51758 18.6308 2.93842 18.8738 3.4 19C5.12 19.46 12 19.46 12 19.46S18.88 19.46 20.6 19C21.0708 18.8668 21.4981 18.6118 21.8387 18.2606C22.1793 17.9094 22.4212 17.4746 22.54 17C22.8524 15.2676 23.0062 13.5103 23 11.75C23.0113 9.96295 22.8602 8.1787 22.54 6.42Z" fill="currentColor"/>
                                        <path d="M9.75 15.02L15.5 11.75L9.75 8.48V15.02Z" fill="white"/>
                                    </svg>
                                </div>
                                <span class="social-text">YouTube</span>
                            </a>
                        </div>

                        <div class="social-link-item col-6">
                            <a target="_blank" href="https://www.facebook.com/groups/1134210028188278/"
                               class="social-link facebook-link">
                                <div class="social-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                        <path d="M24 12.073C24 5.403 18.627 0 12 0S0 5.403 0 12.073C0 18.06 4.388 23.02 10.125 23.854V15.442H7.078V12.073h3.047V9.415c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.369h-2.796v8.412C19.612 23.02 24 18.06 24 12.073Z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <span class="social-text">Facebook</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7">

                    <div class="footer-section">
                        <h5 class="text-white mb-3">ỦNG HỘ TÁC GIẢ AkayHau</h5>
                        <div class="donation-info d-flex flex-column flex-md-row align-items-start align-items-md-end">
                            <ul class="list-unstyled me-3">
                                <li class="mb-2 d-flex flex-column align-items-start">
                                    <div class="mb-3">
                                        <i class="fas fa-university me-2"></i>
                                        <span class="fw-bold">Techcombank:</span>
                                    </div>
                                    <img src="{{ asset('assets/frontend/images/techcombank.jpg') }}"
                                        alt="banking-techcombank" class="" height="250">
                                </li>
                            </ul>

                            <ul class="list-unstyled ">
                                <li class="mb-2">
                                    <i class="fas fa-university me-2"></i>
                                    <span class="fw-bold">Agribank:</span>
                                    1809205083252 (Cờ Đỏ Cần Thơ II) - NGUYEN PHUOC HAU
                                </li>

                                <li class="mb-2">
                                    <i class="fas fa-wallet me-2"></i>
                                    <span class="fw-bold">Momo/ViettelPay:</span>
                                    0942973261
                                </li>

                                <li class="mb-2">
                                    <i class="fab fa-paypal me-2"></i>
                                    <span class="fw-bold">Paypal:</span>
                                    nguyenphuochau12t2@gmail.com
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
            <div class="">
                <div class="container py-1">
                    <div class="marquee">
                        <p>Akaytruyen.com là web đọc truyện chính chủ duy nhất của tác giả AkayHau.
                            Tham gia kênh Youtube và Facebook Page chính của truyện để ủng hộ tác giả.</p>
                    </div>
                </div>
            </div>
            <style>
                .footer-custom {
                    background-color: #14425d;
                    box-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
                }

                .marquee {
                    white-space: nowrap;
                    overflow: hidden;
                    position: relative;
                    width: 100%;
                    color: white;
                    font-size: 16px;
                }

                .marquee p {
                    display: inline-block;
                    padding-left: 100%;
                    animation: marquee-scroll 15s linear infinite;
                }

                @keyframes marquee-scroll {
                    from {
                        transform: translateX(0);
                    }

                    to {
                        transform: translateX(-100%);
                    }
                }

                @media (max-width: 768px) {
                    .marquee p {
                        font-size: 14px;
                        animation-duration: 10s;
                    }
                }
            </style>
        </div>
    </div>
    <div class="bg-dark">
        <div class="container py-3">
            <span class="copyright text-white">
                Copyright © {{ date('Y') }} {{ request()->getHost() }} Version 3.5.2
                <a href="//www.dmca.com/Protection/Status.aspx?ID=3da5a11e-fc82-4343-b6a0-8515713d3c33"
                    title="DMCA.com Protection Status" class="dmca-badge"> <img
                        src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=3da5a11e-fc82-4343-b6a0-8515713d3c33"
                        alt="DMCA.com Protection Status" /></a>
                <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"></script>
            </span>
        </div>

    </div>
</footer>
