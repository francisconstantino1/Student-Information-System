{{-- 
    Reusable Modal Component
    
    Usage:
    @include('layouts.modal', [
        'id' => 'my-modal',
        'title' => 'Modal Title',
        'subtitle' => 'Optional subtitle',
        'type' => 'view|edit|add|delete', // Optional, affects styling
        'size' => 'small|medium|large', // Optional, defaults to medium
    ])
--}}

@php
    $modalId = $id ?? 'modal';
    $modalTitle = $title ?? 'Modal';
    $modalSubtitle = $subtitle ?? '';
    $modalType = $type ?? 'default';
    $modalSize = $size ?? 'medium';
    
    $sizeClasses = [
        'small' => 'max-w-md',
        'medium' => 'max-w-2xl',
        'large' => 'max-w-4xl',
        'xlarge' => 'max-w-6xl',
    ];
    
    $maxWidth = $sizeClasses[$modalSize] ?? $sizeClasses['medium'];
@endphp

<div class="modal" id="{{ $modalId }}">
    <div class="modal-content" style="max-width: {{ $maxWidth === 'max-w-md' ? '500px' : ($maxWidth === 'max-w-2xl' ? '800px' : ($maxWidth === 'max-w-4xl' ? '1000px' : '1200px')) }};">
        <div class="form-header">
            @if(isset($institution))
                <h3>{{ $institution['name'] ?? 'Student Information System' }}</h3>
                <p>{{ $institution['address'] ?? '' }}</p>
            @endif
            <h4>{{ $modalTitle }}</h4>
            @if($modalSubtitle)
                <p style="margin-top: 8px; color: #6b7280;">{{ $modalSubtitle }}</p>
            @endif
        </div>

        {{ $slot ?? '' }}

        @if(isset($footer))
            <div class="modal-buttons">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

