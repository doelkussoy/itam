@extends('layouts.admin')

@section('title', __('messages.system_settings'))

@section('content')
<style>
    /* Fix for active nav-pill text color in light/dark mode */
    .settings-nav .nav-link.active {
        color: var(--theme-text) !important;
        background: rgba(0,240,255,0.1) !important;
        border-left: 3px solid var(--neon-cyan) !important;
        font-weight: bold;
    }
    .settings-nav .nav-link {
        color: var(--theme-text);
        border-bottom: 1px solid var(--tech-border);
    }
    .settings-nav .nav-link:hover {
        background: rgba(0,240,255,0.05);
    }
</style>
<div class="row">
    <div class="col-md-3">
        <div class="card theme-card">
            <div class="card-body p-0">
                <div class="nav flex-column nav-pills settings-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @php $first = true; @endphp
                    @foreach($settings as $group => $items)
                        <a class="nav-link {{ $first ? 'active' : '' }} p-3" id="v-pills-{{ $group }}-tab" data-toggle="pill" href="#v-pills-{{ $group }}" role="tab" aria-controls="v-pills-{{ $group }}" aria-selected="{{ $first ? 'true' : 'false' }}">
                            <i class="fas fa-cogs mr-2 text-info"></i> {{ __('messages.' . $group . '_settings') ?? ucfirst($group) . ' Settings' }}
                        </a>
                        @php $first = false; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card theme-card">
            <form action="{{ route('settings.updateAll') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        @php $first = true; @endphp
                        @foreach($settings as $group => $items)
                            <div class="tab-pane fade {{ $first ? 'show active' : '' }}" id="v-pills-{{ $group }}" role="tabpanel" aria-labelledby="v-pills-{{ $group }}-tab">
                                <h4 class="mb-4 text-info">{{ __('messages.' . $group . '_configuration') ?? ucfirst($group) . ' Configuration' }}</h4>
                                
                                @foreach($items as $setting)
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label theme-text">{{ __('messages.' . $setting->key) ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                        <div class="col-sm-9">
                                            @if($setting->type == 'textarea')
                                                <textarea name="{{ $setting->key }}" class="form-control theme-input" rows="3" >{{ $setting->value }}</textarea>
                                            @elseif($setting->type == 'boolean')
                                                <select name="{{ $setting->key }}" class="form-control theme-input" style="width: 150px;">
                                                    <option value="1"  {{ $setting->value == '1' ? 'selected' : '' }}>{{ __('messages.enabled') }}</option>
                                                    <option value="0"  {{ $setting->value == '0' ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                                                </select>
                                            @else
                                                <input type="text" name="{{ $setting->key }}" class="form-control theme-input" value="{{ $setting->value }}" >
                                            @endif
                                        </div>
                                    </div>
                                    @if(!$loop->last) <hr style="border-color: rgba(255,255,255,0.05);"> @endif
                                @endforeach
                            </div>
                            @php $first = false; @endphp
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-right">
                    <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.save_all_settings') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Tabs are now handled purely by Bootstrap's data-toggle="pill" and our CSS rules -->
@endpush
@endsection
