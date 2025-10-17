@extends('layouts.app')

@section('title', 'Danh sách yêu thích - Tour365')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-heart"></i> Danh sách yêu thích</h2>
                <a href="{{ route('tours.index') }}" class="btn btn-primary">
                    <i class="fas fa-search"></i> Khám phá thêm tour
                </a>
            </div>

            @if($wishlists->count() > 0)
                <div class="row">
                    @foreach($wishlists as $wishlist)
                        @php $tour = $wishlist->tour; @endphp
                        @if($tour)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if($tour->images->count() > 0)
                                                <img src="{{ $tour->images->first()->image_url }}" 
                                                     class="img-fluid rounded" alt="{{ $tour->title }}"
                                                     style="height: 150px; object-fit: cover; width: 100%;">
                                            @else
                                                <img src="https://via.placeholder.com/300x150/4F46E5/ffffff?text={{ urlencode($tour->title) }}" 
                                                     class="img-fluid rounded" alt="{{ $tour->title }}"
                                                     style="height: 150px; object-fit: cover; width: 100%;">
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-2">{{ $tour->title }}</h5>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-map-marker-alt"></i> {{ $tour->destination ?? 'Không xác định' }}
                                            </p>
                                            <p class="text-muted mb-3">
                                                <i class="fas fa-clock"></i> {{ $tour->duration ?? 'N/A' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="h5 text-primary mb-0">
                                                    {{ number_format($tour->price ?? 0, 0, ',', '.') }}đ
                                                </div>
                                                <form action="{{ route('wishlists.destroy', $wishlist->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm" title="Xóa khỏi yêu thích">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <small class="text-muted">
                                        Thêm vào lúc: {{ $wishlist->created_at->format('d/m/Y H:i') }}
                                    </small>
                                    <a href="{{ route('tours.show', $tour->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heart-broken fa-4x text-muted mb-4"></i>
                    <h4>Danh sách yêu thích trống</h4>
                    <p class="text-muted mb-4">Bạn chưa thêm tour nào vào danh sách yêu thích.</p>
                    <a href="{{ route('tours.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Xem tours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
