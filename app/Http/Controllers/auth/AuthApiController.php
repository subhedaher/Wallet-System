<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Spatie\Permission\Models\Role;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator($request->all(), [
            'full_name' => 'required|string|max:20',
            'id_number' => 'required|numeric|digits:9|unique:users,id_number',
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'email' => 'required|email|unique:users,email',
            'image' => 'nullable|image|mimes:jpg,png',
            'address' => 'required|string|max:50',
            'password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->symbols()
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised(), 'confirmed'
            ]
        ]);

        if (!$validator->fails()) {
            $user = new User();
            $user->full_name = $request->input('full_name');
            $user->id_number = $request->input('id_number');
            $user->phone_number = $request->input('phone_number');
            $user->email = $request->input('email');
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $name = $imageFile->store('users', ['disk' => 'public']);
                $user->image = $name;
            }
            $user->address = $request->input('address');
            $user->password = Hash::make($request->input('password'));
            $isSaved = $user->save();
            if ($isSaved) {
                $role = Role::where('name', '=', 'User-Api')->first();
                $user->assignRole($role);
            }
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "User Added Successfully" : "User Added Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
            'password' => 'required|string'
        ]);

        if (!$validator->fails()) {
            $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'KOKt0D2CEovYyMcLNp7hdIXj6KI3IXsFWtH6yOgj',
                'username' => $request->input('phone_number'),
                'password' => $request->input('password'),
                'scope' => '*',
            ]);
            $json = $response->json();
            if (array_key_exists('error', $json)) {
                return response()->json([
                    'status' => false,
                    'error' => $json['error'],
                    'message' => $json['message']
                ]);
            } else {
                $user = User::where('phone_number', '=', $request->input('phone_number'))->first();
                $user->setAttribute('toekn', $json['access_token']);
                return response()->json([
                    'status' => true,
                    'user' => $user
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        $revoked = $request->user()->token()->revoke();
        return response()->json([
            'status' => $revoked,
            'message' => $revoked ? "Logout Successfully" : " Logout Failed!"
        ], $revoked ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    function changePassword(Request $request)
    {
        $validator = Validator($request->all(), [
            'password' => 'required|string|current_password:user-api',
            'new_password' => [
                'required', 'string', Password::min(8)
                    ->letters()
                    ->uncompromised()
                    ->symbols()
                    ->mixedCase(), 'confirmed'
            ]
        ]);

        if (!$validator->fails()) {
            $request->user()->forcefill([
                'password' => Hash::make($request->input('new_password'))
            ]);
            $isSaved = $request->user()->save();
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Changed Password Successfully" : "Changed Password Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    function updateProfile(Request $request)
    {
        $validator = Validator($request->all(), [
            'full_name' => 'required|string|max:20',
            'id_number' => 'required|numeric|digits:9|unique:users,id_number,' . $request->user()->id,
            'phone_number' => 'required|string|max:15|unique:users,phone_number,' . $request->user()->id,
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'image' => 'nullable|image|mimes:jpg,png',
            'address' => 'required|string|max:50'
        ]);
        if (!$validator->fails()) {
            $user = $request->user();
            $user->full_name = $request->input('full_name');
            $user->id_number = $request->input('id_number');
            $user->phone_number = $request->input('phone_number');
            if ($request->input('email') !== $user->email) {
                $user->email_verified_at = null;
                $user->email = $request->input('email');
            }
            if ($request->hasFile('image')) {
                if (!is_null($user->image)) {
                    Storage::delete($user->image);
                }
                $imageFile = $request->file('image');
                $name = $imageFile->store('users', ['disk' => 'public']);
                $user->image = $name;
            }
            $user->address = $request->input('address');
            $isSaved = $user->save();
            return response()->json([
                'status' => $isSaved,
                'message' => $isSaved ? "Profile Updated Successfully" : "Profile Updated Failed!"
            ], $isSaved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
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
        return response()->json([
            'status' => true,
            'messgae' => 'Verification Request Successfully'
        ]);
    }

    function sendResetEmail(Request $request)
    {
        $validaor = Validator($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if (!$validaor->fails()) {
            $status = FacadesPassword::broker('users')->sendResetLink($request->only('email'));
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
            $status = FacadesPassword::broker('users')->reset($request->all(), function ($user, $password) {
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
}