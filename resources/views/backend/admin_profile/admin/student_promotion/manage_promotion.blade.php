@extends('backend.admin_profile.admin.admin_dashboard')
@section('admin')

<div class="container py-4">
    <h4 class="p-2 bg-info text-white rounded" style="text-align: center;">Manage Promotions</h4>

    <!-- ✅ UX Note -->
    <div class="alert alert-info">
        <strong>Note:</strong> For bulk deletion, please click "Select All" checkbox  Then click the "Delete Selected" button. For single deletion select the checkbox of the row you want to delete, and finally click the "Delete" button.
    </div>

    <!-- ✅ Filter Form -->
    <form method="GET" action="{{ route('promotion.manage') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="class_id" class="form-select">
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->class_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="term" class="form-select">
                <option value="">-- Select Term --</option>
                @foreach($terms as $term)
                    <option value="{{ $term }}" {{ request('term') == $term ? 'selected' : '' }}>
                        {{ $term }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="session" class="form-select">
                <option value="">-- Select Session --</option>
                @foreach($sessions as $session)
                    <option value="{{ $session }}" {{ request('session') == $session ? 'selected' : '' }}>
                        {{ $session }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">Filter</button>
            <a href="{{ route('promotion.manage') }}" class="btn btn-secondary flex-fill">Reset</a>
        </div>
    </form>

    <!-- ✅ Bulk Delete Form -->
    <form method="POST" action="{{ route('promotion.bulk.delete') }}">
        @csrf
        <div class="mb-3">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete selected promotions?')">
                Delete Selected
            </button>
        </div>

        <!-- ✅ Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Select All <input type="checkbox" id="select_all"></th>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $key => $promotion)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="promotion_ids[]" value="{{ $promotion->id }}" class="promotion-check form-check-input">
                            </td>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $promotion->student->name ?? 'N/A' }}</td>
                            <td>{{ $promotion->class->class_name ?? 'N/A' }}</td>
                            <td>{{ $promotion->subject->subject_name ?? 'N/A' }}</td>
                            <td>{{ $promotion->term }}</td>
                            <td>{{ $promotion->session }}</td>
                            <td class="text-center">
                                <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-info btn-sm mb-1">Edit</a>
                                <form action="{{ route('promotion.delete', $promotion->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this promotion?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No promotions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
              {{ $promotions->links('pagination::bootstrap-5') }}
            </div>

    </form>
</div>

<!-- ✅ Select All Script -->
<script>
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.promotion-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

@endsection
