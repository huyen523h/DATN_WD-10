<?php

namespace App\Services;

use App\Models\Guide;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GuideService
{
    public function create(array $data): Guide
    {
        return DB::transaction(function () use ($data) {
            // Đảm bảo có full_name
            if (empty($data['full_name']) && !empty($data['name'])) {
                $data['full_name'] = $data['name'];
            }
            
            // Nếu vẫn không có full_name, tạo từ email hoặc tạo mặc định
            if (empty($data['full_name'])) {
                if (!empty($data['email'])) {
                    $data['full_name'] = explode('@', $data['email'])[0];
                } else {
                    $data['full_name'] = 'Hướng dẫn viên';
                }
            }
            
            // Xóa trường name nếu có (không cần thiết)
            unset($data['name']);

            // Tạm thời không ghi đè cột status gốc trong DB (tránh xung đột schema cũ)
            unset($data['status']);
            
            // Tạo tài khoản User cho hướng dẫn viên - BẮT BUỘC khi thêm mới
            // Email đã được validate là required và unique trong StoreGuideRequest
            $user = null;
            $initialPassword = null;
            
            if (!empty($data['email'])) {
                // Kiểm tra xem email đã tồn tại chưa (phòng trường hợp validation bị bypass)
                $existingUser = User::where('email', $data['email'])->first();
                
                if (!$existingUser) {
                    // Tạo password mặc định (có thể thay đổi sau)
                    $initialPassword = $data['password'] ?? Str::random(12);
                    
                    $user = User::create([
                        'name' => $data['full_name'] ?? $data['name'] ?? 'Guide',
                        'email' => $data['email'],
                        'password' => Hash::make($initialPassword),
                        'phone' => $data['phone'] ?? null,
                        'address' => $data['address'] ?? null,
                        'role' => 'guide', // CRITICAL: Set role directly in users table
                    ]);
                    
                    // Gán role 'guide' cho user trong bảng user_roles (nếu có)
                    $guideRole = Role::where('name', 'guide')->first();
                    if ($guideRole) {
                        $user->roles()->attach($guideRole->id);
                    }
                } else {
                    // Nếu user đã tồn tại, gán role guide nếu chưa có
                    if ($existingUser->role !== 'guide') {
                        $existingUser->update(['role' => 'guide']);
                    }
                    
                    $guideRole = Role::where('name', 'guide')->first();
                    if ($guideRole && !$existingUser->hasRole('guide')) {
                        $existingUser->roles()->attach($guideRole->id);
                    }
                    $user = $existingUser;
                }
                
                // Lưu user_id vào trường user_id và metadata
                $data['user_id'] = $user->id;
                $currentMetadata = $data['metadata'] ?? [];
                if (is_string($currentMetadata)) {
                    $currentMetadata = json_decode($currentMetadata, true) ?? [];
                }
                if (!is_array($currentMetadata)) {
                    $currentMetadata = [];
                }
                $currentMetadata['user_id'] = $user->id;
                if ($initialPassword) {
                    $currentMetadata['initial_password'] = $initialPassword;
                }
                $data['metadata'] = $currentMetadata;
            }
            
            // Đảm bảo các trường bắt buộc có giá trị mặc định
            $data['experience_years'] = $data['experience_years'] ?? 0;
            $data['rating_average'] = $data['rating_average'] ?? 0.0;
            $data['rating_count'] = $data['rating_count'] ?? 0;
            
            // Tạo code tự động nếu chưa có
            if (empty($data['code'])) {
                $data['code'] = 'HDV' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                
                // Đảm bảo code là unique
                while (Guide::where('code', $data['code'])->exists()) {
                    $data['code'] = 'HDV' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                }
            }
            
            // Debug: Log data before creating guide
            Log::info('Creating guide with data:', $data);
            
            try {
                $guide = Guide::create($data);
                Log::info('Guide created successfully:', ['id' => $guide->id]);
            } catch (\Exception $e) {
                Log::error('Failed to create guide:', [
                    'error' => $e->getMessage(),
                    'data' => $data
                ]);
                throw $e;
            }
            $this->syncRelations($guide, $data);

            return $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        });
    }

    public function update(Guide $guide, array $data): Guide
    {
        return DB::transaction(function () use ($guide, $data) {
            // Đảm bảo có full_name
            if (empty($data['full_name']) && !empty($data['name'])) {
                $data['full_name'] = $data['name'];
            }
            
            // Xóa trường name nếu có (không cần thiết)
            unset($data['name']);

            // Không cập nhật cột status để tránh lỗi với schema cũ
            unset($data['status']);
            
            $guide->update($data);
            $this->syncRelations($guide, $data);

            return $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        });
    }

    protected function syncRelations(Guide $guide, array $payload): void
    {
        if (array_key_exists('category_ids', $payload)) {
            $guide->categories()->sync($payload['category_ids'] ?? []);
        }

        if (array_key_exists('languages', $payload)) {
            $guide->languages()->delete();
            $languages = array_filter($payload['languages'] ?? [], function ($lang) {
                return !empty($lang['language']);
            });
            if (!empty($languages)) {
                $guide->languages()->createMany($languages);
            }
        }

        if (array_key_exists('documents', $payload)) {
            $guide->documents()->delete();
            $documents = array_filter($payload['documents'] ?? [], function ($doc) {
                return !empty($doc['type']) || !empty($doc['name']);
            });
            if (!empty($documents)) {
                $guide->documents()->createMany($documents);
            }
        }

        if (array_key_exists('health_records', $payload)) {
            $guide->healthRecords()->delete();
            $healthRecords = array_filter($payload['health_records'] ?? [], function ($record) {
                return !empty($record['check_date']) && !empty($record['status']);
            });
            if (!empty($healthRecords)) {
                $guide->healthRecords()->createMany($healthRecords);
            }
        }

        // Xử lý certifications - lọc bỏ giá trị rỗng
        if (array_key_exists('certifications', $payload)) {
            $certifications = array_filter($payload['certifications'] ?? [], function ($cert) {
                return !empty($cert);
            });
            $guide->update(['certifications' => array_values($certifications)]);
        }
    }
}

