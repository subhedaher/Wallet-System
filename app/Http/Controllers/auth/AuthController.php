<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    function showLogin($guard)
    {
        $validaor = Validator(['guard' => $guard], [
            'guard' => 'required|string|in:admin,shippingPoint'
        ]);
        if (!$validaor->fails()) {
            session()->put('guard', $guard);
            return view('cms.auth.login');
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    function login(Request $request)
    {
        $guard = str::plural(session('guard'));
        if ($guard == 'shippingPoints') {
            $guard = 'shipping_points';
        }
        $validaor = Validator($request->all(), [
            'email' => 'required|email|exists:' . $guard,
            'password' => 'required|string',
            'remember' => 'required|boolean'
        ]);

        if (!$validaor->fails()) {
            if (Auth::guard(session('guard'))->attempt($request->only(['email', 'password']), $request->input('remember'))) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Successfully'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login Failed!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validaor->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function logout(Request $request)
    {
        $guard = session('guard');
        auth($guard)->logout();
        $request->session()->invalidate();
        session()->remove('guard');
        return redirect()->route('auth.showLogin', $guard);
    }

    function editPassword()
    {
        return view('cms.auth.change-password');
    }

    function updatePassword(Request $request)
    {
        $guard = session('guard');
        $validaor = Validator($request->all(), [
            'old-password' => 'required|string|current_password:' . $guard,
            'new-password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->uncompromised()
                    ->symbols()
                    ->mixedCase(), 'confirmed'
            ]
        ]);

        if (!$validaor->fails()) {
            $request->user()->forceFill([
                'password' => Hash::make($request->input('new-password'))
            ])->save();
            return response()->json([
                'status' => true,
                'message' => 'Changed Password Successfully'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validaor->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function forgotPassword()
    {
        return view('cms.auth.forgot-password');
    }

    function sendResetEmail(Request $request)
    {
        $borker = Str::plural(session('guard'));
        $validaor = Validator($request->all(), [
            'email' => 'required|email|exists:' . $borker
        ]);

        if (!$validaor->fails()) {
            $status = FacadesPassword::broker($borker)->sendResetLink($request->only('email'));
            return $status == FacadesPassword::RESET_LINK_SENT ? response()->json([
                'status' => true,
                'message' => __($status)
            ]) : response()->json([
                'status' => false,
                'message' => __($status)
            ], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validaor->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function recoverPassword(Request $request, $token)
    {
        return view('cms.auth.recover-password', ['email' => $request->input('email'), 'token' => $token]);
    }

    function resetPassword(Request $request)
    {
        $validaor = Validator($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:password_reset_tokens',
            'password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->uncompromised()
                    ->symbols()
                    ->mixedCase(), 'confirmed'
            ]
        ]);

        if (!$validaor->fails()) {
            $borker = Str::plural(session('guard'));
            $status = FacadesPassword::broker($borker)->reset($request->all(), function ($user, $password) {
                $user->forcefill(['password' => Hash::make($password)])->save();
                event(new ResetPassword($user));
            });
            return $status == FacadesPassword::PASSWORD_RESET ? response()->json([
                'status' => true,
                'message' => __($status)
            ]) : response()->json([
                'status' => false,
                'message' => __($status)
            ], Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validaor->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function showEmailVerification()
    {
        return view('cms.auth.email-verification');
    }

    function sendVerifyEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'status' => true,
            'message' => 'Send Email Verifcation Successfully'
        ], Response::HTTP_OK);
    }

    function verify(EmailVerificationRequest $emailVerificationRequest)
    {
        $emailVerificationRequest->fulfill();
        return redirect()->route('cms.index');
    }

    function editUser(Request $request)
    {
        return view('cms.auth.editProfile', ['user' => $request->user()]);
    }

    function updateUser(Request $request)
    {
        if (session('guard') === 'admin') {
            $validator = Validator($request->all(), [
                'full_name' => 'required|string',
                'phone_number' => 'required|string|max:15|unique:admins,phone_number,' . $request->user()->id,
                'email' => "required|email|unique:admins,email," . $request->user()->id
            ]);
        } else {
            $validator = Validator($request->all(), [
                'full_name' => 'required|string',
                'phone_number' => 'required|string|max:15|unique:shipping_points,phone_number,' . $request->user()->id,
                'address' => 'required|string|max:20',
                'email' => 'required|string|unique:shipping_points,email,' . $request->user()->id,
            ]);
        }

        if (!$validator->fails()) {
            $user = $request->user();
            if ($user->email !== $request->input('email')) {
                $user->email_verified_at = null;
            }
            $isSaved = $user->forcefill($request->all())->save();
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Update Profile Successfully" : "Update Profile Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function readAllNotifications(Request $request)
    {
        $user = $request->user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }
}
