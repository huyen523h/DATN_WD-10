<?php

namespace App\Http\Requests\Admin\Guide;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:guides,code'],
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'avatar_url' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'primary_language' => ['nullable', 'string', 'max:100'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'specialty_routes' => ['nullable', 'string'],
            'biography' => ['nullable', 'string'],
            'certifications' => ['nullable', 'array'],
            'certifications.*' => ['nullable', 'string'],
            'rating_average' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'rating_count' => ['nullable', 'integer', 'min:0'],
            // status tạm thời không ràng buộc chặt để tránh xung đột với schema cũ
            'status' => ['nullable', 'string', 'max:50'],
            'health_status' => ['nullable', 'string', 'max:255'],
            'last_medical_check_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],

            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:guide_categories,id'],

            'languages' => ['nullable', 'array'],
            'languages.*.language' => ['nullable', 'string', 'max:100'],
            'languages.*.proficiency' => ['nullable', 'in:basic,intermediate,advanced,native'],
            'languages.*.certification_code' => ['nullable', 'string', 'max:100'],
            'languages.*.certified_at' => ['nullable', 'date'],

            'documents' => ['nullable', 'array'],
            'documents.*.type' => ['nullable', 'string', 'max:100'],
            'documents.*.name' => ['nullable', 'string', 'max:255'],
            'documents.*.file_path' => ['nullable', 'string', 'max:500'],
            'documents.*.issued_by' => ['nullable', 'string', 'max:255'],
            'documents.*.issued_at' => ['nullable', 'date'],
            'documents.*.expires_at' => ['nullable', 'date', 'after_or_equal:documents.*.issued_at'],
            'documents.*.status' => ['nullable', 'in:valid,expired,revoked,pending'],
            'documents.*.notes' => ['nullable', 'string'],

            'health_records' => ['nullable', 'array'],
            'health_records.*.check_date' => ['nullable', 'date'],
            'health_records.*.status' => ['nullable', 'string', 'max:100'],
            'health_records.*.doctor_name' => ['nullable', 'string', 'max:255'],
            'health_records.*.hospital' => ['nullable', 'string', 'max:255'],
            'health_records.*.notes' => ['nullable', 'string'],
            'health_records.*.attachments' => ['nullable', 'array'],
        ];
    }
}

