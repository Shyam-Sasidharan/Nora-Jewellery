@extends('layouts.frontend')

@section('content')
@php $data = $contact?->data ?? []; @endphp

<section class="page-hero">
    <div class="reveal">
        <span>Contact</span>
        <h1>{{ $contact?->title ?? 'Book A Private Jewellery Appointment' }}</h1>
    </div>
</section>

<section class="section-wrap contact-grid">
    <div class="contact-card reveal">
        <p>{{ $contact?->content ?? 'Speak with our consultants for bridal, bespoke, or collection enquiries.' }}</p>
        <dl>
            <div><dt>Phone</dt><dd>{{ $data['phone'] ?? '+91 8848254420' }}</dd></div>
            <div><dt>Email</dt><dd>{{ $data['email'] ?? 'norajewels0523@gmail.com' }}</dd></div>
            <div><dt>Address</dt><dd>{{ $data['address'] ?? 'Nora Jewels nss college(po) Nemmara, palakkad 678508' }}</dd></div>
            <div><dt>Hours</dt><dd>{{ $data['hours'] ?? 'Monday to Saturday, 10:00 AM to 7:00 PM' }}</dd></div>
        </dl>
    </div>
    <form class="contact-form reveal" action="{{ route('contact.send') }}" method="post">
        @csrf
        @if(session('success'))
            <div class="notice success">{{ session('success') }}</div>
        @endif
        <input name="name" value="{{ old('name') }}" placeholder="Name" required>
        <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" required>
        <input name="phone" value="{{ old('phone') }}" placeholder="Phone">
        <input name="subject" value="{{ old('subject', request('subject')) }}" placeholder="Subject">
        <textarea name="message" rows="6" placeholder="Tell us what you are looking for" required>{{ old('message') }}</textarea>
        @if($errors->any())
            <div class="notice error">{{ $errors->first() }}</div>
        @endif
        <button class="gold-button" type="submit">Send Enquiry</button>
    </form>
</section>
@endsection
