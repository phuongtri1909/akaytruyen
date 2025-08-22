<footer id="donate">
    <div class="footer-custom bg-gradient">
        <div class="container ">
            <div class="row py-5 text-white g-3">
                <div class="col-12 col-md-6">
                    <h5 class="mb-4">AKAY TRUYỆN</h5>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <a target="_blank" href="https://youtube.com/@AkayTruyen?sub_confirmation=1"
                                        class="text-decoration-none text-dark text-center">
                                        <h6 class="info-title">YOUTUBE</h6>
                                        <img src="{{ asset('assets/frontend/images/ytb_logo.png') }}" alt="logo_ytb"
                                            class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <a target="_blank" href="https://www.facebook.com/groups/1134210028188278/"
                                        class="text-decoration-none text-dark text-center">
                                        <h6 class="info-title">GROUP FACEBOOK</h6>
                                        <img src="{{ asset('assets/frontend/images/Facebook_Logo.png') }}"
                                            alt="logo_fb" class="img-fluid" style="height: 70px; object-fit: contain;">
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-12 col-md-6">
                    <div class="footer-section">
                        <h5 class="text-white mb-3">ỦNG HỘ TÁC GIẢ AkayHau</h5>
                        <div class="donation-info">
                            <ul class="list-unstyled ">
                                <li class="mb-2 d-flex flex-column align-items-start">
                                    <div class="mb-3">
                                        <i class="fas fa-university me-2"></i>
                                        <span class="fw-bold">Techcombank:</span>
                                    </div>
                                    <img src="{{ asset('assets/frontend/images/techcombank.jpg') }}"
                                        alt="banking-techcombank" class="" height="250">
                                </li>

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
                                /* Đảm bảo không xuống dòng */
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

                            /* Responsive: Điều chỉnh tốc độ và kích thước chữ trên điện thoại */
                            @media (max-width: 768px) {
                                .marquee p {
                                    font-size: 14px;
                                    animation-duration: 10s;
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>
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
