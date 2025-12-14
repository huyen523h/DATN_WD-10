<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourScheduleResource extends JsonResource
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
            'day_number' => $this->day_number,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_time' => $this->start_time ? $this->start_time->format('H:i') : null,
            'end_time' => $this->end_time ? $this->end_time->format('H:i') : null,
            'meeting_point' => $this->meeting_point,
            'activities' => $this->activities,
            'meals' => $this->meals,
            'accommodation' => $this->accommodation,
            'transportation' => $this->transportation,
            'notes' => $this->notes,
            'images' => $this->images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}