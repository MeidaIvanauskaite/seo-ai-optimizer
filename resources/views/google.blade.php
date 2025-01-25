<!-- resources/views/google.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Google Analytics Data</h1>
    <div id="loading-spinner" class="text-center my-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="google-accounts-container" style="display: none;">
        <div class="row" id="google-accounts"></div>
    </div>
</div>

<script>
    fetch('/google-accounts')
        .then(response => response.json())
        .then(data => {
            const listContainer = document.getElementById('google-accounts');
            const loadingSpinner = document.getElementById('loading-spinner');
            const accountsContainer = document.getElementById('google-accounts-container');

            if (data.items.length > 0) {
                data.items.forEach(account => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-4';

                    const card = `
                        <div class="card shadow rounded">
                            <div class="card-body">
                                <h5 class="card-title text-primary">${account.name}</h5>
                                <p class="card-text"><strong>ID:</strong> ${account.id}</p>
                                <p class="card-text"><strong>Roles:</strong> ${account.permissions.effective.join(', ')}</p>
                                <p class="card-text"><strong>Created:</strong> ${new Date(account.created).toLocaleDateString()}</p>
                                <a href="${account.childLink.href}" target="_blank" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    `;

                    col.innerHTML = card;
                    listContainer.appendChild(col);
                });
                loadingSpinner.style.display = 'none';
                accountsContainer.style.display = 'block';
            } else {
                loadingSpinner.innerHTML = '<p class="text-danger">No accounts found.</p>';
            }
        })
        .catch(error => {
            const loadingSpinner = document.getElementById('loading-spinner');
            loadingSpinner.innerHTML = `<p class="text-danger">Error: ${error.message}</p>`;
            console.error('Error:', error);
        });
</script>
@endsection
