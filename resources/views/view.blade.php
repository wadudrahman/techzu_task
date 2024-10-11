@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <a href="{{ route('showCsvImport') }}" class="btn btn-outline-warning ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-up">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                            <path d="M19 22v-6" />
                            <path d="M22 19l-3 -3l-3 3" />
                        </svg>
                        Import CSV
                    </a>
                    <a href="{{ route('list') }}" class="btn btn-dark ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-time">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                            <path d="M15 3v4" />
                            <path d="M7 3v4" />
                            <path d="M3 11h16" />
                            <path d="M18 16.496v1.504l1 1" />
                        </svg>
                        Event List
                    </a>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <h2 class="page-title">
                        Update New Event
                    </h2>
                </div>
            </div>

        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-md-6">
                    <form class="card" method="post" action="{{ route('update', ['uuid' => $event->uuid]) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label required">Event Title</label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               name="title" value="{{ old('title', $event->title) }}" required
                                               placeholder="Marina Bay Singapore Countdown">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label required">Select Date</label>
                                    <div>
                                        <select class="form-select @error('date') is-invalid @enderror" name="date" required>
                                            @foreach($availableDates as $availableDate)
                                                <option value="{{ $availableDate }}"
                                                    {{ old('date', \Carbon\Carbon::createFromFormat('Y-m-d', $event->date)->format('d M Y')) === $availableDate ? 'selected' : '' }}>
                                                    {{ $availableDate }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-hint">15 Days From Today Available. Including Today.</small>
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label required">Select Time</label>
                                    <div>
                                        <select class="form-select @error('time') is-invalid @enderror" name="time" required>
                                            @foreach($availableSlots as $availableSlot)
                                                <option value="{{ $availableSlot }}"
                                                    {{ old('time', \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('H:i')) === $availableSlot ? 'selected' : '' }}>
                                                    {{ $availableSlot }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-hint">1 hour slot, available for business hours.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Guests</label>
                                    <div>
                                        <input type="text" class="form-control @error('guests') is-invalid @enderror"
                                               name="guests" value="{{ old('guests', $event->guests) }}"
                                               placeholder="example@domain.com" required>
                                        @error('guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-hint">Use semi - colon (;) as separator</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
