@extends('layouts.app')

@section('title', 'Nhật ký tour')

@section('content')

<style>
    .log-dashboard {
        padding: 2rem;
    }

    .log-header {
        margin-bottom: 2rem;
    }

    .log-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .log-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 25px rgba(0,0,0,.06);
        padding: 1.5rem;
        transition: .25s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .log-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 40px rgba(0,0,0,.1);
    }

    .log-title {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: .5rem;
    }

    .log-meta {
        color: #6B7280;
        font-size: .9rem;
        margin-bottom: 1rem;
    }

    .log-badge {
        display: inline-block;
        background: #E0F2FE;
        color: #0284C7;
        font-weight: 600;
        padding: .35rem .75rem;
        border-radius: 999px;
        font-size: .8rem;
        margin-bottom: 1rem;
    }

    .log-action {
        margin-top: auto;
    }

    .log-btn {
        display: inline-block;
        width: 100%;
        text-align: center;
        background: #0EA5E9;
        color: white;
        padding: .6rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .log-btn:hover {
        background: #0284C7;
        color: white;
    }

    .empty-state {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 25px rgba(0,0,0,.06);
        padding: 3rem;
        text-align: center;
        color: #6B7280;
        font-size: 1.05rem;
    }
</style>

<div class="log-dashboard">

    <!-- HEADER -->
    <div class="log-header">
        <h2 class="fw-bold text-primary">
            📘 Nhật ký tour
        </h2>
        <p class="text-muted">
            Danh sách các tour bạn đã ghi nhật ký
        </p>
    </div>

    <!-- CONTENT -->
    @if ($departures->count())
        <div class="log-grid">
            @foreach ($departures as $departure)
                <div class="log-card">

                    <div>
                        <div class="log-title">
                            {{ $departure->tour->title }}
                        </div>

                        <div class="log-meta">
                            📅 Ngày khởi hành:
                            <strong>{{ $departure->departure_date->format('d/m/Y') }}</strong>
                        </div>

                        <div class="log-badge">
                            {{ $departure->logs_count }} nhật ký
                        </div>
                    </div>

                    <div class="log-action">
                        <a href="{{ route('guide.tour-logs.index', $departure->id) }}"
                           class="log-btn">
                            Xem nhật ký
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            📭 Bạn chưa có tour nào được ghi nhật ký
        </div>
    @endif

</div>

@endsection
