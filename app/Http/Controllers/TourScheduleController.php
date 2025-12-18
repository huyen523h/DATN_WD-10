<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\TourDeparture;
use App\Models\User;
use App\Http\Resources\TourScheduleResource;
use App\Http\Resources\TourDepartureDetailResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TourScheduleController extends Controller
{
    /**
     * Lấy lịch trình chi tiết của tour
     */
    public function getScheduleDetails(Request $request, $tourId): JsonResponse
    {
        try {
            $tour = Tour::with(['schedules' => function($query) {
                $query->orderBy('day_number');
            }])->findOrFail($tourId);

            // Lấy thông tin departure nếu có departure_id
            $departure = null;
            if ($request->has('departure_id')) {
                $departure = TourDeparture::with(['guide', 'backupGuide'])
                    ->where('tour_id', $tourId)
                    ->where('id', $request->departure_id)
                    ->first();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tour' => $tour,
                    'schedules' => TourScheduleResource::collection($tour->schedules),
                    'departure' => $departure ? new TourDepartureDetailResource($departure) : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin lịch trình: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách hướng dẫn viên có sẵn
     */
    public function getAvailableGuides(Request $request): JsonResponse
    {
        try {
            $guides = User::where('role', 'guide')
                ->select('id', 'name', 'email', 'phone')
                ->get();

            // Nếu có ngày cụ thể, thêm thông tin về xung đột lịch trình
            if ($request->has('date') && $request->date) {
                $excludeDepartureId = $request->get('exclude_departure_id');
                
                $guides->each(function($guide) use ($request, $excludeDepartureId) {
                    $conflictsQuery = TourDeparture::where('departure_date', $request->date)
                        ->where(function($query) use ($guide) {
                            $query->where('guide_id', $guide->id)
                                  ->orWhere('backup_guide_id', $guide->id);
                        });
                    
                    if ($excludeDepartureId) {
                        $conflictsQuery->where('id', '!=', $excludeDepartureId);
                    }
                    
                    $conflicts = $conflictsQuery->with('tour')->get();
                    
                    $guide->is_available = $conflicts->isEmpty();
                    $guide->conflicts = $conflicts->map(function($departure) use ($guide) {
                        return [
                            'departure_id' => $departure->id,
                            'tour_title' => $departure->tour->title ?? 'N/A',
                            'role' => $departure->guide_id == $guide->id ? 'Chính' : 'Dự phòng'
                        ];
                    })->toArray();
                });
            } else {
                // Nếu không có ngày, tất cả HDV đều available
                $guides->each(function($guide) {
                    $guide->is_available = true;
                    $guide->conflicts = [];
                });
            }

            return response()->json([
                'success' => true,
                'data' => $guides
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách hướng dẫn viên: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin departure cụ thể
     */
    public function getDeparture($id): JsonResponse
    {
        try {
            $departure = TourDeparture::with(['guide', 'backupGuide', 'tour'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new TourDepartureDetailResource($departure)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin khởi hành: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin departure
     */
    public function updateDeparture(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'departure_date' => 'nullable|date',
                'departure_time' => 'nullable|date_format:H:i',
                'departure_location' => 'nullable|string|max:500',
                'departure_instructions' => 'nullable|string',
                'guide_id' => 'nullable|exists:users,id',
                'backup_guide_id' => 'nullable|exists:users,id',
                'emergency_contact' => 'nullable|string|max:255',
                'emergency_phone' => 'nullable|string|max:20',
                'preparation_status' => 'nullable|in:pending,ready,confirmed,cancelled,draft',
                'seats_total' => 'nullable|integer|min:1|max:100',
                'special_notes' => 'nullable|string'
            ]);

            $departure = TourDeparture::findOrFail($id);
            
            // CRITICAL: Validate guide assignments first
            if ($request->has('guide_id') && $request->has('backup_guide_id') && 
                $request->guide_id && $request->backup_guide_id && 
                $request->guide_id == $request->backup_guide_id) {
                
                \Log::warning("Attempted to assign same guide for both roles", [
                    'departure_id' => $id,
                    'guide_id' => $request->guide_id,
                    'backup_guide_id' => $request->backup_guide_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Hướng dẫn viên chính và dự phòng không thể là cùng một người'
                ], 422);
            }
            
            // Also check if trying to assign same guide when only one field is provided
            if ($request->has('guide_id') && $request->guide_id && 
                !$request->has('backup_guide_id') && $departure->backup_guide_id && 
                $request->guide_id == $departure->backup_guide_id) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'HDV này đã được gán làm HDV dự phòng cho departure này'
                ], 422);
            }
            
            if ($request->has('backup_guide_id') && $request->backup_guide_id && 
                !$request->has('guide_id') && $departure->guide_id && 
                $request->backup_guide_id == $departure->guide_id) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'HDV này đã được gán làm HDV chính cho departure này'
                ], 422);
            }

            // Check for guide conflicts on the same date
            $checkDate = $request->departure_date ?: $departure->departure_date;
            
            \Log::info("Checking guide conflicts for departure {$id} on date {$checkDate}", [
                'guide_id' => $request->guide_id,
                'backup_guide_id' => $request->backup_guide_id
            ]);
            
            if ($request->guide_id) {
                $conflictingDeparture = TourDeparture::where('departure_date', $checkDate)
                    ->where('id', '!=', $id)
                    ->where(function($query) use ($request) {
                        $query->where('guide_id', $request->guide_id)
                              ->orWhere('backup_guide_id', $request->guide_id);
                    })
                    ->with('tour')
                    ->first();
                
                \Log::info("Main guide conflict check", [
                    'guide_id' => $request->guide_id,
                    'conflicting_departure' => $conflictingDeparture ? $conflictingDeparture->id : null
                ]);
                
                if ($conflictingDeparture) {
                    $guideName = \App\Models\User::find($request->guide_id)->name ?? 'HDV';
                    $tourTitle = $conflictingDeparture->tour->title ?? 'N/A';
                    
                    \Log::warning("Guide conflict detected", [
                        'guide_id' => $request->guide_id,
                        'guide_name' => $guideName,
                        'conflicting_departure_id' => $conflictingDeparture->id,
                        'tour_title' => $tourTitle
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "HDV {$guideName} đã được gán cho tour khác vào ngày {$checkDate} (Tour: {$tourTitle})"
                    ], 422);
                }
            }
            
            if ($request->backup_guide_id) {
                $conflictingDeparture = TourDeparture::where('departure_date', $checkDate)
                    ->where('id', '!=', $id)
                    ->where(function($query) use ($request) {
                        $query->where('guide_id', $request->backup_guide_id)
                              ->orWhere('backup_guide_id', $request->backup_guide_id);
                    })
                    ->with('tour')
                    ->first();
                
                \Log::info("Backup guide conflict check", [
                    'backup_guide_id' => $request->backup_guide_id,
                    'conflicting_departure' => $conflictingDeparture ? $conflictingDeparture->id : null
                ]);
                
                if ($conflictingDeparture) {
                    $guideName = \App\Models\User::find($request->backup_guide_id)->name ?? 'HDV';
                    $tourTitle = $conflictingDeparture->tour->title ?? 'N/A';
                    
                    \Log::warning("Backup guide conflict detected", [
                        'backup_guide_id' => $request->backup_guide_id,
                        'guide_name' => $guideName,
                        'conflicting_departure_id' => $conflictingDeparture->id,
                        'tour_title' => $tourTitle
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "HDV {$guideName} đã được gán cho tour khác vào ngày {$checkDate} (Tour: {$tourTitle})"
                    ], 422);
                }
            }

            $departure->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin khởi hành thành công',
                'data' => new TourDepartureDetailResource($departure->fresh(['guide', 'backupGuide', 'tour']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật thông tin khởi hành: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo departure mới
     */
    public function createDeparture(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'tour_id' => 'required|exists:tours,id',
                'departure_date' => 'required|date|after:today',
                'departure_time' => 'nullable|date_format:H:i',
                'departure_location' => 'nullable|string|max:500',
                'departure_instructions' => 'nullable|string',
                'seats_total' => 'nullable|integer|min:1|max:100',
                'price' => 'nullable|numeric|min:0',
                'guide_id' => 'nullable|exists:users,id',
                'backup_guide_id' => 'nullable|exists:users,id',
                'special_notes' => 'nullable|string',
                'preparation_status' => 'nullable|in:pending,ready,confirmed,cancelled,draft'
            ]);

            // CRITICAL: Validate guide assignments first
            if ($request->guide_id && $request->backup_guide_id && $request->guide_id == $request->backup_guide_id) {
                \Log::warning("Attempted to create departure with same guide for both roles", [
                    'tour_id' => $request->tour_id,
                    'guide_id' => $request->guide_id,
                    'backup_guide_id' => $request->backup_guide_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Hướng dẫn viên chính và dự phòng không thể là cùng một người'
                ], 422);
            }

            // Check for guide conflicts on the same date
            if ($request->guide_id) {
                $conflictingDeparture = TourDeparture::where('departure_date', $request->departure_date)
                    ->where(function($query) use ($request) {
                        $query->where('guide_id', $request->guide_id)
                              ->orWhere('backup_guide_id', $request->guide_id);
                    })
                    ->with('tour')
                    ->first();
                
                if ($conflictingDeparture) {
                    $guideName = \App\Models\User::find($request->guide_id)->name ?? 'HDV';
                    $tourTitle = $conflictingDeparture->tour->title ?? 'N/A';
                    return response()->json([
                        'success' => false,
                        'message' => "HDV {$guideName} đã được gán cho tour khác vào ngày {$request->departure_date} (Tour: {$tourTitle})"
                    ], 422);
                }
            }
            
            if ($request->backup_guide_id) {
                $conflictingDeparture = TourDeparture::where('departure_date', $request->departure_date)
                    ->where(function($query) use ($request) {
                        $query->where('guide_id', $request->backup_guide_id)
                              ->orWhere('backup_guide_id', $request->backup_guide_id);
                    })
                    ->with('tour')
                    ->first();
                
                if ($conflictingDeparture) {
                    $guideName = \App\Models\User::find($request->backup_guide_id)->name ?? 'HDV';
                    $tourTitle = $conflictingDeparture->tour->title ?? 'N/A';
                    return response()->json([
                        'success' => false,
                        'message' => "HDV {$guideName} đã được gán cho tour khác vào ngày {$request->departure_date} (Tour: {$tourTitle})"
                    ], 422);
                }
            }

            // Check if departure already exists for this date
            $existingDeparture = TourDeparture::where('tour_id', $request->tour_id)
                ->where('departure_date', $request->departure_date)
                ->first();

            if ($existingDeparture) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có departure cho ngày này. Vui lòng chọn ngày khác.'
                ], 422);
            }

            $departure = TourDeparture::create([
                'tour_id' => $request->tour_id,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'departure_location' => $request->departure_location,
                'departure_instructions' => $request->departure_instructions,
                'seats_total' => $request->seats_total ?: 25,
                'seats_available' => $request->seats_total ?: 25,
                'price' => $request->price,
                'guide_id' => $request->guide_id,
                'backup_guide_id' => $request->backup_guide_id,
                'special_notes' => $request->special_notes,
                'preparation_status' => $request->preparation_status ?: 'pending',
                'status' => 'available'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo departure mới thành công',
                'data' => new TourDepartureDetailResource($departure->fresh(['guide', 'backupGuide', 'tour']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo departure: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lưu departure dưới dạng nháp
     */
    public function saveDepartureAsDraft(Request $request, $id): JsonResponse
    {
        try {
            $departure = TourDeparture::findOrFail($id);
            
            $updateData = $request->all();
            $updateData['preparation_status'] = 'draft';
            
            $departure->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu nháp thành công',
                'data' => new TourDepartureDetailResource($departure->fresh(['guide', 'backupGuide', 'tour']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lưu nháp: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông báo gần đây
     */
    public function getRecentNotifications(): JsonResponse
    {
        try {
            // Giả lập dữ liệu thông báo (có thể thay bằng model Notification thực tế)
            $notifications = [
                [
                    'id' => 1,
                    'title' => 'Cập nhật thành công',
                    'message' => 'Đã cập nhật thông tin khởi hành ID: 42',
                    'type' => 'success',
                    'created_at' => now()->subMinutes(5)->toISOString(),
                    'read_at' => null
                ],
                [
                    'id' => 2,
                    'title' => 'Phân công HDV',
                    'message' => 'Đã gán HDV Nguyễn Văn Hùng cho tour Sapa',
                    'type' => 'guide',
                    'created_at' => now()->subMinutes(15)->toISOString(),
                    'read_at' => null
                ],
                [
                    'id' => 3,
                    'title' => 'Cảnh báo',
                    'message' => 'HDV chính chưa được gán cho chuyến đi ngày mai',
                    'type' => 'warning',
                    'created_at' => now()->subHours(1)->toISOString(),
                    'read_at' => now()->subMinutes(30)->toISOString()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông báo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông báo mới
     */
    public function getNewNotifications(Request $request): JsonResponse
    {
        try {
            $since = $request->get('since', 0);
            
            // Giả lập thông báo mới (trong thực tế sẽ query từ database)
            $newNotifications = [];
            
            // Randomly generate new notifications for demo
            if (rand(1, 10) > 7) { // 30% chance of new notification
                $types = ['info', 'success', 'warning', 'departure', 'guide'];
                $messages = [
                    'info' => 'Hệ thống đã được cập nhật',
                    'success' => 'Đã lưu thay đổi thành công',
                    'warning' => 'Cần kiểm tra thông tin HDV',
                    'departure' => 'Có thay đổi lịch khởi hành',
                    'guide' => 'HDV mới đã được thêm vào hệ thống'
                ];
                
                $type = $types[array_rand($types)];
                $newNotifications[] = [
                    'id' => time(),
                    'title' => ucfirst($type) . ' notification',
                    'message' => $messages[$type],
                    'type' => $type,
                    'created_at' => now()->toISOString(),
                    'read_at' => null
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $newNotifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông báo mới: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả departures của một tour
     */
    public function getTourDepartures($tourId): JsonResponse
    {
        try {
            $departures = TourDeparture::with(['guide', 'backupGuide'])
                ->where('tour_id', $tourId)
                ->orderBy('departure_date')
                ->get();

            return response()->json([
                'success' => true,
                'data' => TourDepartureDetailResource::collection($departures)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách departures: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả departures theo ngày
     */
    public function getDeparturesByDate(Request $request): JsonResponse
    {
        try {
            $date = $request->get('date');
            
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng cung cấp ngày'
                ], 400);
            }

            $departures = TourDeparture::with(['guide', 'backupGuide', 'tour'])
                ->where('departure_date', $date)
                ->orderBy('id')
                ->get();

            return response()->json([
                'success' => true,
                'data' => TourDepartureDetailResource::collection($departures)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách departures: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllNotificationsAsRead(): JsonResponse
    {
        try {
            // Trong thực tế sẽ update database
            // Notification::whereNull('read_at')->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu tất cả thông báo là đã đọc'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể đánh dấu thông báo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo lịch trình mới
     */
    public function createSchedule(Request $request, $tourId): JsonResponse
    {
        try {
            $request->validate([
                'day_number' => 'required|integer|min:1|max:30',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:500',
                'activities' => 'nullable|string',
                'meals' => 'nullable|string'
            ]);

            // Kiểm tra tour tồn tại
            $tour = Tour::findOrFail($tourId);

            // Kiểm tra day_number đã tồn tại chưa
            $existingSchedule = TourSchedule::where('tour_id', $tourId)
                ->where('day_number', $request->day_number)
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ngày ' . $request->day_number . ' đã có lịch trình. Vui lòng chọn ngày khác.'
                ], 422);
            }

            $schedule = TourSchedule::create([
                'tour_id' => $tourId,
                'day_number' => $request->day_number,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'activities' => $request->activities,
                'meals' => $request->meals
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo lịch trình thành công',
                'data' => new TourScheduleResource($schedule)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo lịch trình: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật lịch trình
     */
    public function updateSchedule(Request $request, $tourId, $scheduleId): JsonResponse
    {
        try {
            $request->validate([
                'day_number' => 'required|integer|min:1|max:30',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:500',
                'activities' => 'nullable|string',
                'meals' => 'nullable|string'
            ]);

            $schedule = TourSchedule::where('tour_id', $tourId)
                ->where('id', $scheduleId)
                ->firstOrFail();

            // Kiểm tra day_number conflict (nếu thay đổi)
            if ($schedule->day_number != $request->day_number) {
                $existingSchedule = TourSchedule::where('tour_id', $tourId)
                    ->where('day_number', $request->day_number)
                    ->where('id', '!=', $scheduleId)
                    ->first();

                if ($existingSchedule) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ngày ' . $request->day_number . ' đã có lịch trình khác.'
                    ], 422);
                }
            }

            $schedule->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật lịch trình thành công',
                'data' => new TourScheduleResource($schedule)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật lịch trình: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa lịch trình
     */
    public function deleteSchedule($tourId, $scheduleId): JsonResponse
    {
        try {
            $schedule = TourSchedule::where('tour_id', $tourId)
                ->where('id', $scheduleId)
                ->firstOrFail();

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa lịch trình thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa lịch trình: ' . $e->getMessage()
            ], 500);
        }
    }
}