{{-- resources/views/users/index.blade.php --}}
    <!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت کاربران و رول‌ها</title>
    <style>
        /* Reset و فونت */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; direction: rtl; background: #f5f6fa; padding: 30px; }

        h2 { margin-bottom: 20px; color: #2f3640; }

        /* کارت‌ها */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* چک‌باکس‌ها */
        label.checkbox-container {
            display: block;
            position: relative;
            padding-left: 25px;
            margin-bottom: 8px;
            cursor: pointer;
            user-select: none;
        }

        label.checkbox-container input { position: absolute; opacity: 0; cursor: pointer; }
        label.checkbox-container .checkmark {
            position: absolute;
            top: 0; left: 0;
            height: 18px;
            width: 18px;
            background-color: #eee;
            border-radius: 4px;
        }

        label.checkbox-container input:checked ~ .checkmark {
            background-color: #0984e3;
        }

        label.checkbox-container .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        label.checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        label.checkbox-container .checkmark:after {
            left: 6px;
            top: 2px;
            width: 4px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* دکمه‌ها */
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background-color: #00b894;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover { background-color: #55efc4; }

        /* پیام موفقیت */
        .alert-success {
            background: #dff9fb;
            border-left: 5px solid #00cec9;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            color: #2d3436;
        }

        /* دکمه انتخاب همه */
        .select-all-btn {
            display: inline-block;
            margin-bottom: 10px;
            background: #0984e3;
            font-size: 0.9em;
        }
        .select-all-btn:hover { background: #74b9ff; }
    </style>
</head>
<body>

<h2>📋 کاربران و اختصاص رول</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@foreach($users as $user)
    <div class="card">
        <strong>{{ $user->name }} ({{ $user->email }})</strong>

        <form method="POST" action="{{ route('users.assignRoles', $user) }}">
            @csrf

            <button type="button" class="select-all-btn" onclick="toggleAllRoles(this)">انتخاب / غیرفعال کردن همه</button>

            @foreach($roles as $role)
                <label class="checkbox-container">{{ $role->name }}
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                </label>
            @endforeach

            <button type="submit">ذخیره رول‌ها</button>
        </form>
    </div>
@endforeach

<script>
    // انتخاب / غیرفعال کردن همه رول‌ها برای هر کاربر
    function toggleAllRoles(btn) {
        const form = btn.closest('form');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        let allChecked = Array.from(checkboxes).every(chk => chk.checked);
        checkboxes.forEach(chk => chk.checked = !allChecked);
    }
</script>

</body>
</html>
