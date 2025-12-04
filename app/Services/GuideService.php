<?php

namespace App\Services;

use App\Models\Guide;
use Illuminate\Support\Facades\DB;

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

