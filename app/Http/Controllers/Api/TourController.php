<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TourIndexRequest;
use App\Models\Tour;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class TourController extends Controller
{
    public function index(TourIndexRequest $request): JsonResponse
    {
        $params = $request->validatedWithDefaults();
        $query = Tour::with(['category', 'images', 'reviews']);
        if (!empty($params['search'])) {
            $query->search($params['search']);
        }
        if (!empty($params['category_id'])) {
            $query->byCategory($params['category_id']);
        }
        if (!empty($params['min_price']) || !empty($params['max_price'])) {
            $minPrice = $params['min_price'] ?? 0;
            $maxPrice = $params['max_price'] ?? PHP_FLOAT_MAX;
            $query->byPriceRange($minPrice, $maxPrice);
        }
        if (!empty($params['location'])) {
            $query->byLocation($params['location']);
        }
        if ($params['sort_by'] === 'price') {
            $query->sortByPrice($params['sort_direction']);
        } elseif ($params['sort_by'] === 'title') {
            $query->orderBy('title', $params['sort_direction']);
        } else {
            $query->sortByDate($params['sort_direction']);
        }
        $tours = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);
        $transformedTours = $tours->through(function ($tour) {
            return $this->transformTour($tour);
        });

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách tour thành công.',
            'data' => [
                'tours' => $transformedTours->items(),
                'pagination' => [
                    'current_page' => $transformedTours->currentPage(),
                    'last_page' => $transformedTours->lastPage(),
                    'per_page' => $transformedTours->perPage(),
                    'total' => $transformedTours->total(),
                    'from' => $transformedTours->firstItem(),
                    'to' => $transformedTours->lastItem(),
                    'has_more_pages' => $transformedTours->hasMorePages(),
                ],
                'filters_applied' => [
                    'search' => $params['search'],
                    'category_id' => $params['category_id'],
                    'min_price' => $params['min_price'],
                    'max_price' => $params['max_price'],
                    'location' => $params['location'],
                    'sort_by' => $params['sort_by'],
                    'sort_direction' => $params['sort_direction'],
                ]
            ]
        ]);
    }

    public function show(Tour $tour): JsonResponse
    {
        $tour->load(['category', 'images', 'reviews.user', 'schedules', 'departures']);

        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin tour thành công.',
            'data' => [
                'tour' => $this->transformTourDetail($tour)
            ]
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::withCount('tours')->get();

        $transformedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'tours_count' => $category->tours_count,
                'created_at' => $category->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $category->updated_at?->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công.',
            'data' => [
                'categories' => $transformedCategories
            ]
        ]);
    }

    private function transformTour(Tour $tour): array
    {
        return [
            'id' => $tour->id,
            'title' => $tour->title,
            'short_description' => $tour->short_description,
            'description' => $tour->description,
            'price' => (float) $tour->price,
            'price_formatted' => number_format((float) $tour->price, 0, ',', '.') . ' ₫',
            'location' => $tour->location,
            'duration' => $tour->duration,
            'available_seats' => $tour->available_seats,
            'main_image' => $tour->main_image,
            'average_rating' => round($tour->average_rating, 1),
            'reviews_count' => $tour->reviews_count,
            'category' => $tour->category ? [
                'id' => $tour->category->id,
                'name' => $tour->category->name,
            ] : null,
            'created_at' => $tour->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $tour->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function transformTourDetail(Tour $tour): array
    {
        return [
            'id' => $tour->id,
            'title' => $tour->title,
            'short_description' => $tour->short_description,
            'description' => $tour->description,
            'price' => (float) $tour->price,
            'price_formatted' => number_format((float) $tour->price, 0, ',', '.') . ' ₫',
            'location' => $tour->location,
            'duration' => $tour->duration,
            'available_seats' => $tour->available_seats,
            'average_rating' => round($tour->average_rating, 1),
            'reviews_count' => $tour->reviews_count,
            'category' => $tour->category ? [
                'id' => $tour->category->id,
                'name' => $tour->category->name,
                'description' => $tour->category->description,
            ] : null,
            'images' => $tour->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => $image->image_url,
                    'is_main' => $image->is_main,
                ];
            }),
            'schedules' => $tour->schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'day' => $schedule->day,
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                ];
            }),
            'departures' => $tour->departures->map(function ($departure) {
                return [
                    'id' => $departure->id,
                    'departure_date' => $departure->departure_date?->format('Y-m-d'),
                    'return_date' => $departure->return_date?->format('Y-m-d'),
                    'available_seats' => $departure->available_seats,
                    'status' => $departure->status,
                ];
            }),
            'reviews' => $tour->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                    ],
                    'created_at' => $review->created_at?->format('Y-m-d H:i:s'),
                ];
            }),
            'created_at' => $tour->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $tour->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
