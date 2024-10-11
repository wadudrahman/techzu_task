<div class="d-flex border-bottom pb-3 flex-row-reverse">
    <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
        @php
            $currentRouteName = request()->route()->getName();
            $currentRouteParam = request()->route()->parameter('status');
        @endphp
        <form action="{{ route($currentRouteName, ['status' => $currentRouteParam]) }}" method="get" autocomplete="off"
              novalidate="">
            <div class="input-icon">
                    <span class="input-icon-addon">
                      <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                           stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                           stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                          <path d="M21 21l-6 -6"></path>
                      </svg>
                    </span>
                <input type="text" name="searchUuid" value="" class="form-control"
                       placeholder="To Search Type & Press Enter">
            </div>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(empty($events))
            <tr>
                <td class="text-center" colspan="6"> No Data to Show !!</td>
            </tr>
        @else
            @foreach($events->items() as $event)
                <tr>
                    <td>{{ $event->uuid }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->date }}</td>
                    <td>{{ $event->time }}</td>
                    <td>
                        @if(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->date . ' ' . $event->time)->isFuture())
                            <span class="badge bg-success me-1"></span>Upcoming
                        @else
                            <span class="badge bg-danger me-1"></span>Completed
                        @endif
                    </td>
                    <td class="text-end">
                <span class="dropdown">
                    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                        Actions
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('view', ['uuid' => $event->uuid]) }}">View</a>
                        <form action="{{ route('delete', ['uuid' => $event->uuid]) }}" method="post">
                            <input class="dropdown-item" type="submit" value="Delete"/>
                            @method('delete')
                            @csrf
                        </form>
                    </div>
                </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@if(!empty($events))
    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">Showing <span>{{ $events->firstItem() }}</span> to <span>{{ $events->lastItem() }}</span>
            of
            <span>{{ $events->total() }}</span> entries</p>
        <ul class="pagination m-0 ms-auto">
            @for($i = 1; $i <= ($events->total()/$events->perPage()+1); $i++)
                <li class="page-item @if($events->currentPage() === $i) active @endif">
                    <a class="page-link" href="{{ $events->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
@endif
