@extends('layouts.app')

@section('content')
<h2>تسجيل الدخول</h2>
<form id="loginForm">
  <label>البريد الإلكتروني:</label><br>
  <input type="email" name="email" required><br><br>

  <label>كلمة المرور:</label><br>
  <input type="password" name="password" required><br><br>

  <button type="submit">دخول</button>
</form>

<script>
  document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    const response = await fetch("{{ url('api/login') }}", {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const result = await response.json();
    alert(result.message || 'تم تسجيل الدخول');
  });
</script>
@endsection
