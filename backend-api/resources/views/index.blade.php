@extends('layouts.master')
@section('title', 'Best Travel Agency in Bangladesh | FNF Trip')
@section('description')
Book group tours, private tours, honeymoon tours and adventure trips across Bangladesh at affordable price.
@endsection
@section('keywords')
travel agency bangladesh, group tour bd, sundarban tour, chaina tour, international tour
@endsection
@section('og_title','FNF Trip - Explore Bangladesh')
@section('og_description','Discover the beauty of Bangladesh with FNF Trip. We offer unforgettable group tours, private tours, honeymoon packages, and adventure trips at affordable prices. Book your next adventure today!')
@section('og_image', asset('images/seo.jpg'))
@section('twitter_title','FNF Trip - Best Travel Agency in Bangladesh')
@section('twitter_description','Book group tours, private tours, honeymoon tours and adventure trips across Bangladesh at affordable price with FNF Trip.')
@section('twitter_image', asset('images/seo.jpg'))



@section('content')
    <section class="container mt-5">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-up">Explore Our Exclusive Packages</h2>
        </section>
@endsection

@push('styles')
<style>
    /* custom css */
    .package-card { border-left: 5px solid #0056b3; transition: 0.3s; }
    .package-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>
@endpush
