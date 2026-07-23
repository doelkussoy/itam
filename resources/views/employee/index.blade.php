@extends('layouts.admin')

@section('title', __('messages.manage') . ' ' . __('messages.employee'))

@section('content')
<div class="row mb-3">
    <div class="col-12 mb-3">
        <form action="{{ route('employees.index') }}" method="GET">
            <div class="d-flex flex-wrap gap-2" style="gap: 10px;">
                <input type="text" name="search" class="form-control theme-input" placeholder="{{ __('messages.search_employee') }}" value="{{ request('search') }}" style="width: 250px;" >
                
                <select name="department_id" class="form-control select2 theme-input" style="width: 200px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">All {{ __('messages.department') }}</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" style="color: #000;" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>

                <select name="supervisor_id" class="form-control select2 theme-input" style="width: 200px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">All {{ __('messages.supervisor') ?? 'Supervisor' }}</option>
                    @foreach($supervisors as $sup)
                        <option value="{{ $sup->id }}" style="color: #000;" {{ request('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="form-control select2 theme-input" style="width: 150px; background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #fff;" onchange="this.form.submit()" >
                    <option value="" style="color: #000;">{{ __('messages.all_status') }}</option>
                    <option value="Active" style="color: #000;" {{ request('status') == 'Active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="Inactive" style="color: #000;" {{ request('status') == 'Inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>

                <button class="btn btn-outline-info" type="submit" ><i class="fas fa-search"></i></button>
                @if(request('search') || request('department_id') || request('supervisor_id') || request('status'))
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary" ><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-12 text-right">
        <button type="button" class="btn btn-sm btn-success mr-2" data-toggle="modal" data-target="#importModal" style="box-shadow: 0 0 10px rgba(40,167,69,0.3);">
            <i class="fas fa-file-excel"></i> {{ __('messages.import_excel') }}
        </button>
        <a href="{{ route('employees.export') }}" class="btn btn-sm btn-warning mr-2" style="box-shadow: 0 0 10px rgba(255,193,7,0.3);">
            <i class="fas fa-download"></i> {{ __('messages.export') }}
        </a>
        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary" >
            <i class="fas fa-user-plus"></i> {{ __('messages.add') }} {{ __('messages.employee') }}
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" style="background: rgba(40,167,69,0.2); border: 1px solid rgba(40,167,69,0.5); color: #28a745; backdrop-filter: blur(10px);">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible" style="background: rgba(220,53,69,0.2); border: 1px solid rgba(220,53,69,0.5); color: #dc3545; backdrop-filter: blur(10px);">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i> {{ session('error') }}
    </div>
@endif

<div class="card theme-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0 theme-table">
                <thead>
                    <tr >
                        <th width="50">{{ __('messages.no') }}</th>
                        <th>{{ __('messages.nik') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.anydesk_id') ?? 'AnyDesk ID' }}</th>
                        <th>{{ __('messages.pc_username') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th width="150" class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td class="theme-text">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                        <td class="text-info font-weight-bold">{{ $emp->employee_id }}</td>
                        <td class="theme-text"><a href="{{ route('employees.show', $emp) }}" class="text-info font-weight-bold">{{ $emp->name }}</a></td>
                        <td class="theme-text">{{ $emp->email ?? '-' }}</td>
                        <td class="theme-text text-success font-weight-bold"><code>{{ $emp->anydesk_id ?? '-' }}</code></td>
                        <td class="theme-text text-warning font-weight-bold"><code>{{ $emp->login_username ?? '-' }}</code></td>
                        <td class="theme-text">
                            @if($emp->status == 'Active')
                                <span class="badge badge-success" style="box-shadow: 0 0 8px rgba(40,167,69,0.5);">{{ __('messages.active') }}</span>
                            @else
                                <span class="badge badge-danger" style="box-shadow: 0 0 8px rgba(220,53,69,0.5);">{{ __('messages.inactive') }}</span>
                            @endif
                        </td>
                        <td class="theme-text">
                            <div class="d-flex justify-content-center" style="gap: 8px;">
                                <a href="{{ route('employees.edit', $emp) }}" class="btn action-btn btn-outline-warning" style="border: 1px solid rgba(255, 193, 7, 0.3); background: rgba(255, 193, 7, 0.15); color: #ffc107;"  title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('employees.destroy', $emp) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-delete action-btn btn-outline-danger" style="border: 1px solid rgba(220, 53, 69, 0.3); background: rgba(220, 53, 69, 0.15); color: #dc3545;"  title="{{ __('messages.delete') }}" data-confirm-message="{{ __('messages.confirm_delete') }}"><i class="fas fa-trash"></i></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">{{ __('messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        {{ $employees->appends(request()->query())->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title " id="importModalLabel"><i class="fas fa-upload text-success"></i> {{ __('messages.import_excel') }} {{ __('messages.employee') }}</h5>
        <button type="button" class="close " data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.import_excel') }} (.xlsx, .xls, .csv)</label>
                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="customFile" required accept=".xlsx, .xls, .csv">
                    <label class="custom-file-label" for="customFile" style="background-color: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;" class="theme-text">{{ __('messages.select') }}...</label>
                </div>
            </div>
        </div>
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
            <button type="submit" class="btn btn-success" style="box-shadow: 0 0 10px rgba(40,167,69,0.4);">{{ __('messages.import_excel') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    // To show file name in custom file input
    document.querySelector('.custom-file-input').addEventListener('change',function(e){
      var fileName = document.getElementById("customFile").files[0].name;
      var nextSibling = e.target.nextElementSibling;
      nextSibling.innerText = fileName;
    });
</script>
@endpush
@endsection
