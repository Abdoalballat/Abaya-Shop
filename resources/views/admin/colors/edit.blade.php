<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head><meta charset="UTF-8"><title>تعديل لون</title></head>
<body>
    <h1>تعديل اللون: {{ $color->name }}</h1>
    <p><a href="{{ route('admin.colors.index') }}">رجوع</a></p>
    <hr>
    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf @method('PUT')
        <div>
            <label>اسم اللون:</label>
            <input type="text" name="name" value="{{ $color->name }}" required>
        </div>
        <br>
        <div>
            <label>كود اللون:</label>
            <input type="color" name="hex_code" value="{{ $color->hex_code }}" required>
        </div>
        <br>
        <button type="submit">تحديث اللون</button>
    </form>
</body>
</html>