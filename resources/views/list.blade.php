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
                    <a href="{{ route('showAddEvent') }}" class="btn btn-dark ms-auto">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                              fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"
                              stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                            <path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M16 19h6" />
                            <path d="M19 16v6" />
                        </svg>
                        Add New Event
                    </a>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <h2 class="page-title">
                        Event List
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('failure'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('failure') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row row-cards">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('list') }}" class="nav-link @if(is_null($status)) active @endif">
                                            All
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('list', ['status' => \App\Helpers\EnumHelper::UPCOMING]) }}"
                                           class="nav-link @if($status === \App\Helpers\EnumHelper::UPCOMING) active @endif">
                                            Upcoming
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="{{ route('list', ['status' => \App\Helpers\EnumHelper::COMPLETED]) }}"
                                           class="nav-link @if($status === \App\Helpers\EnumHelper::COMPLETED) active @endif">
                                            Completed
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body border-bottom py-3">
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="tabs-home-1" role="tabpanel">
                                        @include('partials.table')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
