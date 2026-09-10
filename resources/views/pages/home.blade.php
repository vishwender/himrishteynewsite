 <!doctype html>
 <html lang="en" data-site="{{ $siteKey }}">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width,initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <script>
         try {
             const savedTheme = localStorage.getItem('site-theme') || localStorage.getItem('public-theme') || (matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
             document.documentElement.dataset.theme = savedTheme;
         } catch (e) {}
     </script>
     <title>{{ $siteName }} — Find someone who feels like home</title>
     <meta name="description" content="Meaningful connections. Genuine profiles. A simple way to find your life partner.">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
     @vite(['resources/css/home/home.css', 'resources/js/home.js'])
 </head>
 <style>
     /* =========================================================
   APP DOWNLOAD / MOBILE APP CTA
========================================================= */

     .app-download-section {
         position: relative;
         width: 100%;
         min-height: 520px;

         display: grid;
         grid-template-columns: 46% 54%;
         align-items: center;

         overflow: hidden;

         padding: 55px 70px;

         background:
             linear-gradient(135deg,
                 color-mix(in srgb, var(--site-secondary) 90%, #140c20),
                 color-mix(in srgb, var(--site-primary) 55%, #21142f));

         color: #fff;
     }


     /* =========================================================
   LEFT SIDE
========================================================= */

     .app-download-content {
         position: relative;
         z-index: 2;
         max-width: 520px;
     }

     .app-qr {
         width: 90px;
         height: 90px;

         background: #fff;
         padding: 7px;

         border-radius: 3px;
         margin-bottom: 22px;
     }

     .app-qr img {
         width: 100%;
         height: 100%;
         object-fit: contain;
         display: block;
     }


     .app-download-content h2 {
         margin: 0 0 22px;

         font-family: "Outfit", sans-serif;
         font-size: clamp(27px, 3vw, 39px);
         line-height: 1.22;
         font-weight: 400;

         color: #fff;
     }

     .app-download-content h2 strong {
         font-weight: 700;
     }


     /* =========================================================
   STORE BUTTONS
========================================================= */

     .app-store-buttons {
         display: flex;
         flex-wrap: wrap;
         gap: 10px;

         margin-bottom: 25px;
     }

     .app-store-btn {
         height: 48px;

         display: inline-flex;
         align-items: center;
         gap: 9px;

         padding: 5px 13px;

         background: #050505;
         border: 1px solid rgba(255, 255, 255, 0.6);
         border-radius: 7px;

         color: #fff;
         text-decoration: none;

         transition:
             transform 0.2s ease,
             border-color 0.2s ease;
     }

     .app-store-btn:hover {
         transform: translateY(-2px);
         border-color: #fff;

         color: #fff;
     }

     .app-store-icon {
         flex: 0 0 auto;

         width: 27px;
         height: 29px;
     }

     .apple-icon {
         width: 26px;
     }

     .app-store-btn span {
         display: flex;
         flex-direction: column;

         line-height: 1;
     }

     .app-store-btn small {
         margin-bottom: 3px;

         font-size: 9px;
         letter-spacing: 0.2px;

         color: #fff;
     }

     .app-store-btn strong {
         font-size: 16px;
         font-weight: 500;

         color: #fff;
     }


     /* =========================================================
   DOWNLOAD DETAILS
========================================================= */

     .app-download-meta {
         margin-top: 10px;
     }

     .app-download-meta p {
         margin: 0 0 5px;

         font-size: 16px;
         color: rgba(255, 255, 255, 0.96);
     }

     .app-download-meta .download-count {
         margin-bottom: 12px;
         font-weight: 500;
     }


     .app-rating {
         display: flex;
         align-items: center;
         gap: 14px;

         margin-top: 5px;
     }

     .rating-number {
         font-family: "Outfit", sans-serif;

         font-size: 34px;
         line-height: 1;
         font-weight: 600;
     }

     .stars {
         margin-bottom: 3px;

         font-size: 31px;
         line-height: 1;

         letter-spacing: 1px;

         color: #ffc900;
     }

     .stars span {
         color: #d6d4dc;
     }

     .app-rating small {
         font-size: 13px;
         color: rgba(255, 255, 255, 0.9);
     }


     /* =========================================================
   RIGHT SIDE / PHONE MOCKUPS
========================================================= */

     .app-phone-showcase {
         position: relative;

         min-height: 410px;

         display: flex;
         justify-content: center;
         align-items: center;
     }


     /* Phone wrapper */

     .phone-mockup {
         position: absolute;
         z-index: 2;

         padding: 7px;

         background: #f4f4f4;

         border: 2px solid rgba(255, 255, 255, 0.55);
         border-radius: 31px;

         box-shadow:
             0 25px 60px rgba(0, 0, 0, 0.32),
             0 4px 15px rgba(0, 0, 0, 0.18);

         overflow: hidden;
     }

     .phone-mockup img {
         width: 100%;
         height: 100%;

         display: block;

         object-fit: cover;

         border-radius: 23px;
     }


     /* Main / left phone */

     .phone-mockup-main {
         width: 180px;
         height: 365px;

         left: 20%;
         top: 5px;

         transform: rotate(-1deg);
     }


     /* Secondary / right phone */

     .phone-mockup-secondary {
         width: 180px;
         height: 330px;

         right: 10%;
         bottom: 0;

         transform: rotate(1deg);
     }


     /* =========================================================
   DECORATIVE CIRCLES
========================================================= */

     .app-decoration {
         position: absolute;

         border-radius: 50%;

         pointer-events: none;
     }

     .app-decoration-one {
         width: 130px;
         height: 130px;

         right: 6%;
         top: 80px;

         background:
             color-mix(in srgb,
                 var(--site-accent) 35%,
                 transparent);
     }

     .app-decoration-two {
         width: 180px;
         height: 180px;

         left: 34%;
         bottom: -100px;

         background:
             color-mix(in srgb,
                 var(--site-primary) 25%,
                 transparent);
     }


     /* =========================================================
   TABLET
========================================================= */

     @media (max-width: 991px) {

         .app-download-section {
             grid-template-columns: 1fr 1fr;

             padding: 45px 35px;

             min-height: 500px;
         }

         .phone-mockup-main {
             width: 160px;
             height: 325px;

             left: 5%;
         }

         .phone-mockup-secondary {
             width: 155px;
             height: 290px;

             right: 0;
         }
     }


     /* =========================================================
   MOBILE
========================================================= */

     @media (max-width: 767px) {

         .app-download-section {
             display: flex;
             flex-direction: column;

             padding: 38px 20px 20px;

             text-align: center;
         }

         .app-download-content {
             width: 100%;
             max-width: 100%;
         }

         .app-qr {
             margin-left: auto;
             margin-right: auto;
         }

         .app-download-content h2 {
             font-size: 29px;
         }

         .app-store-buttons {
             justify-content: center;
         }

         .app-rating {
             justify-content: center;
         }


         /* phones */

         .app-phone-showcase {
             width: 100%;
             min-height: 370px;

             margin-top: 25px;
         }

         .phone-mockup-main {
             width: 155px;
             height: 315px;

             left: calc(50% - 155px);

             top: 0;
         }

         .phone-mockup-secondary {
             width: 145px;
             height: 275px;

             right: calc(50% - 150px);

             bottom: 5px;
         }

     }


     /* =========================================================
   SMALL MOBILE
========================================================= */

     @media (max-width: 420px) {

         .app-download-section {
             padding-left: 15px;
             padding-right: 15px;
         }

         .app-download-content h2 {
             font-size: 25px;
         }

         .app-store-btn {
             height: 45px;
             padding-left: 10px;
             padding-right: 10px;
         }

         .app-store-btn strong {
             font-size: 14px;
         }

         .stars {
             font-size: 24px;
         }

         .rating-number {
             font-size: 30px;
         }

         .app-phone-showcase {
             min-height: 330px;
         }

         .phone-mockup-main {
             width: 140px;
             height: 285px;

             left: calc(50% - 140px);
         }

         .phone-mockup-secondary {
             width: 130px;
             height: 250px;

             right: calc(50% - 135px);
         }
     }
 </style>

 <body class="public-site tenant-{{ \Illuminate\Support\Str::slug($siteKey) }}" style="
        --site-primary: {{ $sitePrimaryColor }};
        --site-secondary: {{ $siteSecondaryColor }};
        --site-accent: {{ $siteAccentColor }};
        --brand: {{ $sitePrimaryColor }};
        --deep: {{ $siteSecondaryColor }};
        --gold: {{ $siteAccentColor }};
    ">
     @include('partials.public-header')
     <main>
         <section class="hero" style="background-image: url('{{ asset($siteHeroBackground) }}');">
             <div class="hero-copy">
                 <h1>Find someone<br><em>who feels like home.</em></h1>
                 <p>Meaningful connections. Genuine profiles.<br>A simple way to find your life partner.</p>
                 <div class="hero-buttons">
                     <a class="solid public-cta public-cta-primary" href="#featured">
                         <i data-lucide="heart" aria-hidden="true"></i>Find Matches
                     </a>
                     <a class="outline public-cta public-cta-secondary" href="{{ route('login-form') }}#register">
                         <i data-lucide="user-plus" aria-hidden="true"></i>Register Free
                     </a>
                 </div>
                 <div class="trusted">
                     <span class="mini-faces">
                         <i></i>
                         <i></i>
                         <i></i>
                     </span>
                     <small>Trusted by thousands of<br>happy members <i data-lucide="heart" aria-hidden="true"></i></small>
                 </div>
             </div>
         </section>

         <form class="finder" id="search" action="{{ auth('member')->check() ? route('search-results') : route('login-form') . '#register' }}" method="get">
             @auth('member')
             <input type="hidden" name="_source" value="advanced">
             @endauth
             <label for="lookingFor">
                 <span>Looking for</span>
                 <span class="finder-control">
                     <i data-lucide="user-search" aria-hidden="true"></i>
                     <select id="lookingFor" name="looking_for">
                         <option value="Female">Bride</option>
                         <option value="Male">Groom</option>
                     </select>
                 </span>
             </label>
             <label for="ageFrom">
                 <span>Age</span>
                 <span class="finder-control">
                     <i data-lucide="calendar-range" aria-hidden="true"></i>
                     <span class="finder-age-fields">
                         <select id="ageFrom" name="partner_age_from" aria-label="Minimum age">
                             @for ($age = 18; $age <= 70; $age++)
                                 <option value="{{ $age }}" @selected($age===24)>{{ $age }}</option>
                                 @endfor
                         </select>
                         <span aria-hidden="true">to</span>
                         <select id="ageTo" name="partner_age_to" aria-label="Maximum age">
                             @for ($age = 18; $age <= 70; $age++)
                                 <option value="{{ $age }}" @selected($age===30)>{{ $age }}</option>
                                 @endfor
                         </select>
                     </span>
                 </span>
             </label>
             <label for="partnerReligion">
                 <span>Religion</span>
                 <span class="finder-control">
                     <i data-lucide="landmark" aria-hidden="true"></i>
                     <select id="partnerReligion" name="partner_religion">
                         <option value="">Any</option>
                         <option value="Hindu">Hindu</option>
                         <option value="Sikh">Sikh</option>
                         <option value="Christian">Christian</option>
                         <option value="Buddhist">Buddhist</option>
                         <option value="Muslim">Muslim</option>
                     </select>
                 </span>
             </label>
             <label for="cityName">
                 <span>City</span>
                 <span class="finder-control">
                     <i data-lucide="map-pin" aria-hidden="true"></i>
                     <select id="cityName" name="city_name">
                         <option value="">Any city</option>
                         @foreach($searchCities as $city)
                         <option value="{{ $city }}">{{ $city }}</option>
                         @endforeach
                     </select>
                 </span>
             </label>
             <button class="public-cta public-cta-primary" type="submit">
                 <i data-lucide="search" aria-hidden="true"></i>
                 <span>Search Matches</span>
             </button>
         </form>

         <section class="trust-strip">
             <article><i data-lucide="badge-check" aria-hidden="true"></i>
                 <div><b>100% Verified Profiles</b><small>Manually verified for your safety</small></div>
             </article>
             <article><i data-lucide="shield-check" aria-hidden="true"></i>
                 <div><b>Secure &amp; Private</b><small>Your privacy is our priority</small></div>
             </article>
             <article><i data-lucide="users" aria-hidden="true"></i>
                 <div><b>Thousands of Matches</b><small>New matches every day</small></div>
             </article>
             <article><i data-lucide="headset" aria-hidden="true"></i>
                 <div><b>24/7 Customer Support</b><small>We are here to help you</small></div>
             </article>
         </section>

         @if($featuredProfiles->isNotEmpty())
         <section class="matches wrap" id="featured" data-profile-slider aria-label="Verified members" aria-roledescription="carousel">
             <h2>Meet our verified members</h2>
             <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
             <div class="profile-slider-controls" hidden>
                 <button type="button" data-slider-prev aria-label="Previous profiles" aria-controls="verified-profile-track">←</button>
                 <button type="button" data-slider-next aria-label="Next profiles" aria-controls="verified-profile-track">→</button>
             </div>
             <div class="cards profile-slider-track" id="verified-profile-track" tabindex="0" aria-label="Verified profiles; use arrow keys to browse">
                 @foreach($featuredProfiles as $profile)
                 @php
                 $photoPath = 'photos/photo/' . basename($profile->photo);
                 $photoOrigin = config('site.sites')[$siteKey]['app_url'] ?? config('app.url');
                 $photoUrl = is_file(public_path($photoPath))
                 ? asset($photoPath)
                 : rtrim($photoOrigin, '/') . '/' . $photoPath;
                 @endphp
                 <article class="profile-card">
                     <div class="profile-photo">
                         <img src="{{ $photoUrl }}" alt="{{ $profile->full_name }}" loading="lazy">
                         <span>● Verified</span>
                     </div>
                     <div>
                         <b>{{ $profile->full_name }}@if($profile->age), {{ $profile->age }}@endif</b>
                         <small>{{ $profile->city_living_in }}<br>{{ $profile->occupation }}</small>
                     </div>
                 </article>
                 @endforeach
             </div><a class="more outline public-cta public-cta-secondary" href="{{ route('login-form') }}#register">View More Profiles</a>
         </section>
         @endif

         <section class="split wrap">
             <article class="how" id="how">
                 <h2>How it works</h2>
                 <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
                 <div class="three">
                     <div>
                         <i data-lucide="user-round-plus" aria-hidden="true"></i>
                         <b>1. Create Your Profile</b>
                         <small>Sign up and create your profile in just a few minutes.</small>
                     </div>
                     <div>
                         <i data-lucide="search" aria-hidden="true"></i>
                         <b>2. Discover Matches</b>
                         <small>Get matched with compatible profiles tailored for you.</small>
                     </div>
                     <div>
                         <i data-lucide="messages-square" aria-hidden="true"></i>
                         <b>3. Start a Conversation</b>
                         <small>Connect, chat and take the first step towards a beautiful journey.</small>
                     </div>
                 </div>
             </article>
             <article class="why">
                 <h2>Why choose {{ $siteName }}?</h2>
                 <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
                 <div class="why-grid">
                     <p><i data-lucide="badge-check" aria-hidden="true"></i><span><b>Verified &amp; Genuine</b><small>Every profile is manually verified</small></span></p>
                     <p><i data-lucide="eye-off" aria-hidden="true"></i><span><b>Privacy Controls</b><small>You are in control of your privacy</small></span></p>
                     <p><i data-lucide="sparkles" aria-hidden="true"></i><span><b>Smart Matching</b><small>Advanced matching for better connections</small></span></p>
                     <p><i data-lucide="users-round" aria-hidden="true"></i><span><b>Community Focused</b><small>Find matches from your community</small></span></p>
                     <p><i data-lucide="shield-check" aria-hidden="true"></i><span><b>Secure Contact Access</b><small>Connect only when comfortable</small></span></p>
                     <p><i data-lucide="headset" aria-hidden="true"></i><span><b>Dedicated Support</b><small>Friendly support whenever needed</small></span></p>
                 </div>
             </article>
         </section>

         <section class="stories wrap" id="stories">
             <h2>Real stories. Real happiness.</h2>
             <div class="ornament"><i data-lucide="heart" aria-hidden="true"></i></div>
             <div class="story-grid">
                 <article><img src="{{ asset('uploads/success-stories/story_1785477068_6a6c37cc05b8a.jpeg') }}" alt="Happy couple">
                     <div><b>Pooja &amp; Ankush</b><strong>Shimla, Himachal Pradesh</strong>
                         <p>“We met on {{ $siteName }} and instantly connected. Today, we are happily building our future together.”</p><small><i data-lucide="heart" aria-hidden="true"></i> Married on 12th Feb 2024</small>
                     </div>
                 </article>
                 <article><img src="{{ asset('uploads/gallery/photo_1787825379_6a900ce3763a3.jpeg') }}" alt="Happy couple">
                     <div><b>Megha &amp; Saurav</b><strong>Kangra, Himachal Pradesh</strong>
                         <p>“Thanks to {{ $siteName }}, we found not just a life partner but a best friend for life.”</p><small><i data-lucide="heart" aria-hidden="true"></i> Married on 5th Nov 2023</small>
                     </div>
                 </article>
             </div><a class="more outline public-cta public-cta-secondary" href="{{ route('success-stories') }}">Read More Success Stories</a>
         </section>

         @php
         $instagram = config('site.current.instagram', []);

         $enabled = (bool) ($instagram['enabled'] ?? false);
         $username = $instagram['username'] ?? null;
         $instagramUrl = $instagram['url'] ?? '#';

         $instagramItems = collect();

         if ($enabled) {
         $instagramItems = app(\App\Services\InstagramFeedService::class)
         ->getLatestMedia(6);
         }
         @endphp

         @if($enabled && $instagramItems->isNotEmpty())

         <section class="communities wrap">
             <h2>Latest from Instagram</h2>

             <div class="community-grid">

                 @foreach($instagramItems as $item)

                 @php
                 $mediaType = $item['media_type'] ?? '';
                 $productType = $item['media_product_type'] ?? '';

                 $isReel =
                 $mediaType === 'VIDEO'
                 && $productType === 'REELS';

                 $image =
                 $item['thumbnail_url']
                 ?? $item['media_url']
                 ?? null;

                 $permalink =
                 $item['permalink']
                 ?? $instagramUrl;

                 $caption =
                 $item['caption']
                 ?? 'View on Instagram';
                 @endphp

                 @if($image)

                 <a
                     href="{{ $permalink }}"
                     target="_blank"
                     rel="noopener noreferrer"
                     class="instagram-community-card"
                     style="--bg:url('{{ $image }}')">

                 </a>
                 @endif
                 @endforeach
             </div>
         </section>
         @endif


         {{--<section class="app-cta">
             <div class="phone"><i data-lucide="smartphone" aria-hidden="true"></i><small>{{ $siteName }}</small></div>
         <div>
             <h2>Take the next step towards<br>your happily ever after.</h2>
             <p>Create your free profile today and start your journey.</p>
         </div>
         @if($siteAndroidAppUrl)
         <a class="store" href="{{ $siteAndroidAppUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Download {{ $siteName }} from Google Play">
             <svg class="store-icon play-icon" viewBox="0 0 28 31" aria-hidden="true">
                 <path fill="#00d4ff" d="M2 2.2 16.3 15.5 2 28.8c-.6-.7-1-1.7-1-2.9V5.1c0-1.2.4-2.2 1-2.9Z" />
                 <path fill="#00e676" d="m2.8 1.5 17.5 10-4 4L2 2.2c.2-.3.5-.5.8-.7Z" />
                 <path fill="#ffcf3f" d="m16.3 15.5 4-4 5.1 2.9c1.5.8 1.5 2.3 0 3.2l-5.1 2.9-4-5Z" />
                 <path fill="#ff4b55" d="m2 28.8 14.3-13.3 4 5L2.8 29.5c-.3-.2-.6-.4-.8-.7Z" />
             </svg>
             <span>Get it on<b>Google Play</b></span>
         </a>
         @endif @if($siteIosAppUrl)
         <a class="store" href="{{ $siteIosAppUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Download {{ $siteName }} from the Apple App Store">
             <svg class="store-icon apple-icon" viewBox="0 0 24 24" aria-hidden="true">
                 <path fill="currentColor" d="M17.1 12.5c0-2.6 2.1-3.9 2.2-4-1.2-1.8-3.1-2-3.8-2-1.6-.2-3.1.9-3.9.9-.8 0-2-1-3.4-.9-1.7 0-3.4 1-4.3 2.6-1.9 3.2-.5 8 1.3 10.6.9 1.3 1.9 2.7 3.3 2.6 1.3-.1 1.8-.8 3.4-.8 1.6 0 2 .8 3.4.8 1.4 0 2.3-1.3 3.2-2.6 1-1.5 1.5-3 1.5-3.1-.1 0-2.9-1.1-2.9-4.1ZM14.4 4.8c.7-.9 1.2-2.1 1.1-3.3-1.1.1-2.4.7-3.2 1.6-.7.8-1.3 2-1.1 3.2 1.2.1 2.4-.6 3.2-1.5Z" />
             </svg>
             <span>Download on the<b>App Store</b></span>
         </a>
         @endif
         <a class="cta-white public-cta public-cta-primary" href="{{ route('login-form') }}#register">Create Free Profile
             <i class="cta-profile-icon" data-lucide="user-plus" aria-hidden="true"></i>
         </a>
         </section>--}}
         <section class="app-download-section reveal">

             {{-- LEFT CONTENT --}}
             <div class="app-download-content">

                 <div class="app-qr">
                     <img
                         src="{{ asset('assets/images/app/himrishtey-qr.png') }}"
                         alt="Download HimRishtey App QR Code">
                 </div>

                 <h2>
                     To speed up your partner search,<br>
                     download <strong>HimRishtey App</strong>
                 </h2>

                 <div class="app-store-buttons">

                     {{-- APP STORE --}}
                     <a
                         href="https://apps.apple.com/in/app/him-rishtey/id1669261836"
                         target="_blank"
                         rel="noopener noreferrer"
                         class="app-store-btn">
                         <svg
                             class="app-store-icon apple-icon"
                             viewBox="0 0 24 24"
                             aria-hidden="true">
                             <path
                                 fill="currentColor"
                                 d="M17.1 12.5c0-2.6 2.1-3.9 2.2-4-1.2-1.8-3.1-2-3.8-2-1.6-.2-3.1.9-3.9.9-.8 0-2-1-3.4-.9-1.7 0-3.4 1-4.3 2.6-1.9 3.2-.5 8 1.3 10.6.9 1.3 1.9 2.7 3.3 2.6 1.3-.1 1.8-.8 3.4-.8 1.6 0 2 .8 3.4.8 1.4 0 2.3-1.3 3.2-2.6 1-1.5 1.5-3 1.5-3.1-.1 0-2.9-1.1-2.9-4.1ZM14.4 4.8c.7-.9 1.2-2.1 1.1-3.3-1.1.1-2.4.7-3.2 1.6-.7.8-1.3 2-1.1 3.2 1.2.1 2.4-.6 3.2-1.5Z" />
                         </svg>

                         <span>
                             <small>Download on the</small>
                             <strong>App Store</strong>
                         </span>
                     </a>

                     {{-- GOOGLE PLAY --}}
                     <a
                         href="https://play.google.com/store/apps/details?id=com.himrishtey.app"
                         target="_blank"
                         rel="noopener noreferrer"
                         class="app-store-btn">
                         <svg
                             class="app-store-icon play-icon"
                             viewBox="0 0 28 31"
                             aria-hidden="true">
                             <path fill="#00d4ff"
                                 d="M2 2.2 16.3 15.5 2 28.8c-.6-.7-1-1.7-1-2.9V5.1c0-1.2.4-2.2 1-2.9Z" />
                             <path fill="#00e676"
                                 d="m2.8 1.5 17.5 10-4 4L2 2.2c.2-.3.5-.5.8-.7Z" />
                             <path fill="#ffcf3f"
                                 d="m16.3 15.5 4-4 5.1 2.9c1.5.8 1.5 2.3 0 3.2l-5.1 2.9-4-5Z" />
                             <path fill="#ff4b55"
                                 d="m2 28.8 14.3-13.3 4 5L2.8 29.5c-.3-.2-.6-.4-.8-.7Z" />
                         </svg>

                         <span>
                             <small>GET IT ON</small>
                             <strong>Google Play</strong>
                         </span>
                     </a>

                 </div>

                 <div class="app-download-meta">
                     <p>
                         HimRishtey® – Trusted Matrimony App
                     </p>

                     <p class="download-count">
                         Find your perfect match anywhere, anytime.
                     </p>

                     <div class="app-rating">
                         <span class="rating-number">4.2</span>

                         <div>
                             <div class="stars">
                                 ★ ★ ★ ★ <span>★</span>
                             </div>

                             <small>Based on Customer Reviews</small>
                         </div>
                     </div>
                 </div>

             </div>


             {{-- RIGHT MOCKUPS --}}
             <div class="app-phone-showcase">

                 <div class="app-decoration app-decoration-one"></div>
                 <div class="app-decoration app-decoration-two"></div>

                 <div class="phone-mockup phone-mockup-main">
                     <img
                         src="{{ asset('assets/images/app/profile-screen.png') }}"
                         alt="HimRishtey profile screen">
                 </div>

                 <div class="phone-mockup phone-mockup-secondary">
                     <img
                         src="{{ asset('assets/images/app/matches-screen.png') }}"
                         alt="HimRishtey matches screen">
                 </div>

             </div>

         </section>
     </main>
     @include('partials.public-footer')
 </body>

 </html>