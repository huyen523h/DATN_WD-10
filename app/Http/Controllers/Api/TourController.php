<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TourController extends Controller
{
    /**
     * Display a listing of tours with filters
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Tour::query();

            // Filter by location
            if ($request->has('location') && $request->location) {
                $query->byLocation($request->location);
            }

            // Filter by price range
            if ($request->has('min_price') || $request->has('max_price')) {
                $query->byPriceRange($request->min_price, $request->max_price);
            }

            // Filter by availability
            if ($request->has('available') && $request->available == 'true') {
                $query->available();
            }

            // Search by title
            if ($request->has('search') && $request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'id');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $tours = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Tours retrieved successfully',
                'data' => $tours,
                'filters_applied' => [
                    'location' => $request->location,
                    'min_price' => $request->min_price,
                    'max_price' => $request->max_price,
                    'available' => $request->available,
                    'search' => $request->search,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified tour with detailed information
     */
    public function show($id): JsonResponse
    {
        try {
            $tour = Tour::with('category')->find($id);

            if (!$tour) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tour not found',
                ], 404);
            }

            // Prepare detailed tour information
            $tourDetails = [
                'id' => $tour->id,
                'title' => $tour->title,
                'short_description' => $tour->short_description,
                'description' => $tour->description,
                'location' => $tour->location,
                'duration' => $tour->duration,
                'available_seats' => $tour->available_seats,
                'departure_date' => $tour->departure_date,
                'formatted_departure_date' => $tour->formatted_departure_date,
                'price' => $tour->price,
                'formatted_price' => $tour->formatted_price,
                'image' => $tour->image,
                'image_url' => $tour->image_url,
                'status' => $tour->status,
                'category' => $tour->category,
                'id' => $tour->id,
                
                // Additional computed fields
                'is_available' => $tour->available_seats > 0 && 
                                 ($tour->departure_date === null || $tour->departure_date->isFuture()),
                'days_until_departure' => $tour->departure_date ? 
                                        now()->diffInDays($tour->departure_date, false) : null,
                'price_per_day' => $tour->duration > 0 ? 
                                  round($tour->price / $tour->duration, 2) : null,
                
                // Services and itinerary (parsed from description)
                'services' => $this->parseServices($tour->description),
                'itinerary' => $this->parseItinerary($tour->description),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Tour details retrieved successfully',
                'data' => $tourDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tour details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tours by location
     */
    public function getByLocation($location): JsonResponse
    {
        try {
            $tours = Tour::byLocation($location)
                        ->available()
                        ->orderBy('price', 'asc')
                        ->get();

            return response()->json([
                'success' => true,
                'message' => "Tours in {$location} retrieved successfully",
                'data' => $tours,
                'count' => $tours->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tours by location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured/popular tours
     */
    public function getFeatured(): JsonResponse
    {
        try {
            $tours = Tour::available()
                        ->orderBy('id', 'desc')
                        ->limit(6)
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'Featured tours retrieved successfully',
                'data' => $tours
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving featured tours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse services from description
     */
    private function parseServices($description): array
    {
        if (!$description) return [];
        
        $services = [];
        
        // Look for common service keywords
        $serviceKeywords = [
            'khách sạn' => 'Khách sạn',
            'ăn sáng' => 'Ăn sáng',
            'ăn trưa' => 'Ăn trưa', 
            'ăn tối' => 'Ăn tối',
            'xe đưa đón' => 'Xe đưa đón',
            'hướng dẫn viên' => 'Hướng dẫn viên',
            'vé tham quan' => 'Vé tham quan',
            'bảo hiểm' => 'Bảo hiểm du lịch'
        ];

        foreach ($serviceKeywords as $keyword => $service) {
            if (stripos($description, $keyword) !== false) {
                $services[] = $service;
            }
        }

        return array_unique($services);
    }

    /**
     * Parse itinerary from description
     */
    private function parseItinerary($description): array
    {
        if (!$description) return [];
        
        $itinerary = [];
        
        // Split by common day indicators
        $dayPatterns = [
            '/Ngày (\d+):(.*?)(?=Ngày \d+:|$)/si',
            '/Day (\d+):(.*?)(?=Day \d+:|$)/si'
        ];
        
        foreach ($dayPatterns as $pattern) {
            if (preg_match_all($pattern, $description, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $itinerary[] = [
                        'day' => (int)$match[1],
                        'activities' => trim($match[2])
                    ];
                }
                break;
            }
        }
        
        // If no day patterns found, return description as single day
        if (empty($itinerary)) {
            $itinerary[] = [
                'day' => 1,
                'activities' => $description
            ];
        }
        
        return $itinerary;
    }
}
