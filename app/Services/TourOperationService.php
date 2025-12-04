<?php

namespace App\Services;

use App\Models\OperationService;
use App\Models\OperationStaffAssignment;
use App\Models\TourOperation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TourOperationService
{
    public function __construct(
        protected OperationNotificationService $notificationService
    ) {
    }

    public function create(array $data): TourOperation
    {
        return DB::transaction(function () use ($data) {
            $operation = TourOperation::create([
                'tour_id' => $data['tour_id'],
                'tour_departure_id' => $data['tour_departure_id'],
                'operation_code' => $data['operation_code'],
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'] ?? null,
                'meeting_point' => $data['meeting_point'] ?? null,
                'status' => $data['status'] ?? 'planning',
                'notes' => $data['notes'] ?? null,
                'itinerary_snapshot' => $data['itinerary_snapshot'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $this->syncStaff($operation, $data['staff_assignments'] ?? []);
            $this->syncServices($operation, $data['services'] ?? []);

            return $operation->load(['staffAssignments', 'services']);
        });
    }

    public function update(TourOperation $operation, array $data): TourOperation
    {
        return DB::transaction(function () use ($operation, $data) {
            $operation->update([
                'tour_id' => $data['tour_id'] ?? $operation->tour_id,
                'tour_departure_id' => $data['tour_departure_id'] ?? $operation->tour_departure_id,
                'operation_code' => $data['operation_code'] ?? $operation->operation_code,
                'start_datetime' => $data['start_datetime'] ?? $operation->start_datetime,
                'end_datetime' => $data['end_datetime'] ?? $operation->end_datetime,
                'meeting_point' => $data['meeting_point'] ?? $operation->meeting_point,
                'status' => $data['status'] ?? $operation->status,
                'notes' => $data['notes'] ?? $operation->notes,
                'itinerary_snapshot' => $data['itinerary_snapshot'] ?? $operation->itinerary_snapshot,
                'updated_by' => auth()->id(),
            ]);

            if (array_key_exists('staff_assignments', $data)) {
                $this->syncStaff($operation, $data['staff_assignments']);
            }

            if (array_key_exists('services', $data)) {
                $this->syncServices($operation, $data['services']);
            }

            return $operation->load(['staffAssignments', 'services']);
        });
    }

    public function assignStaff(
        TourOperation $operation,
        array $payload
    ): OperationStaffAssignment {
        $assignment = $operation->staffAssignments()->create([
            'guide_id' => $payload['guide_id'] ?? null,
            'user_id' => $payload['user_id'] ?? null,
            'external_name' => $payload['external_name'] ?? null,
            'role' => $payload['role'],
            'assignment_type' => $payload['assignment_type'],
            'status' => $payload['status'] ?? 'pending',
            'notes' => $payload['notes'] ?? null,
            'metadata' => $payload['metadata'] ?? null,
        ]);

        $this->notificationService->notifyStaffAssignment(array_merge(
            $payload,
            [
                'operation_code' => $operation->operation_code,
                'user_id' => $assignment->user_id,
                'tour_operation_id' => $operation->id,
            ]
        ));

        return $assignment;
    }

    public function addService(
        TourOperation $operation,
        array $payload
    ): OperationService {
        $service = $operation->services()->create($payload);

        if (!empty($payload['provider_name']) || !empty($payload['contact_email'])) {
            $this->notificationService->notifyServiceProvider(
                $payload['provider_name'] ?? '',
                $payload['contact_email'] ?? '',
                array_merge($payload, ['operation_code' => $operation->operation_code])
            );
        }

        return $service;
    }

    protected function syncStaff(TourOperation $operation, array $rows): Collection
    {
        $operation->staffAssignments()->delete();

        $assignments = collect();
        foreach ($rows as $row) {
            $assignments->push($this->assignStaff($operation, $row));
        }

        return $assignments;
    }

    protected function syncServices(TourOperation $operation, array $rows): Collection
    {
        $operation->services()->delete();

        $services = collect();
        foreach ($rows as $row) {
            $services->push($this->addService($operation, $row));
        }

        return $services;
    }
}

