

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MiColega - Empower Your Learning</title>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            <!-- Navigation Bar -->
            <nav class="bg-white shadow-md py-4">
                <div class="container mx-auto flex justify-between items-center px-4">
                    {{-- <div class="text-xl font-semibold text-gray-800">
                        MiColega
                    </div> --}}
                    <img src="{{ asset('images/logoMiColega.jpeg') }}" alt="Logo" class="block h-12 w-auto" />
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" 
                        class="px-4 py-2 border border-black text-gray-600 rounded-full transition duration-200"
                        style="transition: all 0.2s; border-color: purple;" 
                        onmouseover="this.style.backgroundColor='#7F00FF'; this.style.color='white';" 
                        onmouseout="this.style.backgroundColor=''; this.style.color='';">
                         Log in
                     </a>
                     <a href="{{ route('register') }}" 
                        class="px-4 py-2 border border-black text-gray-600 rounded-full transition duration-200"
                        style="transition: all 0.2s; border-color: purple;" 
                        onmouseover="this.style.backgroundColor='#7F00FF'; this.style.color='white';" 
                        onmouseout="this.style.backgroundColor=''; this.style.color='';">
                         Register
                     </a>
                     
                     
                     
</div>


</div>
</nav>

<!-- Main Content -->
<main class="flex-grow container mx-auto px-4 py-6">
    <!-- Hero Section -->
    <section class="text-center mb-10">
        <h1 class="text-4xl font-bold mb-4">Empower Your Learning at APIIT</h1>
        <p class="text-lg text-gray-600 mb-6">
            Join a community of motivated students and skilled peer-tutors to elevate your academic journey.
        </p>
        <img src="/images/mainmicolega.avif" alt="Peer learning illustration" class="mx-auto mb-8 rounded-lg shadow-md">
        <a href="{{ route('register') }}" 
   class="text-white px-6 py-3 rounded-md font-semibold transition" 
   style="background-color: #7F00FF;" 
    onmouseover="this.style.backgroundColor='white'; this.style.color='#6B00E0';" 
    onmouseout="this.style.backgroundColor='#6B00E0'; this.style.color='white';">
    Register Now
</a>


    </section>

    <!-- Why Choose MiColega Section -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
<!-- Benefit 1 -->
<div class="flex flex-col items-center text-center">
<img src="/images/girl.avif" alt="Interactive learning" class="w-32 h-32 mb-4 rounded-full shadow-md object-cover">
<h2 class="text-2xl font-semibold mb-2">Interactive Peer Learning</h2>
<p class="text-gray-600">
Collaborate with fellow students through interactive courses, study groups, and tutoring sessions tailored to your academic needs.
</p>
</div>
<!-- Benefit 2 -->
<div class="flex flex-col items-center text-center">
<img src="/images/woman.avif" alt="Expert peer-tutors" class="w-32 h-32 mb-4 rounded-full shadow-md object-cover">
<h2 class="text-2xl font-semibold mb-2">Expert Peer-Tutors</h2>
<p class="text-gray-600">
Learn from experienced peer-tutors who understand your courses and can provide personalized guidance and mentorship.
</p>
</div>
<!-- Benefit 3 -->
<div class="flex flex-col items-center text-center">
<img src="/images/groupsession.jpg" alt="Skill development" class="w-32 h-32 mb-4 rounded-full shadow-md object-cover">
<h2 class="text-2xl font-semibold mb-2">Skill Development</h2>
<p class="text-gray-600">
Gain valuable skills through workshops, networking events, and hands-on projects that prepare you for your future career.
</p>
</div>
<!-- Benefit 4 -->
<div class="flex flex-col items-center text-center">
<img src="/images/blackhair.webp" alt="Seamless integration" class="w-32 h-32 mb-4 rounded-full shadow-md object-cover">
<h2 class="text-2xl font-semibold mb-2">Seamless Integration</h2>
<p class="text-gray-600">
Our platform integrates smoothly with your APIIT schedule, making it easy to manage classes, meetings, and study sessions.
</p>
</div>
</section>

<section class="bg-white py-8">
    <h2 class="text-3xl font-bold text-center mb-6">Let's Navigate through the Platform Before You Begin..!</h2>
    <div class="container mx-auto space-y-6 px-4">
      <!-- Dashboard -->
      <div class="rounded-lg shadow-md overflow-hidden">
        <img src="/images/Dash-word.png" alt="Walkthrough of the Dashboard" class="w-full h-72 object-cover">
        <p class="text-center text-gray-600 py-2">Walkthrough of the Dashboard</p>
      </div>
      <!-- Sessions -->
      <div class="rounded-lg shadow-md overflow-hidden">
        <img src="/images/sessions-word.png" alt="Walkthrough of the Sessions" class="w-full h-72 object-cover">
        <p class="text-center text-gray-600 py-2">Walkthrough of the Sessions</p>
      </div>
      <!-- Teach -->
      <div class="rounded-lg shadow-md overflow-hidden">
        <img src="/images/teach-word.png" alt="Walkthrough of the Teach Dashboard" class="w-full h-72 object-cover">
        <p class="text-center text-gray-600 py-2">Walkthrough of the Teach Dashboard</p>
      </div>
      <!-- Group Sessions -->
      <div class="rounded-lg shadow-md overflow-hidden">
        <img src="/images/Group-word.png" alt="Walkthrough of the Group Sessions" class="w-full h-72 object-cover">
        <p class="text-center text-gray-600 py-2">Walkthrough of the Group Sessions</p>
      </div>
    </div>
  </section>
  


    <!-- Call to Action -->
    <section class="text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Excel Together?</h2>
        <p class="text-lg text-gray-600 mb-6">
            Sign up today to get access to a supportive community, tailored resources, and endless opportunities to grow.
        </p>
        <a href="{{ route('register') }}" 
        class="text-white px-6 py-3 rounded-md font-semibold transition" 
        style="background-color: #6B00E0; color: white;" 
        onmouseover="this.style.backgroundColor='white'; this.style.color='#6B00E0';" 
        onmouseout="this.style.backgroundColor='#6B00E0'; this.style.color='white';">
         Join Now
     </a>
     
    </section>
</main>

<!-- Footer -->
<footer class="bg-gray-200 py-4 text-center text-gray-600">
    © {{ date('Y') }} MiColega. All rights reserved.
</footer>
</div>
</body>
</html>

