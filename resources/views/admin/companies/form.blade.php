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
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Company Name"
                value="{{ old('name', $company->name ?? '') }}" required />
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="code" class="form-label">Company Code</label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Enter Company Code"
                value="{{ old('code', $company->code ?? '') }}" required />
            @error('code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="mb-3">
            <label for="notes" class="form-label"><i>Company Notes</i></label>
            <input type="text" class="form-control" name="notes" id="notes" placeholder="Description Notes"
                value="{{ old('notes', $company->notes ?? '') }}" />
            @error('notes')
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
                @checked(old('is_active', $company->is_active ?? true))>
            <label class="form-check-label" for="is_active">
                Active
            </label>
        </div>
    </div>
</div>