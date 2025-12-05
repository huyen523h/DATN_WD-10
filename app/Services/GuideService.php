<?php

namespace App\Services;

use App\Models\Guide;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuideService
{
    public function create(array $data): Guide
    {
        return DB::transaction(function () use ($data) {
            // Map full_name -> name cho schema cũ (bảng guides đang dùng cột name)
            if (!empty($data['full_name']) && empty($data['name'])) {
                $data['name'] = $data['full_name'];
            }

            // Xử lý tạo / gán tài khoản user cho HDV
            $userId = $this->resolveGuideUserId($data);
            if ($userId) {
                $data['user_id'] = $userId;
                // Đồng bộ email/phone mặc định từ user nếu thiếu
                $user = User::find($userId);
                $data['email'] = $data['email'] ?? $user?->email;
                $data['phone'] = $data['phone'] ?? $user?->phone;
            }

            // Tạm thời không ghi đè cột status gốc trong DB (tránh xung đột schema cũ)
            unset($data['status']);
            unset($data['create_user_account'], $data['user_email'], $data['user_password']);

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

            // Xử lý tạo / gán tài khoản user nếu có yêu cầu
            $userId = $this->resolveGuideUserId($data, $guide);
            if ($userId) {
                $data['user_id'] = $userId;
            }

            // Không cập nhật cột status để tránh lỗi với schema cũ
            unset($data['status']);
            unset($data['create_user_account'], $data['user_email'], $data['user_password']);
            
            $guide->update($data);
            $this->syncRelations($guide, $data);

            return $guide->load(['categories', 'languages', 'documents', 'healthRecords']);
        });
    }

    /**
     * Tạo mới hoặc lấy user_id để gán cho HDV dựa trên payload.
     */
    protected function resolveGuideUserId(array &$data, Guide $existingGuide = null): ?int
    {
        // Nếu đã truyền sẵn user_id thì ưu tiên dùng
        if (!empty($data['user_id'])) {
            return (int) $data['user_id'];
        }

        // Nếu đang update và guide đã có user_id, mặc định giữ nguyên
        if ($existingGuide && $existingGuide->user_id && empty($data['create_user_account'])) {
            return $existingGuide->user_id;
        }

        // Nếu được yêu cầu tạo tài khoản mới
        $shouldCreate = !empty($data['create_user_account']);
        if (!$shouldCreate) {
            return $existingGuide?->user_id;
        }

        // Cần có email & password để tạo tài khoản
        $email = $data['user_email'] ?? $data['email'] ?? null;
        $password = $data['user_password'] ?? null;

        if (!$email || !$password) {
            return $existingGuide?->user_id;
        }

        // Nếu user với email này đã tồn tại, dùng luôn
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $data['full_name'] ?? ($existingGuide?->full_name ?? $email),
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => $data['phone'] ?? null,
            ]);
        }

        // Gán role guide cho user nếu chưa có
        $guideRole = Role::where('name', 'guide')->first();
        if ($guideRole && !$user->roles()->where('role_id', $guideRole->id)->exists()) {
            $user->roles()->attach($guideRole->id);
        }

        return $user->id;
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

