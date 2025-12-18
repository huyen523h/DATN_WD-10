<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourDepartureDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tour_id' => $this->tour_id,
            'departure_date' => $this->departure_date->format('Y-m-d'),
            'departure_time' => $this->departure_time ? $this->departure_time->format('H:i') : null,
            'departure_location' => $this->departure_location,
            'departure_instructions' => $this->departure_instructions,
            'seats_total' => $this->seats_total,
            'seats_available' => $this->seats_available,
            'price' => $this->price,
            'child_price' => $this->child_price,
            'infant_price' => $this->infant_price,
            'status' => $this->status,
            'preparation_status' => $this->preparation_status,
            'emergency_contact' => $this->emergency_contact,
            'emergency_phone' => $this->emergency_phone,
            'special_notes' => $this->special_notes,
            'vehicle_details' => $this->vehicle_details,
            'driver_contact' => $this->driver_contact,
            'guide' => $this->whenLoaded('guide', function () {
                return [
                    'id' => $this->guide->id,
                    'name' => $this->guide->name,
                    'phone' => $this->guide->phone,
                    'email' => $this->guide->email,
                ];
            }),
            'backup_guide' => $this->whenLoaded('backupGuide', function () {
                return [
                    'id' => $this->backupGuide->id,
                    'name' => $this->backupGuide->name,
                    'phone' => $this->backupGuide->phone,
                    'email' => $this->backupGuide->email,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}