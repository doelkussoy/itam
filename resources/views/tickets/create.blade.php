@extends('layouts.admin')

@section('title', __('messages.create_ticket'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <h5 class="text-info mb-3"><i class="fas fa-ticket-alt"></i> {{ __('messages.ticket_details') }}</h5>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="theme-text d-flex justify-content-between align-items-center w-100" style="margin-bottom: 0.5rem;">
                        <span>{{ __('messages.title_subject') }} *</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" style="padding: 2px 8px; font-size: 0.8rem;">
                                <i class="fas fa-list"></i> Template
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow" style="max-height: 250px; overflow-y: auto;">
                                @foreach($templates as $template)
                                    <a class="dropdown-item template-title-item" href="#" data-title="{{ $template }}"><small>{{ $template }}</small></a>
                                @endforeach
                            </div>
                        </div>
                    </label>
                    <input type="text" id="ticket_title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required >
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.reporter_employee') }} *</label>
                    <select name="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" required >
                        <option value="" >{{ __('messages.select_employee') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"  {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->employee_id }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.related_asset_optional') }}</label>
                    <select name="asset_id" class="form-control select2 @error('asset_id') is-invalid @enderror" >
                        <option value="" >{{ __('messages.none') }}</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}"  {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag }} - {{ $asset->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">Kategori Pelaporan *</label>
                    <select name="category" id="category_select" class="form-control select2 @error('category') is-invalid @enderror" required >
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['name'] }}" {{ old('category') == $category['name'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <small id="category_description" class="form-text text-info mt-2"></small>
                    @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.priority') }} *</label>
                    <select name="priority" class="form-control @error('priority') is-invalid @enderror" required >
                        <option value="Low"  {{ old('priority') == 'Low' ? 'selected' : '' }}>{{ __('messages.low') }}</option>
                        <option value="Medium"  {{ old('priority') == 'Medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                        <option value="High"  {{ old('priority') == 'High' ? 'selected' : '' }}>{{ __('messages.high') }}</option>
                        <option value="Critical"  {{ old('priority') == 'Critical' ? 'selected' : '' }}>{{ __('messages.critical') }}</option>
                    </select>
                    @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">PIC (Penanggung Jawab)</label>
                    <select name="pic_id" id="pic_id" class="form-control select2 w-100 theme-input">
                        <option value="">Belum di-assign</option>
                        @foreach($pics as $pic)
                            <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                                {{ $pic->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pic_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label class="theme-text">Waktu Laporan Dibuat</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-right-0"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" name="created_at" class="form-control theme-input border-left-0 flatpickr-datetime" style="background: transparent;" value="{{ old('created_at', now()->format('Y-m-d\TH:i')) }}">
                    </div>
                    <small class="text-muted">Biarkan jika ingin menggunakan waktu saat ini.</small>
                    @error('created_at') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label  class="theme-text">{{ __('messages.description_details') }} *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required >{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const categoriesData = @json($categories);
    const categorySelect = document.getElementById('category_select');
    const categoryDesc = document.getElementById('category_description');

    function updateCategoryDescription() {
        const selectedValue = categorySelect.value;
        const category = categoriesData.find(c => c.name === selectedValue);
        if (category) {
            categoryDesc.innerHTML = '<strong>Deskripsi Tugas:</strong> ' + category.description;
        } else {
            categoryDesc.innerHTML = '';
        }
    }

    // Trigger on change
    if(categorySelect) {
        // If select2 is used, we need to listen to its event
        $(categorySelect).on('select2:select', updateCategoryDescription);
        $(categorySelect).on('change', updateCategoryDescription);
        // Initial load
        updateCategoryDescription();
    }

    // Template Title Selection
    document.querySelectorAll('.template-title-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('ticket_title').value = this.getAttribute('data-title');
        });
    });
</script>
@endpush
@endsection
