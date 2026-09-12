@extends('layouts.public')

@section('title', 'Membership Plans - HimRishtey')

@section('content')
<section class="pp-main" id="main-content">
    <div class="pp-hero">
        <p class="pp-kicker">Membership</p>
        <h1 class="pp-title">Choose a plan that suits you</h1>
        <p class="pp-intro">Compare HimRishtey membership plans and unlock the features you need.</p>
    </div>
    <div class="container pb-5">
        <div class="row g-4">
            @forelse ($pricings as $plan)
            <div class="col-md-6 col-lg-4">
                <article class="pp-card h-100">
                    <div class="pp-content">
                        <h2 class="h4">{{ $plan->plan_name }}</h2>
                        <p class="h3 mb-3">₹{{ $plan->final_cost }}</p>
                        <p>{{ $plan->duration_days }} days · {{ $plan->view_contact }} contact views</p>
                        @if ($plan->plan_description)
                        <p>{{ $plan->plan_description }}</p>
                        @endif
                        <a class="btn btn-primary public-cta public-cta-primary" href="{{ route('login-form') }}#register">Register to choose this plan</a>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12">
                <article class="pp-card">
                    <div class="pp-content">
                        <p>Membership plans are currently unavailable. Please contact support for assistance.</p>
                    </div>
                </article>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection