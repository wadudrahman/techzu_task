@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <a href="{{ route('list') }}" class="btn btn-dark ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round"
                             class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5"/>
                            <path d="M16 3v4"/>
                            <path d="M8 3v4"/>
                            <path d="M4 11h16"/>
                            <path d="M16 19h6"/>
                            <path d="M19 16v6"/>
                        </svg>
                        Event List
                    </a>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <h2 class="page-title">
                        Add New Event
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
                    <form class="card" method="post" action="{{ route('addEvent') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label required">Event Title</label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               name="title" value="{{ old('title') }}"
                                               placeholder="Marina Bay Singapore Countdown">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label required">Select Date</label>
                                    <div>
                                        <select class="form-select @error('date') is-invalid @enderror" name="date">
                                            @foreach($availableDates as $availableDate)
                                                <option value="{{ $availableDate }}">{{ $availableDate }}</option>
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
                                        <select class="form-select @error('time') is-invalid @enderror" name="time">
                                            @foreach($availableSlots as $availableSlot)
                                                <option value="{{ $availableSlot }}">{{ $availableSlot }}</option>
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
                                        <input type="text" class="form-control  @error('guests') is-invalid @enderror"
                                               name="guests" value="{{ old('guests') }}" placeholder="example@domain.com">
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
