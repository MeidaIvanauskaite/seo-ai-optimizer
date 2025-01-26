@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Google Analytics Accounts and Properties</h1>

    @if (isset($error))
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ $error }}
        </div>
    @elseif (count($accounts) === 0)
        <div class="alert alert-warning text-center">
            No accounts or properties found.
        </div>
    @else
        <div class="accordion" id="analyticsAccountsAccordion">
            @foreach ($accounts as $index => $data)
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading-{{ $index }}">
                        <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $index }}">
                            <strong>Account: </strong> {{ $data['account']['name'] }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $index }}"
                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                         aria-labelledby="heading-{{ $index }}"
                         data-bs-parent="#analyticsAccountsAccordion">
                        <div class="accordion-body">
                            <p><strong>ID:</strong> {{ $data['account']['id'] }}</p>
                            @if (count($data['properties']) > 0)
                                <h6 class="text-secondary">Properties:</h6>
                                <ul class="list-group">
                                    @foreach ($data['properties'] as $property)
                                        <li class="list-group-item">
                                            <h6 class="text-primary">{{ $property->displayName }}</h6>
                                            <p>
                                                <strong>Time Zone:</strong> {{ $property->timeZone }} <br>
                                                <strong>Currency:</strong> {{ $property->currencyCode }} <br>
                                                <strong>Industry:</strong> {{ $property->industryCategory }} <br>
                                                <strong>Service Level:</strong>
                                                <span class="badge bg-success">{{ $property->serviceLevel }}</span>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No properties available for this account.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
