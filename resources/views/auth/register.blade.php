<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تسجيل مستخدم جديد</title>
</head>
<body>
  <h2>تسجيل مستخدم جديد</h2>

  <form id="registerForm">
    <label>اسم المستخدم:</label><br>
    <input type="text" name="user_name" required><br><br>

    <label>البريد الإلكتروني:</label><br>
    <input type="email" name="email" required><br><br>

    <label>كلمة المرور:</label><br>
    <input type="password" name="password" required><br><br>

    <label>الدور (Role):</label><br>
    <select name="role" required>
      <option value="admin">مدير (Admin)</option>
      <option value="student">طالب (Student)</option>
      <option value="publisher">ناشر (Publisher)</option>
    </select><br><br>

    <button type="submit">تسجيل</button>
  </form>

  <script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const data = Object.fromEntries(formData.entries());

      const response = await fetch("{{ url('api/register') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();
      alert(result.message || 'تم التسجيل بنجاح');
    });
  </script>
</body>
</html>
