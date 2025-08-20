<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\OTPForgotPWMail;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Mail\OTPUpdateUserMail;

class UserController extends Controller

{
    public function searchuser(Request $request)
{
    $query = $request->input('query');

    // Tìm kiếm người dùng theo tên (bạn có thể thay đổi logic nếu cần)
    $users = User::where('name', 'LIKE', "%{$query}%")
                 ->limit(5)
                 ->get(['id', 'name']);

    return response()->json(['users' => $users]);
}

    public function show($id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        // Check permissions
        if ($authUser->role === 'mod') {
            if ($user->role === 'admin' || $user->role === 'mode') {
                abort(403, 'Unauthorized action.');
            }
        }

        // Only show active users
        if ($user->active !== 'active') {
            abort(404);
        }

        return view('admin.pages.users.show', compact('user'));
    }
    public function bulkDelete(Request $request)
{
    $userIds = $request->input('user_ids', []);

    if (empty($userIds)) {
        return response()->json(['success' => false, 'message' => 'Không có người dùng nào được chọn.']);
    }

    User::whereIn('id', $userIds)->delete();

    return response()->json(['success' => true, 'message' => 'Xóa thành công!']);
}

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Hãy nhập tên của bạn.',
            'email.required' => 'Hãy nhập email của bạn.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Hãy nhập mật khẩu.',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);
    
        $verificationToken = Str::random(64);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 2, // Tạm thời vô hiệu hóa tài khoản cho đến khi xác thực
            'verification_token' => $verificationToken,
        ]);
    
        // Gửi email xác thực
        Mail::to($user->email)->send(new OTPMail($user));
    
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy kiểm tra email để kích hoạt tài khoản.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Hãy nhập email của bạn vào đi',
            'email.email' => 'Email bạn nhập không hợp lệ rồi',
            'password.required' => 'Hãy nhập mật khẩu của bạn vào đi',
        ]);

        try {

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Thông tin xác thực không chính xác',
                ]);
            }

            if ($user->active == 'inactive') {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Tài Khoản Của Bạn Đã Bị Ban',
                ]);
            }

            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->withInput()->withErrors([
                    'password' => 'Mat khau sai',
                ]);
            }

            Auth::login($user);

            $user->ip_address = $request->ip();
            $user->save();

            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role == 'user') {
                return redirect()->route('home');
            }

            return redirect()->route('home');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập. Vui lòng thử lại sau.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function forgotPassword(Request $request)
    {
        if ($request->has('email')) {
            try {
                $request->validate([
                    'email' => 'required|email',
                ], [
                    'email.required' => 'Hãy nhập email của bạn vào đi',
                    'email.email' => 'Email bạn nhập không hợp lệ rồi',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors()
                ], 422);
            }

            try {
                $user = User::where('email', $request->email)->first();
                if (!$user || $user->active == 'inactive') {
                    return response()->json([
                        'status' => 'error',
                        'message' => ['email' => ['Thông tin xác thực không chính xác']],
                    ], 422);
                }



                if ($request->has('email') && $request->has('otp')) {

                    try {
                        $request->validate([
                            'otp' => 'required',
                        ], [
                            'otp.required' => 'Hãy nhập mã OTP của bạn vào đi',
                        ]);
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $e->errors()
                        ], 422);
                    }

                    if (!password_verify($request->otp, $user->key_reset_password)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => ['otp' => ['Mã OTP không chính xác']],
                        ], 422);
                    }

                    if ($request->has('email') && $request->has('otp') && $request->has('password')) {
                        try {
                            $request->validate([
                                'password' => 'required|min:6',
                            ], [
                                'password.required' => 'Hãy nhập mật khẩu của bạn vào đi',
                                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                            ]);
                        } catch (\Illuminate\Validation\ValidationException $e) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $e->errors()
                            ], 422);
                        }

                        try {

                            $user->key_reset_password = null;
                            $user->password = bcrypt($request->password);
                            $user->save();

                            Auth::login($user);

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Đặt lại mật khẩu thành công',
                                'url' => route('home'),
                            ]);
                        } catch (Exception $e) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Đã xảy ra lỗi trong quá trình đặt lại mật khẩu. Vui lòng thử lại sau.',
                                'error' => $e->getMessage(),
                            ], 500);
                        }
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Hãy nhập mật khẩu mới của bạn',
                    ], 200);
                }

                if ($user->reset_password_at != null) {
                    $resetPasswordAt = Carbon::parse($user->reset_password_at);
                    if (!$resetPasswordAt->lt(Carbon::now()->subMinutes(3))) {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Dùng lại OTP đã gửi trước đó, nhận OTP mới sau 3 phút',
                        ], 200);
                    }
                }

                $randomOTPForgotPW = $this->generateRandomOTP();
                $user->key_reset_password = bcrypt($randomOTPForgotPW);
                $user->reset_password_at = Carbon::now();
                $user->save();

                Mail::to($user->email)->send(new OTPForgotPWMail($randomOTPForgotPW));
                return response()->json([
                    'status' => 'success',
                    'message' => 'Hãy kiểm tra email của bạn để lấy mã OTP',
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã xảy ra lỗi trong quá trình đặt lại mật khẩu. Vui lòng thử lại sau.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function OTPMail($token)
{
    $user = User::where('verification_token', $token)->first();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Mã xác thực không hợp lệ.');
    }

    $user->email_verified_at = Carbon::now();
    $user->verification_token = null;
    $user->status = 1; // Kích hoạt tài khoản
    $user->save();

    return redirect()->route('login')->with('success', 'Xác thực email thành công! Bạn có thể đăng nhập.');
}


    public function changePassword() {}

    public function userProfile()
    {
        $user = Auth::user();

        return view('Frontend.auth.profile', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ], [
                'avatar.required' => 'Hãy chọn ảnh avatar',
                'avatar.image' => 'Avatar phải là ảnh',
                'avatar.mimes' => 'Chỉ chấp nhận ảnh định dạng jpeg, png, jpg, gif, svg',
                'avatar.max' => 'Dung lượng avatar không được vượt quá 4MB'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 422);
        }
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $imageBackup = $user->avatar;
            $imageName = $user->id . time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('/uploads/images/avatar/'), $imageName);
            $imagePath = '/uploads/images/avatar/' . $imageName;

            $user->avatar = $imagePath;
            $user->save();

            DB::commit();

            if (isset($imageBackup) && File::exists($imageBackup)) {
                File::delete($imageBackup);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật avatar thành công',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau'
            ], 500);
        }
    }
    public function updateNameOrPhone(Request $request)
    {

        if ($request->has('name')) {
            try {
                $request->validate([
                    'name' => 'required|string|min:3|max:255',
                ], [
                    'name.required' => 'Hãy nhập tên',
                    'name.string' => 'Tên phải là chuỗi',
                    'name.min' => 'Tên phải có ít nhất 3 ký tự',
                    'name.max' => 'Tên không được vượt quá 255 ký tự'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->route('profile')->with('error', $e->errors());
            }

            try {
                $user = Auth::user();
                $user->name = $request->name;
                $user->save();
                return redirect()->route('profile')->with('success', 'Cập nhật tên thành công');
            } catch (\Exception $e) {
                return redirect()->route('profile')->with('error', 'Cập nhật tên thất bại');
            }
        } elseif ($request->has('phone')) {

            try {
                $request->validate([
                    'phone' => 'required|string|min:10|max:10',
                ], [
                    'phone.required' => 'Hãy nhập số điện thoại',
                    'phone.string' => 'Số điện thoại phải là chuỗi',
                    'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự',
                    'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->route('profile')->with('error', $e->errors());
            }

            try {
                $user = Auth::user();
                $user->phone = $request->phone;
                $user->save();
                return redirect()->route('profile')->with('success', 'Cập nhật số điện thoại thành công');
            } catch (\Exception $e) {
                return redirect()->route('profile')->with('error', 'Cập nhật số điện thoại thất bại');
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ'
            ], 422);
        }
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if ($request->has('otp')) {
            $otp = $request->otp;

            if (!password_verify($otp, $user->key_reset_password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => ['otp' => ['Mã OTP không chính xác để thay đổi mật khẩu']],
                ], 422);
            }

            if ($request->has('password') && $request->has('password_confirmation')) {
                try {
                    $request->validate([
                        'password' => 'required|min:6|confirmed',
                    ], [
                        'password.required' => 'Hãy nhập mật khẩu mới',
                        'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                        'password.confirmed' => 'Mật khẩu xác nhận không khớp',
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->errors()
                    ], 422);
                }

                $user->password = bcrypt($request->password);
                $user->key_reset_password = null;

                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cập nhật mật khẩu thành công',
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Xác thực OTP thành công',
            ], 200);
        }

        $otp = $this->generateRandomOTP();
        if ($user->reset_password_at != null) {
            $resetPasswordAt = Carbon::parse($user->reset_password_at);
            if (!$resetPasswordAt->lt(Carbon::now()->subMinutes(3))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP đã được gửi đến Email của bạn, hoặc thử lại sau 3 phút',
                ], 200);
            }
        }

        $user->key_reset_password = bcrypt($otp);
        $user->reset_password_at = now();
        $user->save();

        Mail::to($user->email)->send(new OTPUpdateUserMail($otp, 'password'));
        return response()->json([
            'status' => 'success',
            'message' => 'Gửi mã OTP thành công, vui lòng kiểm tra Email của bạn',
        ], 200);
    }

    public function updateBankAccount(Request $request)
    {
        $user = Auth::user();

        if ($request->has('otp')) {
            $otp = $request->otp;

            if (!password_verify($otp, $user->key_change_bank)) {
                return response()->json([
                    'status' => 'error',
                    'message' => ['otp' => ['Mã OTP không chính xác để thay đổi thông tin ngân hàng']],
                ], 422);
            }

            if ($request->has('bank_id') && $request->has('account_number') && $request->has('account_name')) {
                try {
                    $request->validate([
                        'bank_id' => 'required|exists:banks,id',
                        'account_number' => 'required',
                        'account_name' => 'required',
                    ], [
                        'bank_id.required' => 'Hãy chọn ngân hàng',
                        'bank_id.exists' => 'Ngân hàng không tồn tại',
                        'account_number.required' => 'Hãy nhập số tài khoản',
                        'account_name.required' => 'Hãy nhập tên chủ tài khoản',
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->errors()
                    ], 422);
                }

                $user->key_change_bank = null;

                $data = [
                    'bank_id' => $request->input('bank_id'),
                    'account_number' => $request->input('account_number'),
                    'account_name' => $request->input('account_name'),
                ];

                $bank_account = $user->bankAccount()->updateOrCreate(
                    ['user_id' => $user->id], // Điều kiện để kiểm tra sự tồn tại
                    $data // Dữ liệu cần cập nhật hoặc tạo mới
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cập nhật thông tin ngân hàng thành công',
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Xác thực OTP thành công',
            ], 200);
        }

        $otp = $this->generateRandomOTP();
        if ($user->change_bank_at != null) {
            $changeBankAt = Carbon::parse($user->change_bank_at);
            if (!$changeBankAt->lt(Carbon::now()->subMinutes(3))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP đã được gửi đến Email của bạn, hoặc thử lại sau 3 phút',
                ], 200);
            }
        }

        $user->key_change_bank = bcrypt($otp);
        $user->change_bank_at = now();
        $user->save();

        Mail::to($user->email)->send(new OTPUpdateUserMail($otp, 'Banking'));

        return response()->json([
            'status' => 'success',
            'message' => 'Gửi mã OTP thành công, vui lòng kiểm tra Email của bạn',
        ], 200);
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Người dùng đã bị xóa.');
}

public function toggleBan(Request $request, $id)
{
    $user = User::findOrFail($id);
    $type = $request->type;
    $status = $request->status;

    if (!in_array($type, ['login', 'comment', 'ip'])) {
        return response()->json(['message' => 'Loại cấm không hợp lệ'], 400);
    }

    if ($type === 'ip') {
        $user->banned_ips = $status ? request()->ip() : null;
    } else {
        $field = "ban_{$type}";
        $user->$field = $status;
    }

    $user->save();

    return response()->json([
        'message' => 'Cập nhật thành công!',
        'status' => 'success'
    ]);
}


}
