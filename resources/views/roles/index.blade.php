<h2 style="margin-bottom:20px; color:#2d3436; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">➕ افزودن/ویرایش رول</h2>

@if(session('success'))
    <div style="background:#dff9fb; border-left:5px solid #00cec9; padding:12px 15px; border-radius:6px; margin-bottom:20px; font-weight:bold; color:#2d3436;">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 20px rgba(0,0,0,0.1); margin-bottom:30px; transition:0.3s;">
    <form method="POST" action="{{ route('roles.store') }}" id="role-form">
        @csrf
        <input type="hidden" name="role_id" id="role_id" value="">

        <label style="font-weight:bold; margin-bottom:8px; display:block;">نام رول:</label>
        <input type="text" name="name" id="role_name" placeholder="نام رول جدید" required style="width:100%; padding:12px; border-radius:6px; border:1px solid #dcdde1; margin-bottom:20px; font-size:1em;">

        <strong style="display:block; margin-bottom:8px;">سطوح دسترسی:</strong>
        <button type="button" onclick="toggleAllPermissions()" style="margin-bottom:12px; background:#00b894; color:#fff; border:none; padding:8px 15px; border-radius:6px; cursor:pointer; font-weight:bold;">✅ انتخاب/غیرفعال همه</button>

        <div id="permissions-list" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:15px;">
            @foreach($permissions as $permission)
                <label class="checkbox-container" style="flex:1 0 140px; background:#f1f2f6; padding:10px; border-radius:8px; text-align:center; cursor:pointer; transition:0.2s;">
                    {{ $permission->name }}
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                    <span class="checkmark" style="position:absolute; top:6px; left:6px; height:20px; width:20px; background:#eee; border-radius:5px;"></span>
                </label>
            @endforeach
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" id="save-btn" style="background:#0984e3; color:#fff; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer;">ذخیره رول</button>
            <button type="button" id="cancel-btn" style="display:none; background:#636e72; color:#fff; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer;" onclick="cancelEdit()">لغو ویرایش</button>
        </div>
    </form>
</div>

<hr style="margin:40px 0; border:none; border-top:2px solid #dcdde1;">

<h2 style="margin-bottom:20px; color:#2d3436; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">📋 لیست رول‌ها</h2>
<div class="role-list" style="display:flex; flex-wrap:wrap; gap:20px;">
    @foreach($roles as $role)
        <div class="role-item" data-id="{{ $role->id }}" data-name="{{ $role->name }}" data-permissions="{{ $role->permissions->pluck('name')->implode(',') }}"
             style="background:#fff; border-radius:12px; padding:20px; width:260px; box-shadow:0 6px 20px rgba(0,0,0,0.08); transition:0.3s; position:relative;">
            <h3 style="margin-bottom:10px; color:#2d3436; font-size:1.2em;">{{ $role->name }}</h3>
            <ul class="permissions" style="list-style:none; padding:0; display:flex; flex-wrap:wrap; gap:5px; margin-bottom:15px;">
                @foreach($role->permissions as $perm)
                    <li style="background:#dfe6e9; padding:5px 10px; border-radius:5px; font-size:0.85em;">{{ $perm->name }}</li>
                @endforeach
            </ul>

            <div class="role-buttons" style="display:flex; gap:10px;">
                <button class="edit-btn" style="background:#00b894; color:#fff; padding:6px 12px; border-radius:6px; cursor:pointer;" onclick="editRole(this)">ویرایش</button>

                <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('آیا مطمئن هستید حذف شود؟');">
                    @csrf
                    @method('DELETE')
                    <button class="delete-btn" style="background:#e84118; color:#fff; padding:6px 12px; border-radius:6px; cursor:pointer;">حذف</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<script>
    function toggleAllPermissions() {
        const checkboxes = document.querySelectorAll('#permissions-list input[type="checkbox"]');
        const allChecked = Array.from(checkboxes).every(chk => chk.checked);
        checkboxes.forEach(chk => chk.checked = !allChecked);
    }

    function editRole(button) {
        const card = button.closest('.role-item');
        const id = card.dataset.id;
        const name = card.dataset.name;
        const permissions = card.dataset.permissions.split(',');

        document.getElementById('role_id').value = id;
        document.getElementById('role_name').value = name;

        document.querySelectorAll('#permissions-list input[type="checkbox"]').forEach(chk => chk.checked = false);

        permissions.forEach(permName => {
            const checkbox = document.querySelector('#permissions-list input[value="'+permName+'"]');
            if(checkbox) checkbox.checked = true;
        });

        document.getElementById('save-btn').textContent = 'بروزرسانی رول';
        document.getElementById('cancel-btn').style.display = 'inline-block';

        const form = document.getElementById('role-form');
        form.action = `/roles/${id}`;
        if(!form.querySelector('input[name="_method"]')) {
            form.insertAdjacentHTML('beforeend', '@method("PUT")');
        }
    }

    function cancelEdit() {
        document.getElementById('role_id').value = '';
        document.getElementById('role_name').value = '';
        document.querySelectorAll('#permissions-list input[type="checkbox"]').forEach(chk => chk.checked = false);
        document.getElementById('save-btn').textContent = 'ذخیره رول';
        document.getElementById('cancel-btn').style.display = 'none';

        const form = document.getElementById('role-form');
        form.action = "{{ route('roles.store') }}";
        const methodField = form.querySelector('input[name="_method"]');
        if(methodField) methodField.remove();
    }
</script>
