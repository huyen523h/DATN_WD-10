<?php

namespace App\Http\Requests\Admin\Operation;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourOperationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'tour_id' => ['required', 'integer', 'exists:tours,id'],
            'tour_departure_id' => ['required', 'integer', 'exists:tour_departures,id'],
            'operation_code' => ['required', 'string', 'max:100', 'unique:tour_operations,operation_code'],
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['nullable', 'date', 'after_or_equal:start_datetime'],
            'meeting_point' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:planning,confirmed,in_progress,completed,cancelled'],
            'notes' => ['nullable', 'string'],
            'itinerary_snapshot' => ['nullable', 'array'],
            'staff_assignments' => ['nullable', 'array'],
            'staff_assignments.*.guide_id' => ['nullable', 'integer', 'exists:guides,id'],
            'staff_assignments.*.user_id' => ['nullable', 'integer', 'exists:users,id'],
            'staff_assignments.*.external_name' => ['nullable', 'string', 'max:255'],
            'staff_assignments.*.role' => ['required_with:staff_assignments', 'string', 'max:50'],
            'staff_assignments.*.assignment_type' => ['nullable', 'in:internal,external'],
            'staff_assignments.*.status' => ['nullable', 'in:pending,notified,confirmed,declined'],
            'staff_assignments.*.notes' => ['nullable', 'string'],
            'services' => ['nullable', 'array'],
            'services.*.service_type' => ['required_with:services', 'string', 'max:50'],
            'services.*.provider_name' => ['nullable', 'string', 'max:255'],
            'services.*.contact_person' => ['nullable', 'string', 'max:255'],
            'services.*.contact_phone' => ['nullable', 'string', 'max:50'],
            'services.*.contact_email' => ['nullable', 'email', 'max:255'],
            'services.*.booking_code' => ['nullable', 'string', 'max:100'],
            'services.*.quantity' => ['nullable', 'integer', 'min:0'],
            'services.*.cost' => ['nullable', 'numeric', 'min:0'],
            'services.*.status' => ['nullable', 'in:draft,requested,confirmed,cancelled'],
            'services.*.confirmation_deadline' => ['nullable', 'date'],
            'services.*.requirements' => ['nullable', 'string'],
            'services.*.notes' => ['nullable', 'string'],
        ];
    }
}

