<?php

namespace App\Services;

use App\Models\Guide;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuideService
{
    public function create(array $data): Guide
    {
        return DB::transaction(function () use ($data) {
            // Map full_name -> name cho schema cũ (bảng guides đang dùng cột name)
            if (!empty($data['full_name']) && empty($data['name'])) {
                $data['name'] = $data['full_name'];
            }

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
                    ]);
                    
                    // Gán role 'guide' cho user
                    $guideRole = Role::where('name', 'guide')->first();
                    if ($guideRole) {
                        $user->roles()->attach($guideRole->id);
                    }
                } else {
                    // Nếu user đã tồn tại, gán role guide nếu chưa có
                    $guideRole = Role::where('name', 'guide')->first();
                    if ($guideRole && !$existingUser->hasRole('guide')) {
                        $existingUser->roles()->attach($guideRole->id);
                    }
                    $user = $existingUser;
                }
                
                // Lưu user_id vào metadata
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
            
            $guide = Guide::create($data);
            $this->syncRelations($guide, $data);

            return $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        });
    }

    public function update(Guide $guide, array $data): Guide
    {
        return DB::transaction(function () use ($guide, $data) {
            // Map full_name -> name cho schema cũ
            if (!empty($data['full_name'])) {
                $data['name'] = $data['full_name'];
            }

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

