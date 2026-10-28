@csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row g-2">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" id="address" placeholder="Enter Company address"
                value="{{ old('address', $site->address ?? '') }}" required />
            @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="company_id" class="form-label">Select Company</label>
            <select name="company_id" id="company_id" class="form-select" required>
                <option value="">-- select company --</option>
                @foreach ($companies as $company)
                <option {{ old('company_id',$site->company['id']) == $company->id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            @error('company_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="name" class="form-label">Address Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Address name"
                value="{{ old('name', $site->name ?? '') }}" required />
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" class="form-control" name="latitude" id="latitude" placeholder="Latitude"
                value="{{ old('latitude', $site->latitude ?? '') }}" step="any" inputmode="decimal" required />
            @error('latitude')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" class="form-control" name="longitude" id="longitude" placeholder="Longitude"
                value="{{ old('longitude', $site->longitude ?? '') }}" step="any" inputmode="decimal" required />
            @error('longitude')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                @checked(old('is_active', $site->is_active ?? true))>
            <label class="form-check-label" for="is_active">
                Active
            </label>
        </div>
    </div>
</div>