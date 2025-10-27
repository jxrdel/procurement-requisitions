@extends('layout')

@section('title')
    <title>Notification Details | PRA</title>
@endsection

@section('content')
    <div class="container-fluid px-4 py-4">
        {{-- Main Notification Card --}}
        <div class="row justify-content-center">
            <div class="col">
                <div class="card border-0 shadow-sm">
                    {{-- Card Header with Back Button and Title --}}
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center mb-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-arrow-left-line me-1"></i> Back
                            </a>
                            <h4 class="mb-0 flex-grow-1 text-center">Notification Details</h4>
                            <div style="width: 80px;"></div> {{-- Spacer to balance the layout --}}
                        </div>

                        {{-- Divider --}}
                        <hr class="my-3">

                        {{-- Notification Title with Icon --}}
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-opacity-10 p-3 me-3">
                                <i class="fa-solid fa-bell fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-semibold">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h5>
                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    {{ $notification->created_at->format('F j, Y, g:i A') }}
                                    <span class="ms-2">({{ $notification->created_at->diffForHumans() }})</span>
                                </small>
                            </div>
                            @if ($notification->read_at === null)
                                <span class="badge bg-primary rounded-pill">New</span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body with Message --}}
                    <div class="card-body p-4">
                        <div class="notification-message mb-4">
                            <p class="fs-5 text-dark mb-0" style="line-height: 1.6;">
                                {{ $notification->data['message'] ?? 'No details available.' }}
                            </p>
                        </div>

                        {{-- Action Button --}}
                        @if (!empty($notification->data['url']))
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                <a href="{{ $notification->data['url'] }}" class="btn btn-primary px-4">
                                    <i class="ri-eye-line me-2"></i> View
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer bg-light border-top py-3">
                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <span>
                                <i class="ri-notification-line me-1"></i>
                                Notification ID: {{ $notification->id }}
                            </span>
                            @if ($notification->read_at)
                                <span>
                                    <i class="ri-check-double-line me-1"></i>
                                    Read on {{ $notification->read_at->format('M j, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .notification-message {
            position: relative;
            padding-left: 1rem;
            border-left: 4px solid #0d6efd;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-outline-secondary:hover {
            transform: translateX(-3px);
            transition: transform 0.2s ease;
        }
    </style>
@endsection
