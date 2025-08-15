<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قائمة المستخدمين</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f7f7f7; }
        .table thead th { white-space: nowrap; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <h2 class="mb-4">📋 جدول المستخدمين</h2>

        <div id="alertBox" class="alert d-none" role="alert"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>الدور</th>
                        <th>اللغة</th>
                        <th>الوضع الليلي</th>
                        <th>عمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr id="row-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td class="col-username">{{ $user->user_name }}</td>
                        <td class="col-email">{{ $user->email }}</td>
                        <td class="col-role">{{ $user->role }}</td>
                        <td class="col-language">{{ $user->language }}</td>
                        <td class="col-dark_mode" data-value="{{ $user->dark_mode ? 1 : 0 }}">
                            {{ $user->dark_mode ? '✅' : '❌' }}
                        </td>
                        <td class="text-nowrap">
                            <button
                                type="button"
                                class="btn btn-sm btn-primary btn-edit"
                                data-id="{{ $user->id }}"
                                data-username="{{ $user->user_name }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ $user->role }}"
                                data-language="{{ $user->language }}"
                                data-darkmode="{{ $user->dark_mode ? 1 : 0 }}"
                                data-address="{{ $user->address }}"
                            >تعديل</button>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger btn-delete ms-2"
                                data-id="{{ $user->id }}"
                            >حذف</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- نافذة التعديل -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form id="editForm" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">تعديل المستخدم</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" id="edit_id">

              <div class="mb-3">
                  <label class="form-label">الاسم</label>
                  <input type="text" id="edit_user_name" class="form-control" required maxlength="150">
              </div>

              <div class="mb-3">
                  <label class="form-label">البريد</label>
                  <input type="email" id="edit_email" class="form-control" required maxlength="100">
              </div>

              <div class="mb-3">
                  <label class="form-label">العنوان</label>
                  <input type="text" id="edit_address" class="form-control" maxlength="200">
              </div>

              <div class="mb-3">
                  <label class="form-label">الدور</label>
                  <select id="edit_role" class="form-select" required>
                      <option value="student">طالب</option>
                      <option value="publisher">ناشر</option>
                      <option value="admin">مدير</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label class="form-label">اللغة</label>
                  <input type="text" id="edit_language" class="form-control" required maxlength="10" value="ar">
              </div>

              <div class="mb-3">
                  <label class="form-label">كلمة المرور (اختياري لتحديثها)</label>
                  <input type="password" id="edit_password" class="form-control" minlength="6" placeholder="اتركها فارغة للإبقاء عليها">
              </div>

              <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="edit_dark_mode">
                  <label class="form-check-label" for="edit_dark_mode">الوضع الليلي</label>
              </div>

              <div class="alert alert-danger d-none mt-3" id="edit_error"></div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ</button>
          </div>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // مسار الـ API الصحيح بغض النظر عن مجلد التطبيق
        const API_BASE = @json(url('api/users'));
        // لو كانت مساراتك محمية بتوكن، فعّل السطر التالي وأضف التوكن:
        // const AUTH_HEADERS = { 'Authorization': 'Bearer YOUR_TOKEN_HERE' };
        const AUTH_HEADERS = {};

        const alertBox = document.getElementById('alertBox');
        const showAlert = (msg, type = 'success') => {
            alertBox.textContent = msg;
            alertBox.className = `alert alert-${type}`;
        };
        const hideAlert = () => {
            alertBox.className = 'alert d-none';
            alertBox.textContent = '';
        };

        // فتح نافذة التعديل وتعبئتها
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                hideAlert();
                const id = btn.dataset.id;
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_user_name').value = btn.dataset.username || '';
                document.getElementById('edit_email').value = btn.dataset.email || '';
                document.getElementById('edit_address').value = btn.dataset.address || '';
                document.getElementById('edit_role').value = btn.dataset.role || 'student';
                document.getElementById('edit_language').value = btn.dataset.language || 'ar';
                document.getElementById('edit_password').value = '';
                document.getElementById('edit_dark_mode').checked = btn.dataset.darkmode === '1';

                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            });
        });

        // حفظ التعديل
        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;

            const payload = {
                user_name: document.getElementById('edit_user_name').value.trim(),
                email: document.getElementById('edit_email').value.trim(),
                address: document.getElementById('edit_address').value.trim() || null,
                role: document.getElementById('edit_role').value,
                language: document.getElementById('edit_language').value.trim(),
                dark_mode: document.getElementById('edit_dark_mode').checked ? 1 : 0
            };

            const password = document.getElementById('edit_password').value;
            if (password) payload.password = password;

            const errBox = document.getElementById('edit_error');
            errBox.classList.add('d-none');
            errBox.textContent = '';

            try {
                const res = await fetch(`${API_BASE}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        ...AUTH_HEADERS
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    const message = (data?.message) || 'فشل التحديث';
                    const firstError = data?.errors ? Object.values(data.errors)[0][0] : '';
                    errBox.textContent = firstError || message;
                    errBox.classList.remove('d-none');
                    return;
                }

                // تحديث الصف في الجدول
                const row = document.getElementById(`row-${id}`);
                row.querySelector('.col-username').textContent = data.user_name;
                row.querySelector('.col-email').textContent = data.email;
                row.querySelector('.col-role').textContent = data.role;
                row.querySelector('.col-language').textContent = data.language;

                const dmCell = row.querySelector('.col-dark_mode');
                dmCell.dataset.value = data.dark_mode ? 1 : 0;
                dmCell.textContent = data.dark_mode ? '✅' : '❌';

                // تحديث بيانات زر التعديل
                const editBtn = row.querySelector('.btn-edit');
                editBtn.dataset.username = data.user_name;
                editBtn.dataset.email = data.email;
                editBtn.dataset.role = data.role;
                editBtn.dataset.language = data.language;
                editBtn.dataset.darkmode = data.dark_mode ? 1 : 0;
                editBtn.dataset.address = data.address ?? '';

                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                showAlert('تم تحديث المستخدم بنجاح', 'success');
            } catch (error) {
                errBox.textContent = 'حدث خطأ غير متوقع. حاول لاحقاً.';
                errBox.classList.remove('d-none');
            }
        });

        // حذف المستخدم
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async () => {
                hideAlert();
                const id = btn.dataset.id;
                if (!confirm('متأكد من الحذف؟ لا يمكن التراجع.')) return;

                try {
                    const res = await fetch(`${API_BASE}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            ...AUTH_HEADERS
                        }
                    });

                    if (!res.ok) {
                        const data = await res.json().catch(() => ({}));
                        showAlert(data?.message || 'فشل الحذف', 'danger');
                        return;
                    }

                    document.getElementById(`row-${id}`)?.remove();
                    showAlert('تم حذف المستخدم بنجاح', 'warning');
                } catch (error) {
                    showAlert('حدث خطأ غير متوقع أثناء الحذف.', 'danger');
                }
            });
        });
    });
    </script>
</body>
</html>
