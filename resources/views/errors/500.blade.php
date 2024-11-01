<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body>
    <div style="text-align: center; padding: 50px;">
        <!-- SVG Illustration for 500 Error -->
        <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#F87171" />
            <path d="M8 8l8 8M8 16L16 8" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" />
        </svg>
        <!-- Error Message -->
        <h1 style="font-size: 36px; color: #333; margin-top: 20px;">500</h1>
        <p style="font-size: 18px; color: #555; margin-top: 10px;">Oops! Something went wrong on our end.</p>
        <p style="font-size: 16px; color: #777; margin-top: 10px;">
            Please try refreshing the page or come back later.
        </p>
        <a href="{{ url('/') }}" style="color: #4A90E2; font-size: 16px; margin-top: 20px; display: inline-block;">
            Go back to the homepage
        </a>
    </div>

</body>


</html>
