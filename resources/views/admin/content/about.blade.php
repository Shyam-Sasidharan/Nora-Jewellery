@extends('layouts.admin')

@section('title', 'About Content')

@section('content')
<form class="admin-form" action="{{ route('admin.about.update') }}" method="post" enctype="multipart/form-data">
    @csrf @method('put')
    <label>Title <input name="title" value="{{ old('title', $about->title) }}" required></label>
    <label>Content <textarea name="content" rows="8" required>{{ old('content', $about->content) }}</textarea></label>
    <div class="form-grid">
        <label>Heritage Stat <input name="data[heritage]" value="{{ old('data.heritage', $about->data['heritage'] ?? '18K') }}"></label>
        <label>Craft Stat <input name="data[craft]" value="{{ old('data.craft', $about->data['craft'] ?? 'Hand') }}"></label>
        <label>Promise Stat <input name="data[promise]" value="{{ old('data.promise', $about->data['promise'] ?? 'Bespoke') }}"></label>
    </div>
    <label>About Image <input type="file" name="image" accept="image/*"></label>
    @if($about->image_path)
        @php $url = str_starts_with($about->image_path, 'http') ? $about->image_path : asset('storage/'.$about->image_path); @endphp
        <img class="admin-preview" src="{{ $url }}" alt="{{ $about->title }}">
    @endif
    <button class="admin-button" type="submit">Save About Content</button>
</form>
@endsection
