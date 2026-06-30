@extends('layouts.admin')

@section('title', 'Contact Details')

@section('content')
<form class="admin-form" action="{{ route('admin.contact.update') }}" method="post">
    @csrf @method('put')
    <label>Title <input name="title" value="{{ old('title', $contact->title) }}" required></label>
    <label>Intro <textarea name="content" rows="4">{{ old('content', $contact->content) }}</textarea></label>
    <div class="form-grid">
        <label>Phone <input name="data[phone]" value="{{ old('data.phone', $contact->data['phone'] ?? '') }}"></label>
        <label>Email <input type="email" name="data[email]" value="{{ old('data.email', $contact->data['email'] ?? '') }}"></label>
        <label>Hours <input name="data[hours]" value="{{ old('data.hours', $contact->data['hours'] ?? '') }}"></label>
        <label>Map URL <input name="data[map_url]" value="{{ old('data.map_url', $contact->data['map_url'] ?? '') }}"></label>
    </div>
    <label>Address <input name="data[address]" value="{{ old('data.address', $contact->data['address'] ?? '') }}"></label>
    <button class="admin-button" type="submit">Save Contact Details</button>
</form>

<div class="panel-heading inbox-heading">
    <h2>Contact Messages</h2>
</div>
<div class="admin-panel">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th></th></tr></thead>
        <tbody>
            @foreach($messages as $message)
                <tr class="{{ $message->is_read ? '' : 'unread' }}">
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->subject }}</td>
                    <td>{{ Str::limit($message->message, 80) }}</td>
                    <td class="table-actions">
                        @if(! $message->is_read)
                            <form action="{{ route('admin.messages.read', $message) }}" method="post">
                                @csrf @method('patch')
                                <button type="submit">Read</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="post" onsubmit="return confirm('Delete this message?')">
                            @csrf @method('delete')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $messages->links() }}
</div>
@endsection
