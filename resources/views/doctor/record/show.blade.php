@extends('layouts.app')

@section('content')
    @php
        $portalPrefix = strtolower(trim((string) (auth()->user()->role ?? 'doctor'))) === 'nurse'
            ? 'nurse'
            : 'doctor';
    @endphp
    @include('record.partials.consultation-record-detail', [
        'portalPrefix' => $portalPrefix,
        'record' => $record,
        'history' => $history ?? collect(),
        'consultationTeam' => $consultationTeam ?? [],
    ])
@endsection
