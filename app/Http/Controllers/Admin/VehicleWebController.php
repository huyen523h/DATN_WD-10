<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\TourDeparture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleWebController extends Controller
{
    /**
     * Danh sách xe.
     */
    public function index(Request $request): View
    {
        $vehicles = Vehicle::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->string('keyword')->toString();
                $query->where(function ($q) use ($keyword) {
                    $q->where('license_plate', 'like', "%{$keyword}%")
                        ->orWhere('brand', 'like', "%{$keyword}%")
                        ->orWhere('vehicle_type', 'like', "%{$keyword}%")
                        ->orWhere('driver_name', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('vehicle_type'), fn($q) => $q->where('vehicle_type', $request->string('vehicle_type')))
            ->orderBy('license_plate')
            ->paginate(10)
            ->appends($request->only(['keyword', 'status', 'vehicle_type']));

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Form tạo xe.
     */
    public function create(): View
    {
        return view('admin.vehicles.create', [
            'vehicle' => new Vehicle(),
        ]);
    }

    /**
     * Lưu xe mới.
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'license_plate.required' => 'Biển số xe là bắt buộc.',
            'license_plate.max' => 'Biển số xe không được dài quá 50 ký tự.',
            'license_plate.unique' => 'Biển số xe này đã tồn tại trong hệ thống.',

            'vehicle_type.required' => 'Vui lòng chọn loại xe.',

            'brand.max' => 'Hãng xe không được dài quá 100 ký tự.',

            'year.integer' => 'Năm sản xuất phải là số.',
            'year.min' => 'Năm sản xuất không hợp lệ.',
            'year.max' => 'Năm sản xuất không được lớn hơn năm hiện tại.',

            'color.max' => 'Màu xe không được dài quá 50 ký tự.',

            'status.required' => 'Vui lòng chọn trạng thái của xe.',
            'status.in' => 'Trạng thái xe không hợp lệ.',

            'driver_name.max' => 'Tên tài xế không được dài quá 255 ký tự.',
            'driver_phone.max' => 'Số điện thoại tài xế không được dài quá 50 ký tự.',
        ];

        $data = $request->validate([
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
            'vehicle_type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'status' => 'required|in:1,2,0',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ], $messages);

        // Đảm bảo status là integer
        $data['status'] = (int)$data['status'];

        Vehicle::create($data);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Xe đã được tạo thành công.');
    }

    /**
     * Form chỉnh sửa xe.
     */
    public function edit(Vehicle $vehicle): View
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Xem chi tiết xe + các lịch khởi hành đã được gán.
     */
    public function show(Vehicle $vehicle): View
    {
        // Lấy các lịch khởi hành đã gán xe này (chia tương lai / quá khứ)
        $futureDepartures = TourDeparture::with('tour')
            ->where('vehicle_id', $vehicle->id)
            ->whereDate('departure_date', '>=', now()->toDateString())
            ->orderBy('departure_date')
            ->limit(50)
            ->get();

        $pastDepartures = TourDeparture::with('tour')
            ->where('vehicle_id', $vehicle->id)
            ->whereDate('departure_date', '<', now()->toDateString())
            ->orderByDesc('departure_date')
            ->limit(50)
            ->get();

        return view('admin.vehicles.show', compact('vehicle', 'futureDepartures', 'pastDepartures'));
    }

    /**
     * Cập nhật xe.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $messages = [
            'license_plate.required' => 'Biển số xe là bắt buộc.',
            'license_plate.max' => 'Biển số xe không được dài quá 50 ký tự.',
            'license_plate.unique' => 'Biển số xe này đã tồn tại trong hệ thống.',

            'vehicle_type.required' => 'Vui lòng chọn loại xe.',

            'brand.max' => 'Hãng xe không được dài quá 100 ký tự.',

            'year.integer' => 'Năm sản xuất phải là số.',
            'year.min' => 'Năm sản xuất không hợp lệ.',
            'year.max' => 'Năm sản xuất không được lớn hơn năm hiện tại.',

            'color.max' => 'Màu xe không được dài quá 50 ký tự.',

            'status.required' => 'Vui lòng chọn trạng thái của xe.',
            'status.in' => 'Trạng thái xe không hợp lệ.',

            'driver_name.max' => 'Tên tài xế không được dài quá 255 ký tự.',
            'driver_phone.max' => 'Số điện thoại tài xế không được dài quá 50 ký tự.',
        ];

        $data = $request->validate([
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
            'vehicle_type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            'status' => 'required|in:1,2,0',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ], $messages);

        // Đảm bảo status là integer
        $data['status'] = (int)$data['status'];

        $vehicle->update($data);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Xe đã được cập nhật thành công.');
    }

    /**
     * Xoá xe.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Xe đã được xóa thành công.');
    }
}


