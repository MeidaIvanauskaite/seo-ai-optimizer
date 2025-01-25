<!-- resources/views/google.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Google Analytics Data</h1>
    <ul id="google-accounts"></ul>
</div>

<script>
    fetch('/google-accounts')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('google-accounts');
            data.items.forEach(account => {
                const li = document.createElement('li');
                li.textContent = `${account.name} (ID: ${account.id})`;
                list.appendChild(li);
            });
        })
        .catch(error => console.error('Error:', error));
</script>
@endsection
