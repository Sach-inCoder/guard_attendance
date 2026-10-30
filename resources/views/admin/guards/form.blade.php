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
    <div class="col-md-3">
        <div class="mb-3">
            <label for="employee_id" class="form-label">Guard ID
            </label>
            <input
                type="text"
                class="form-control"
                name="employee_id"
                id="employee_id"
                placeholder="Enter id"
                value="{{ old('employee_id',$guard->employee_id ?? '') }}"
            />
        </div>
        
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full name
            </label>
            <input
                type="text"
                class="form-control"
                name="full_name"
                id="full_name"
                placeholder="Enter Full name"
                value="{{ old('employee_id',$guard->full_name ?? '') }}"
            />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="joining_date" class="form-label">Joining Date
            </label>
            <input
                type="date"
                class="form-control"
                name="joining_date"
                id="joining_date"
                placeholder="Joining Date"
                value="{{ old('joining_date', ($guard ?? null)?->joining_date?->format('Y-m-d')) }}"
            />
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input
                type="number"
                class="form-control"
                name="mobile"
                id="mobile"
                placeholder="Enter mobile"
                value="{{ old('mobile',$guard->user['mobile'] ?? '') }}"
            />
        </div>
        
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                name="email"
                id="email"
                placeholder="Enter Email"
                value="{{ old('email',$guard->user['email'] ?? '') }}"
            />
        </div>
        
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                class="form-control"
                name="password"
                id="password"
                placeholder="Enter password"
                value="{{ old('password') }}"
            />
        </div>
        
    </div>
    <div class="col-md-12">
        <div class="mb-3">
            <input type="hidden" name="employment_status" value="0">
            <input type="checkbox" name="employment_status" value="1" class="form-check-input" id="employment_status"
                @checked(old('employment_status', $guard->employment_status ?? true))>
            <label class="form-check-label" for="employment_status">
                Active
            </label>
        </div>
    </div>
</div>