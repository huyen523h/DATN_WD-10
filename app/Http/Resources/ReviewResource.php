<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Chú thích: Định dạng lại dữ liệu JSON trước khi trả về cho client
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at_human' => $this->created_at->diffForHumans(), // Ví dụ: "2 days ago"
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                // 'avatar' => $this->user->avatar_url, // Nếu user có cột avatar
            ],
            // Chú thích: Chỉ thêm thông tin tour nếu đang ở trang admin
            'tour' => $this->whenLoaded('tour', function () {
                return [
                    'id' => $this->tour->id,
                    'title' => $this->tour->title,
                ];
            }),
        ];
    }
}