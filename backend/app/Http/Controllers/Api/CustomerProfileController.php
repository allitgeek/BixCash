<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CustomerProfileController extends Controller
{
    /**
     * Get customer profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->isCustomer()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Load customer profile
            $profile = $user->customerProfile;

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer profile not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'phone_verified' => $user->hasVerifiedPhone(),
                        'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                        'has_pin_set' => !is_null($user->pin_hash),
                    ],
                    'profile' => [
                        'id' => $profile->id,
                        'full_name' => $profile->full_name,
                        'date_of_birth' => $profile->date_of_birth,
                        'gender' => $profile->gender,
                        'address' => $profile->address,
                        'city' => $profile->city,
                        'postal_code' => $profile->postal_code,
                        'cnic' => $profile->cnic,
                        'cnic_verified' => $profile->cnic_verified,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get profile failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile'
            ], 500);
        }
    }

    /**
     * Update customer profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->isCustomer()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'full_name' => ['nullable', 'string', 'max:255'],
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'gender' => ['nullable', 'in:male,female,other'],
                'address' => ['nullable', 'string', 'max:500'],
                'city' => ['nullable', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'cnic' => ['nullable', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            ]);

            // Update user record
            $user->name = $validated['name'];
            if (isset($validated['email'])) {
                $user->email = $validated['email'];
            }
            $user->save();

            // Update or create profile
            $profile = $user->customerProfile;

            if (!$profile) {
                $profile = new CustomerProfile(['user_id' => $user->id]);
            }

            if (isset($validated['full_name'])) {
                $profile->full_name = $validated['full_name'];
            }
            if (isset($validated['date_of_birth'])) {
                $profile->date_of_birth = $validated['date_of_birth'];
            }
            if (isset($validated['gender'])) {
                $profile->gender = $validated['gender'];
            }
            if (isset($validated['address'])) {
                $profile->address = $validated['address'];
            }
            if (isset($validated['city'])) {
                $profile->city = $validated['city'];
            }
            if (isset($validated['postal_code'])) {
                $profile->postal_code = $validated['postal_code'];
            }
            if (isset($validated['cnic'])) {
                $profile->cnic = $validated['cnic'];
            }

            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'phone_verified' => $user->hasVerifiedPhone(),
                        'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                    ],
                    'profile' => [
                        'id' => $profile->id,
                        'full_name' => $profile->full_name,
                        'date_of_birth' => $profile->date_of_birth,
                        'gender' => $profile->gender,
                        'address' => $profile->address,
                        'city' => $profile->city,
                        'postal_code' => $profile->postal_code,
                        'cnic' => $profile->cnic,
                        'cnic_verified' => $profile->cnic_verified,
                    ]
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update profile failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }
    }

    /**
     * Upload customer avatar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->isCustomer()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'] // Max 2MB
            ]);

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars/customers', 'public');

            // Update user record
            $user->avatar = $avatarPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar_url' => Storage::url($avatarPath)
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Upload avatar failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar'
            ], 500);
        }
    }

    /**
     * Delete customer avatar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAvatar(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->isCustomer()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            if (!$user->avatar) {
                return response()->json([
                    'success' => false,
                    'message' => 'No avatar to delete'
                ], 404);
            }

            // Delete avatar file
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Update user record
            $user->avatar = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete avatar failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete avatar'
            ], 500);
        }
    }
}
