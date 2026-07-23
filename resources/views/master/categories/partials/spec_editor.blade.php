<div class="form-group mt-4">
    <label class="theme-text d-flex justify-content-between align-items-center">
        {{ __('messages.custom_specifications') }}
        <button type="button" class="btn btn-sm btn-info" id="add-spec-btn">
            <i class="fas fa-plus"></i> {{ __('messages.add_field') }}
        </button>
    </label>
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="specs-table">
            <thead>
                <tr>
                    <th>{{ __('messages.field_key') }}</th>
                    <th>{{ __('messages.field_label') }}</th>
                    <th>{{ __('messages.input_type') }}</th>
                    <th>{{ __('messages.action') }}</th>
                </tr>
            </thead>
            <tbody id="specs-body">
                <!-- Dynamic rows go here -->
            </tbody>
        </table>
    </div>
    <!-- Hidden input to store JSON -->
    <input type="hidden" name="spec_definitions" id="spec_definitions_input" value="{{ old('spec_definitions', isset($category) && $category->spec_definitions ? json_encode($category->spec_definitions) : '[]') }}">
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let specDefinitions = [];
    try {
        let val = $('#spec_definitions_input').val();
        specDefinitions = val ? JSON.parse(val) : [];
    } catch (e) {
        specDefinitions = [];
    }

    function renderSpecs() {
        let tbody = $('#specs-body');
        tbody.empty();
        
        if(specDefinitions.length === 0) {
            tbody.append('<tr><td colspan="4" class="text-center text-muted">{{ __('messages.no_custom_specifications') }}</td></tr>');
        }

        specDefinitions.forEach((spec, index) => {
            let row = `<tr>
                <td><input type="text" class="form-control form-control-sm spec-key" data-index="${index}" value="${spec.name || ''}" placeholder="e.g. ram_size"></td>
                <td><input type="text" class="form-control form-control-sm spec-label" data-index="${index}" value="${spec.label || ''}" placeholder="e.g. RAM Size"></td>
                <td>
                    <select class="form-control form-control-sm custom-select spec-type" data-index="${index}">
                        <option value="text" ${spec.type === 'text' ? 'selected' : ''}>Text</option>
                        <option value="number" ${spec.type === 'number' ? 'selected' : ''}>Number</option>
                        <option value="date" ${spec.type === 'date' ? 'selected' : ''}>Date</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-sm btn-danger remove-spec-btn" data-index="${index}"><i class="fas fa-trash"></i></button></td>
            </tr>`;
            tbody.append(row);
        });
        
        updateHiddenInput();
    }

    function updateHiddenInput() {
        $('#spec_definitions_input').val(JSON.stringify(specDefinitions));
    }

    $('#add-spec-btn').click(function() {
        specDefinitions.push({ name: '', label: '', type: 'text' });
        renderSpecs();
    });

    $(document).on('click', '.remove-spec-btn', function() {
        let idx = $(this).data('index');
        specDefinitions.splice(idx, 1);
        renderSpecs();
    });

    $(document).on('change keyup', '.spec-key, .spec-label, .spec-type', function() {
        let idx = $(this).data('index');
        if($(this).hasClass('spec-key')) specDefinitions[idx].name = $(this).val().toLowerCase().replace(/[^a-z0-9_]/g, '');
        if($(this).hasClass('spec-label')) specDefinitions[idx].label = $(this).val();
        if($(this).hasClass('spec-type')) specDefinitions[idx].type = $(this).val();
        updateHiddenInput();
    });

    // Ensure format on submit
    $('form').submit(function() {
        // filter out empty keys
        specDefinitions = specDefinitions.filter(s => s.name.trim() !== '');
        updateHiddenInput();
    });

    renderSpecs();
});
</script>
@endpush
