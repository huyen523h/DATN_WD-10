<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistsController extends Controller
{
    /**
     * Display a listing of the user's wishlist.
     */
    public function index()
    {
        $user = Auth::user();

        // Lấy wishlist kèm tour và hình ảnh
        $wishlists = Wishlist::with('tour.images')
            ->where('user_id', $user->id)
            ->get();

        // Trả về view Blade (chứ không phải JSON)
        return view('wishlists.index', compact('wishlists'));
    }

    /**
     * Store a newly created wishlist item.
     */
    // public function store(Request $request)
    // {
    //     $user = Auth::user();

    //     $validated = $request->validate([
    //         'tour_id' => 'required|exists:tours,id',
    //     ]);

    //     // Check if already added
    //     $exists = Wishlist::where('user_id', $user->id)
    //         ->where('tour_id', $validated['tour_id'])
    //         ->exists();

    //     if ($exists) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Tour này đã có trong wishlist.'
    //         ], 409);
    //     }

    //     $wishlist = Wishlist::create([
    //         'user_id' => $user->id,
    //         'tour_id' => $validated['tour_id']
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Đã thêm vào wishlist.',
    //         'data' => $wishlist
    //     ]);
    // }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
        ]);

        $exists = Wishlist::where('user_id', $user->id)
            ->where('tour_id', $validated['tour_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('wishlists.index')
                ->with('error', 'Tour này đã có trong danh sách yêu thích.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'tour_id' => $validated['tour_id']
        ]);

        return redirect()->route('wishlists.index')
            ->with('success', 'Đã thêm vào danh sách yêu thích.');
    }


    /**
     * Display a specific wishlist item.
     */
    public function show($id)
    {
        $wishlist = Wishlist::with('tour')->find($id);

        if (!$wishlist) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy wishlist item.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $wishlist
        ]);
    }

    /**
     * Remove the specified item from wishlist.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy wishlist item.'
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa khỏi wishlist.'
        ]);
    }
}
