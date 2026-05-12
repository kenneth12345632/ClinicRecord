@extends('layouts.app')

@section('content')
    @php
        $currentRole = strtolower(trim((string) (auth()->user()->role ?? '')));
        $detectedPrefix = match($currentRole) {
            'nurse' => 'nurse',
            'doctor' => 'doctor',
            'bhw' => 'bhw',
            default => null,
        };
    @endphp
    @include('record.partials.consultation-record-detail', [
        'portalPrefix' => $detectedPrefix,
        'record' => $record,
        'history' => $history ?? collect(),
        'consultationTeam' => $consultationTeam ?? [],
    ])
@endsection
