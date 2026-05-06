@extends('layouts.app')

@section('content')
    @include('record.partials.consultation-record-detail', [
        'portalPrefix' => null,
        'record' => $record,
        'history' => $history ?? collect(),
        'consultationTeam' => $consultationTeam ?? [],
    ])
@endsection
