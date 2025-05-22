<div>
    @auth
    <div class="border p-3 mb-2 rounded bg-light">
        <div class="d-flex align-items-center mb-3">
        @php
            $avatar = asset(auth()->user()->avatar ?? 'avatar_default.jpg');
            $role = auth()->user()->getRoleNames()->first();
            $email = auth()->user()->email;

            // Map mặc định theo role
            $borderMap = [
                'Admin' => 'admin-vip-8.png',
                'Mod' => 'vien_mod.png',
                'Content' => 'avt_content.png',
                'vip' => 'avt_admin.png',
                'VIP PRO' => 'avt_pro_vip.png',
                'VIP PRO MAX' => 'avt_vip_pro_max.gif',
                'VIP SIÊU VIỆT' => 'khung-sieu-viet.png',
            ];
            $border = null;
            $borderStyle = '';
            // Kiểm tra riêng trường hợp Thánh Nữ
            if ($role === 'Admin' && $email === 'nang2025@gmail.com') {
                        $border = asset('images/roles/vien-thanh-nu.png');
                    }
                    elseif ($role === 'Admin' && $email === 'nguyenphuochau12t2@gmail.com') {
                        $border = asset('images/roles/akay.png');
                        $borderStyle = 'width: 200%; height: 200%; top: 31%;';
                    } else {
                        $border = isset($borderMap[$role]) ? asset('images/roles/' . $borderMap[$role]) : null;
                    }
        @endphp


        <div class="avatar-wrapper" style="position: relative; width: 40px; height: 40px; display: inline-block;">
            <!-- Avatar chính -->
            <img src="{{ $avatar }}" 
                class="avatar rounded-circle border border-3"
                alt="{{ auth()->user()->name }}"
                style="width: 100%; height: 100%; object-fit: cover;">

            <!-- Ảnh viền theo vai trò -->
            @if ($border)
                <img src="{{ $border }}" 
                    class="rounded-circle"
                    alt="Border {{ $role }}"
                    style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        {{ $borderStyle ?: 'width: 135%; height: 135%;' }}
                        transform: translate(-50%, -50%);
                        object-fit: cover;
                        pointer-events: none;
                        z-index: 2;
                    ">
            @endif
        </div>


            
            @if(!auth()->user()->ban_comment)
                <textarea wire:model="content" class="form-control ms-2" placeholder="Nội Dung" rows="2"></textarea>
                
                <button wire:click="postComment" class="btn btn-primary ms-2">Gửi</button>
            @else
                <p class="text-danger ms-2">Bạn đã bị cấm chat.</p>
            @endif
        </div>
    </div>

    @endauth
    <div class="comment-list">
        <div class="comment-list" wire>

            @foreach($comments as $comment)
            <div class="border p-3 mb-2 rounded bg-light">
                <div class="d-flex align-items-center">
                @php
                    $avatar = asset($comment->user->avatar ?? 'avatar_default.jpg');
                    $role = $comment->user->getRoleNames()->first();
                    $email = $comment->user->email;

                    $borderMap = [
                        'Admin' => 'admin-vip-8.png',
                        'Mod' => 'vien_mod.png',
                        'Content' => 'avt_content.png',
                        'vip' => 'avt_admin.png',
                        'VIP PRO' => 'avt_pro_vip.png',
                        'VIP PRO MAX' => 'avt_vip_pro_max.gif',
                        'VIP SIÊU VIỆT' => 'khung-sieu-viet.png',
                        
                    ];

                    $border = null;
                    $borderStyle = '';

                    if ($role === 'Admin' && $email === 'nang2025@gmail.com') {
                        $border = asset('images/roles/vien-thanh-nu.png');
                    }
                    elseif ($role === 'Admin' && $email === 'nguyenphuochau12t2@gmail.com') {
                        $border = asset('images/roles/akay.png');
                        $borderStyle = 'width: 200%; height: 200%; top: 31%;';
                    } else {
                        $border = isset($borderMap[$role]) ? asset('images/roles/' . $borderMap[$role]) : null;
                    }
                @endphp


                <div class="avatar-wrapper" style="position: relative; width: 40px; height: 40px; display: inline-block;">
                    <!-- Avatar chính -->
                    <img src="{{ $avatar }}" 
                        class="avatar rounded-circle border border-2"
                        alt="{{ $comment->user->name }}"
                        style="width: 100%; height: 100%; object-fit: cover;">

                    <!-- Ảnh viền to hơn 1 chút -->
                    @if ($border)
                        <img src="{{ $border }}" 
                            class="rounded-circle"
                            alt="Border {{ $role }}"
                            style="
                                position: absolute;
                                top: 50%;
                                left: 53%;
                                transform: translate(-50%, -50%);
                                object-fit: cover;
                                pointer-events: none;
                                z-index: 2;
                                {{ $borderStyle ?: 'width: 151%; height: 151%;' }}
                            ">
                    @endif
                </div>



                    <div class="ms-2">
                    @php
                        // Tạo biến để dùng lại
                        $role = $comment->user->getRoleNames()->first();
                        $bgMap = [
                            'Admin' => 'admin.gif',
                            'Mod' => 'vip.gif',
                            'Content' => 'vip.gif',
                            'vip' => 'mod.gif',
                            'VIP PRO' => 'vip_pro.gif',
                            'VIP PRO MAX' => 'vip_pro_max.webp',
                            'VIP SIÊU VIỆT' => 'vip-sieu-viet.gif',
                        ];
                        $bgImage = isset($bgMap[$role]) ? asset('images/roles/' . $bgMap[$role]) : null;
                        $colorMap = [
                            'Admin' => 'red',
                            'Mod' => 'green',
                            'Content' => 'yellow',
                            'vip' => 'blue',
                            'VIP PRO' => 'purple',
                            'VIP PRO MAX' => '#f37200',
                            'VIP SIÊU VIỆT' => '', // sẽ thay bằng màu gradient động
                        ];
                        $textColor = $colorMap[$role] ?? 'black';
                    @endphp

                    @if ($role === 'VIP SIÊU VIỆT')
                    <div class="vip-sieu-viet-badge">
                        <span>{{ $comment->user->name }}</span>
                    </div>
                    @elseif ($bgImage)
                        <div style="
                            display: inline-block;
                            padding: 4px 8px;
                            color: {{ $textColor }};
                            background-image: url('{{ $bgImage }}');
                            background-size: cover;
                            background-repeat: no-repeat;
                            border-radius: 4px;
                            font-weight: bold;
                        ">
                            {{ $comment->user->name }}
                        </div>
                    @else
                        <strong>{{ $comment->user->name }}</strong>
                    @endif



                                <!-- bat dau -->
                    
                                
                    @if($comment->user->email === 'khaicybers@gmail.com')
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/64012-management.png" width="24px" height="24px" style="margin-left:5px;" alt="Dev">
                            <span class="tooltip-text">Hỗ Trợ</span>
                        </span>
                    @elseif($comment->user->email === 'nguyenphuochau12t2@gmail.com')
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/65928-owner.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="tac gia">
                            <span class="tooltip-text">Tác Giả</span>
                        </span>
                        <div class="custom-badge-tooltip">
                            <img src="{{ asset('images/roles/kaybage.png') }}" alt="user" style="width: 60px; height: 60px; margin-left: 5px;">
                                <div class="custom-badge-tooltiptext">
                                    <img src="{{ asset('images/roles/kaybage.png') }}" alt="user" style="width: 70px; height: 70px; display: block; margin: 0 auto;">
                                    <div class="custom-badge-name">Tác Giả</div>
                                </div>
                        </div>
                    @elseif($comment->user->hasRole('Admin'))
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/39760-owner.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="Admin">
                            <span class="tooltip-text">Quản Trị Viên</span>
                        </span>

                        @php
                            $badgeEmail = $comment->user->email;
                            $badgeName = 'Tông Chủ';
                            $badgeImage = asset('images/roles/tongchu.gif');

                            if ($badgeEmail === 'nguyenphuochau12t2@gmail.com') {
                                $badgeName = 'Tác Giả';
                                $badgeImage = asset('images/roles/akay.png');
                            } elseif ($badgeEmail === 'nang2025@gmail.com') {
                                $badgeName = 'Thánh Nữ';
                                $badgeImage = asset('images/roles/thanhnu.gif'); // ảnh khác
                            }
                        @endphp

                        <div class="custom-badge-tooltip">
                            <img src="{{ $badgeImage }}" alt="user" style="width: 50px; height: 50px; margin-left: 5px;">
                            <div class="custom-badge-tooltiptext">
                                <img src="{{ $badgeImage }}" alt="user" style="width: 60px; height: 60px; display: block; margin: 0 auto;">
                                <div class="custom-badge-name">{{ $badgeName }}</div>
                            </div>
                        </div>
                    @endif

                                @if($comment->user->hasRole('Mod'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/80156-developer.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="Mod">
                                    <span class="tooltip-text">Mod kiểm duyệt</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/chapphap.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: 5px;">
                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/chapphap.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Chấp Pháp</div>
                                    </div>
                                </div>
                                @endif
                                {{-- Badge cho VIP các loại --}}
                                @if ($comment->user->hasRole('vip'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/45918-msp-super-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip1">
                                    <span class="tooltip-text">VIP Bậc I</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/tinhanh.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: 5px;">
                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/tinhanh.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Tinh Anh Bậc I</div>
                                    </div>
                                </div>
                                @endif

                                @if ($comment->user->hasRole('VIP PRO'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/44014-msp-elite-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip2">
                                    <span class="tooltip-text">VIP Bậc II</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/hophap.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: 5px;">
                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/hophap.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Hộ Pháp Bậc II</div>
                                    </div>
                                </div>                    
                                @endif

                                @if ($comment->user->hasRole('VIP PRO MAX'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/55280-msp-star-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip3">
                                    <span class="tooltip-text">VIP Bậc III</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <div class="crossed-swords">
                                        <img src="{{ asset('images/roles/huyet-kiem-vip.gif') }}" alt="kiem xanh" class="sword-left">
                                        <img src="{{ asset('images/roles/kiemdo.gif') }}" alt="kiem do" class="sword-right">
                                    </div>

                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/huyet-kiem-vip.gif') }}" alt="kiem xanh" style="width: 50px; height: 50px; display: block; margin: 0 auto;transform: rotate(-85deg);">
                                        <img src="{{ asset('images/roles/kiemdo.gif') }}" alt="kiem do" style="width: 50px; height: 60px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Trưởng Lão Bậc III</div>
                                    </div>
                                </div>
                  
                                @endif
                                @if ($comment->user->hasRole('User'))
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/detu.gif') }}" alt="user" style="width: 30px;height: 30px;margin-left: -4px;margin-top: -22px;">
                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/detu.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Đệ Tử</div>
                                    </div>
                                </div>
                                @endif
                                @if ($comment->user->hasRole('VIP SIÊU VIỆT'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/2336-vipgif.gif" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vipmax">
                                    <span class="tooltip-text">VIP MAX</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/bage_vipsieuviet.png') }}" alt="user" style="width: 70px;height: 50px;margin-left: -4px;margin-top: -22px;">
                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/bage_vipsieuviet.png') }}" alt="user" style="width: 70px; height: 60px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Thái Thượng</div>
                                    </div>
                                </div>
                                @endif

                                <!-- end -->
                                @auth
                                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod'))
                                    <button wire:click="pinComment({{ $comment->id }})" class="btn btn-sm me-2">
                                        @if($comment->pinned)
                                            <i class="fas fa-thumbtack text-warning" title="Bỏ ghim"></i>
                                        @else
                                            <i class="fas fa-thumbtack" title="Ghim"></i>
                                        @endif
                                    </button>
                                @endif

                                @endauth
                                <style>
                                    .custom-badge-tooltip {
                                        position: relative;
                                        display: inline-block;
                                        cursor: pointer;
                                        z-index: 100;
                                    }

                                    .custom-badge-tooltip .custom-badge-tooltiptext {
                                        visibility: hidden;
                                        width: max-content;
                                        background-color: #333;
                                        color: #fff;
                                        text-align: center;
                                        padding: 8px;
                                        border-radius: 6px;
                                        position: absolute;
                                        top: 125%;  /* Tooltip sẽ xuất hiện phía dưới */
                                        left: 50%;  /* Căn giữa tooltip */
                                        transform: translateX(-50%);
                                        opacity: 0;
                                        transition: opacity 0.3s;
                                        white-space: nowrap;
                                        z-index: 102;
                                    }

                                    .custom-badge-tooltip:hover .custom-badge-tooltiptext {
                                        visibility: visible;
                                        opacity: 2;
                                    }
                                    .vip-sieu-viet-badge {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 8px;
                                        padding: 6px 20px;
                                        font-weight: bold;
                                        border-radius: 10px;
                                        color: #fff;
                                        background-image: url('{{ asset('images/roles/vip-sieu-viet.gif') }}');
                                        background-size: cover;
                                        background-repeat: no-repeat;
                                        position: relative;
                                        clip-path: polygon(10% 0%, 90% 0%, 100% 50%, 90% 100%, 10% 100%, 0% 50%);
                                        z-index: 1;
                                        height: 32px;
                                        margin-left: 4px;
                                        animation: swordFloat 2s ease-in-out infinite;
                                    }
                                    .vip-sieu-viet-badge::after {
                                        content: "";
                                        position: absolute;
                                        bottom: -6px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 100px;
                                        height: 6px;
                                        background: linear-gradient(to right, #fff, #f00, #fff);
                                        border-radius: 3px;
                                        box-shadow: 0 0 10px red;
                                    }


                                    /* Gradient màu trong chữ */
                                    .vip-sieu-viet-badge span {
                                        background: linear-gradient(to right, #ff8a00, #ff2070);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;
                                        font-weight: bold;
                                        position: relative;
                                        z-index: 1;
                                    }

                                    .vip-sieu-viet-content {
                                        font-size: 17px;
                                        font-weight: bold;
                                        background: linear-gradient(to right, #005f99, #87cefa, #00cc66);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;
                                        text-shadow: 1px 1px 2px rgba(0, 95, 153, 0.4); /* Nhẹ nhàng */
                                    }
                                    .vip-sieu-viet-content {
                                        position: relative;
                                        padding: 10px;
                                        border-radius: 10px;
                                        overflow: hidden;
                                    }

                                    .vip-image {
                                        width: 50px;  /* Điều chỉnh kích thước ảnh */
                                        height: 50px;
                                        margin-left: 5px;  /* Khoảng cách giữa ảnh và nội dung */
                                        vertical-align: middle;  /* Căn chỉnh ảnh với văn bản */
                                    }

                                    .swal2-container {
                                        z-index: 99 !important;  /* Đảm bảo thông báo luôn ở trên cùng */
                                    }
                                    .tooltip-icon {
                                        position: relative;
                                        display: inline-block;
                                        cursor: pointer;
                                    }

                                    .tooltip-icon .tooltip-text {
                                        visibility: hidden;
                                        background-color: #333;
                                        color: #fff;
                                        font-size: 12px;
                                        text-align: center;
                                        padding: 6px 10px;
                                        border-radius: 4px;
                                        position: absolute;
                                        z-index: 10;
                                        bottom: 125%; /* hiển thị phía trên */
                                        left: 50%;
                                        transform: translateX(-50%);
                                        white-space: nowrap;
                                        opacity: 0;
                                        transition: opacity 0.3s;
                                    }

                                    .tooltip-icon:hover .tooltip-text {
                                        visibility: visible;
                                        opacity: 1;
                                    }

                                    .fb-reply-border {
                                        border-left: 2px solid #e4e6eb; /* Màu viền giống Facebook */
                                        padding-left: 1rem;
                                        margin-top: 1rem;
                                        margin-left: 4px;
                                        border-radius: 0 0 0 8px;
                                        background-color: #f9f9fb; /* nền nhạt nhẹ */
                                        transition: background-color 0.3s ease;
                                    }

                                    .badge-tacgia {
                                        width: 40px !important;
                                        height: 40px !important;
                                    }

                                    .custom-badge-tooltiptext .badge-tacgia {
                                        width: 50px !important;
                                        height: 50px !important;
                                    }
                                    .break-words {
                                        display: block; /* Chuyển từ inline-block thành block */
                                        word-wrap: break-word; /* Đảm bảo từ dài không tràn */
                                        overflow-wrap: break-word; /* Đảm bảo từ dài không tràn */
                                        margin: 0; /* Xóa margin nếu có */
                                        padding: 0; /* Xóa padding nếu có */
                                    }
                                    .crossed-swords {
    position: relative;
    width: 60px;
    height: 60px;
    margin-left: 5px;
}

.crossed-swords img {
    position: absolute;
    width: 60px;
    height: 60px;
    top: 17px;
    left: 0;
    object-fit: contain;
    transform-origin: center center;
}

.sword-left {
    transform: rotate(-85deg);
    z-index: 2;
}

.sword-right {
    transform: rotate(85deg);
    z-index: 1;
}


                                </style>

            <div class="d-flex">                    
                        </div>
            


 
                            

                    </div>
                </div>
                <span style="margin-left: 50px;" class="text-muted">- {{ $comment->created_at->locale('vi')->diffForHumans() }}</span>
                @php
                    $isVipSieuViet = $comment->user->hasRole('VIP SIÊU VIỆT');
                @endphp

                <p class="break-words mt-2 {{ $isVipSieuViet ? 'vip-sieu-viet-content' : '' }}">
                    {!! $this->parseLinks($comment->content) !!}
                </p>


                <div class="d-flex">
                    @auth
                        <button wire:click="$set('parent_id', {{ $comment->id }})" class="btn btn-sm btn-outline-secondary me-2">Trả lời</button>

                        @if($comment->user_id == auth()->id() || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod'))
                            <button onclick="confirmDelete({{ $comment->id }})" class="btn btn-sm btn-outline-danger">Xóa</button>
                        @endif
                    @endauth
                </div>
                

                @if($parent_id == $comment->id)
                    @if(!auth()->user()->ban_comment)
                        <div class="mt-2">
                            <textarea wire:model="content" class="form-control" placeholder="Viết phản hồi..." rows="1"></textarea>
                            <button wire:click="postComment" class="btn btn-primary mt-2">Gửi</button>
                        </div>
                    @else
                        <p class="text-danger mt-2">Bạn đã bị cấm chat.</p>
                    @endif
                @endif


                @foreach($comment->replies->reverse() as $reply)
                    <div class="fb-reply-border">
                        <div class="d-flex align-items-center">
                        @php
                            $avatar = asset($reply->user->avatar ?? 'avatar_default.jpg');
                            $role = $reply->user->getRoleNames()->first();
                            $email = $reply->user->email;

                            $borderMap = [
                                'Admin' => 'admin-vip-8.png',
                                'Mod' => 'vien_mod.png',
                                'Content' => 'avt_content.png',
                                'vip' => 'avt_admin.png',
                                'VIP PRO' => 'avt_pro_vip.png',
                                'VIP PRO MAX' => 'avt_vip_pro_max.gif',
                                'VIP SIÊU VIỆT' => 'khung-sieu-viet.png',
                            ];

                            $border = null;
                            $borderStyle = '';

                            if ($role === 'Admin' && $email === 'nang2025@gmail.com') {
                                $border = asset('images/roles/vien-thanh-nu.png');
                            }
                            elseif ($role === 'Admin' && $email === 'nguyenphuochau12t2@gmail.com') {
                                $border = asset('images/roles/akay.png');
                                $borderStyle = 'width: 280%; height: 280%; top: 31%;';
                            } else {
                                $border = isset($borderMap[$role]) ? asset('images/roles/' . $borderMap[$role]) : null;
                            }
                        @endphp


                        <div class="avatar-wrapper" style="position: relative; width: 40px; height: 40px; display: inline-block;">
                            <!-- Avatar chính -->
                            <img src="{{ $avatar }}" 
                                class="avatar rounded-circle border border-2"
                                alt="{{ $comment->user->name }}"
                                style="width: 100%; height: 100%; object-fit: cover;">

                            <!-- Ảnh viền to hơn 1 chút -->
                            @if ($border)
                                <img src="{{ $border }}" 
                                    class="rounded-circle"
                                    alt="Border {{ $role }}"
                                    style="
                                        position: absolute;
                                        top: 50%;
                                        left: 53%;
                                        {{ $borderStyle ?: 'width: 151%; height: 151%;' }}
                                        transform: translate(-50%, -50%);
                                        object-fit: cover;
                                        pointer-events: none;
                                        z-index: 2;
                                    ">
                            @endif
                        </div>
                            <div class="ms-2">
                            @php
                        // Tạo biến để dùng lại
                        $role = $reply->user->getRoleNames()->first();
                        $bgMap = [
                            'Admin' => 'admin.gif',
                            'Mod' => 'vip.gif',
                            'Content' => 'content.gif',
                            'vip' => 'mod.gif',
                            'VIP PRO' => 'vip_pro.gif',
                            'VIP PRO MAX' => 'vip_pro_max.webp',
                            'VIP SIÊU VIỆT' => 'vip-sieu-viet.gif',
                        ];

                        $bgImage = isset($bgMap[$role]) ? asset('images/roles/' . $bgMap[$role]) : null;
                        $colorMap = [
                            'Admin' => 'red',
                            'Mod' => 'green',
                            'Content' => 'yellow',
                            'vip' => 'blue',
                            'VIP PRO' => 'purple',
                            'VIP PRO MAX' => '#f37200',
                            'VIP SIÊU VIỆT' => '',
                        ];
                        $textColor = $colorMap[$role] ?? 'black';
                    @endphp

                    @if ($role === 'VIP SIÊU VIỆT')
                        <div class="vip-sieu-viet-badge">
                            <span>{{ $reply->user->name }}</span>
                        </div>
                    @elseif ($bgImage)
                        <div style="
                            display: inline-block;
                            padding: 4px 8px;
                            color: {{ $textColor }};
                            background-image: url('{{ $bgImage }}');
                            background-size: cover;
                            background-repeat: no-repeat;
                            border-radius: 4px;
                            font-weight: bold;
                        ">
                            {{ $reply->user->name }}
                        </div>
                    @else
                        <strong>{{ $reply->user->name }}</strong>
                    @endif
                    @if($reply->user->email === 'khaicybers@gmail.com')
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/64012-management.png" width="24px" height="24px" style="margin-left:5px;" alt="Dev">
                            <span class="tooltip-text">Hỗ Trợ</span>
                        </span>                         <!-- bat dau -->
                    @elseif($reply->user->email === 'nguyenphuochau12t2@gmail.com')
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/65928-owner.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="tac gia">
                            <span class="tooltip-text">Tác Giả</span>
                        </span>
                        <div class="custom-badge-tooltip">
                            <img src="{{ asset('images/roles/kaybage.png') }}" alt="user" style="width: 60px; height: 60px; margin-left: 5px;">
                                <div class="custom-badge-tooltiptext">
                                    <img src="{{ asset('images/roles/kaybage.png') }}" alt="user" style="width: 70px; height: 70px; display: block; margin: 0 auto;">
                                    <div class="custom-badge-name">Tác Giả</div>
                                </div>
                        </div>
                    @elseif($reply->user->hasRole('Admin'))
                        <span class="tooltip-icon">
                            <img src="https://cdn3.emoji.gg/emojis/39760-owner.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="Admin">
                            <span class="tooltip-text">Quản Trị Viên</span>
                        </span>


                    @php
                        $badgeEmail = $reply->user->email;
                        $badgeName = 'Tông Chủ';
                        $badgeImage = asset('images/roles/tongchu.gif');
                        $badgeClass = ''; // default

                        if ($badgeEmail === 'nguyenphuochau12t2@gmail.com') {
                            $badgeName = 'Tác Giả';
                            $badgeImage = asset('images/roles/kaybage.png');
                            $badgeClass = 'badge-tacgia'; // class riêng cho tác giả
                        } elseif ($badgeEmail === 'nang2025@gmail.com') {
                            $badgeName = 'Thánh Nữ';
                            $badgeImage = asset('images/roles/thanhnu.gif');
                        }
                    @endphp

                    <div class="custom-badge-tooltip">
                        <img src="{{ $badgeImage }}" alt="user" class="{{ $badgeClass }}" style="width: 50px; height: 50px; margin-left: 5px;">
                        <div class="custom-badge-tooltiptext">
                            <img src="{{ $badgeImage }}" alt="user" class="{{ $badgeClass }}" style="width: 60px; height: 60px; display: block; margin: 0 auto;">
                            <div class="custom-badge-name">{{ $badgeName }}</div>
                        </div>
                    </div>

                    @endif

                    @if($reply->user->hasRole('Mod'))
                    <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/80156-developer.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="Mod">
                                    <span class="tooltip-text">Mod kiểm duyệt</span>
                                </span>
                    <div class="custom-badge-tooltip">
                        <img src="{{ asset('images/roles/chapphap.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: -4px;margin-top: -22px;">
                        <div class="custom-badge-tooltiptext">
                            <img src="{{ asset('images/roles/chapphap.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                            <div class="custom-badge-name">Chấp Pháp</div>
                        </div>
                    </div>
                    @endif
                    {{-- Badge cho VIP các loại --}}
                    @if ($reply->user->hasRole('vip'))
                    <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/45918-msp-super-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip1">
                                    <span class="tooltip-text">VIP Bậc I</span>
                                </span>
                    <div class="custom-badge-tooltip">
                        <img src="{{ asset('images/roles/tinhanh.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: 5px;">
                        <div class="custom-badge-tooltiptext">
                            <img src="{{ asset('images/roles/tinhanh.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                            <div class="custom-badge-name">Tinh Anh Bậc I</div>
                        </div>
                    </div>
                    @endif

                    @if ($reply->user->hasRole('VIP PRO'))
                    <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/44014-msp-elite-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip2">
                                    <span class="tooltip-text">VIP Bậc II</span>
                                </span>
                    <div class="custom-badge-tooltip">
                        <img src="{{ asset('images/roles/hophap.gif') }}" alt="user" style="width: 40px; height: 40px; margin-left: 5px;">
                        <div class="custom-badge-tooltiptext">
                            <img src="{{ asset('images/roles/hophap.gif') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                            <div class="custom-badge-name">Hộ Pháp Bậc II</div>
                        </div>
                    </div>                    
                    @endif

                    @if ($reply->user->hasRole('VIP PRO MAX'))
                    <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/55280-msp-star-vip.png" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vip3">
                                    <span class="tooltip-text">VIP Bậc III</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <div class="crossed-swords">
                                        <img src="{{ asset('images/roles/huyet-kiem-vip.gif') }}" alt="kiem xanh" class="sword-left">
                                        <img src="{{ asset('images/roles/kiemdo.gif') }}" alt="kiem do" class="sword-right">
                                    </div>

                                    <div class="custom-badge-tooltiptext">
                                        <img src="{{ asset('images/roles/huyet-kiem-vip.gif') }}" alt="kiem xanh" style="width: 50px; height: 50px; display: block; margin: 0 auto;transform: rotate(-85deg);">
                                        <img src="{{ asset('images/roles/kiemdo.gif') }}" alt="kiem do" style="width: 50px; height: 60px; display: block; margin: 0 auto;">
                                        <div class="custom-badge-name">Trưởng Lão Bậc III</div>
                                    </div>
                                </div>                   
                    @endif
                    @if ($reply->user->hasRole('User'))
                    <div class="custom-badge-tooltip">
                        <img src="{{ asset('images/roles/detu.gif?v=2') }}" alt="user" style="width: 30px;height: 30px;margin-left: -4px;margin-top: -22px;">
                        <div class="custom-badge-tooltiptext">
                            <img src="{{ asset('images/roles/detu.gif?v=2') }}" alt="user" style="width: 50px; height: 50px; display: block; margin: 0 auto;">
                            <div class="custom-badge-name">Đệ Tử</div>
                        </div>
                    </div>
                    @endif
                    @if ($reply->user->hasRole('VIP SIÊU VIỆT'))
                    <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/2336-vipgif.gif?v=2" width="30px" style="margin-left:5px;margin-top:-10px;" alt="vipmax">
                                    <span class="tooltip-text">VIP MAX</span>
                                </span>
                                <div class="custom-badge-tooltip">
                                    <img src="{{ asset('images/roles/bage_vipsieuviet.png?v=2') }}" alt="user" style="width: 70px;height: 50px;margin-left: -4px;margin-top: -22px;">
                                    <div class="custom-badge-tooltiptext">
                                    <img src="{{ asset('images/roles/bage_vipsieuviet.png?v=2') }}" alt="user" style="width: 70px; height: 60px; display: block; margin: 0 auto;">
                                <div class="custom-badge-name">Thái Thượng</div>
                            </div>
                        </div>
                    @endif

                    <!-- end -->
                    </div>
                        </div>
                        <span style="margin-left: 50px;" class="text-muted">- {{ $reply->created_at->locale('vi')->diffForHumans() }}</span>

                        <!-- <p class="mt-2">{{ $reply->content }}</p> -->
                        @php
                            $isVipSieuViet = $reply->user->hasRole('VIP SIÊU VIỆT');
                        @endphp

                        <p class="break-words whitespace-pre-line text-sm leading-relaxed break-all mt-2 {{ $isVipSieuViet ? 'vip-sieu-viet-content' : '' }}">
                            {!! $this->parseLinks($reply->content) !!}
                        </p>
                     {{-- Thêm nút xóa cho phản hồi --}}
                        <div class="d-flex">
                            @auth
                                @if($reply->user_id == auth()->id() || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod'))
                                    <button onclick="confirmDelete({{ $reply->id }})" class="btn btn-sm btn-outline-danger">Xóa</button>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach


            </div>
            @endforeach
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
    Swal.fire({
        title: 'Bạn có chắc muốn xoá?',
        text: "Hành động này sẽ không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xoá',
        cancelButtonText: 'Huỷ',
        position: 'center', // Đảm bảo thông báo hiển thị giữa màn hình
        backdrop: true,  // Đảm bảo có lớp nền mờ đằng sau
        willOpen: () => {
            document.body.style.overflow = 'hidden';  // Khóa cuộn trang khi hiển thị thông báo
        },
        willClose: () => {
            document.body.style.overflow = '';  // Mở lại cuộn trang khi thông báo đóng
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Gọi method Livewire trong component hiện tại
            @this.call('deleteComment', id);
        }
    });
}

// Nghe sự kiện xóa thành công
window.addEventListener('deleteSuccess', () => {
    Swal.fire({
        title: 'Đã xoá!',
        text: 'Nội dung đã được xoá.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        position: 'center',  // Đảm bảo thông báo hiển thị giữa màn hình
        backdrop: true,  // Lớp nền mờ khi thông báo hiện
        willOpen: () => {
            document.body.style.overflow = 'hidden';  // Khóa cuộn trang
        },
        willClose: () => {
            document.body.style.overflow = '';  // Mở lại cuộn trang khi thông báo đóng
        }
    });
});



$(document).ready(function() {
    // Sau khi nội dung đã được tải hoàn toàn
    $('p').each(function() {
        var text = $(this).html();
        
        // Tự động nhận diện link và thay thế bằng thẻ <a>
        var updatedText = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" class="text-blue-500 underline hover:text-blue-700">$1</a>');
        
        $(this).html(updatedText);
    });
});

 
</script>

