<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body>
    <div style="text-align: center; padding: 50px;">
        <!-- SVG Illustration -->
        <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" fill="#F3F4F6" />
            <path d="M12 4.5C7.529 4.5 4 7.529 4 12s3.529 7.5 8 7.5 8-3.529 8-7.5-3.529-7.5-8-7.5z" fill="#E5E7EB" />
            <circle cx="9" cy="10" r="1" fill="#6B7280" />
            <circle cx="15" cy="10" r="1" fill="#6B7280" />
            <path d="M8.5 15c1 1.333 2.333 2 4 2s3-0.667 4-2" stroke="#6B7280" stroke-width="1.5"
                stroke-linecap="round" />
        </svg>
        <!-- Error Message -->
        <h1 style="font-size: 36px; color: #333; margin-top: 20px;">404</h1>
        <p style="font-size: 18px; color: #555; margin-top: 10px;">Oops! Page Not Found</p>
        <p style="font-size: 16px; color: #777; margin-top: 10px;">
            Sorry, the page you are looking for does not exist.
        </p>
        <a href="{{ url('/') }}" style="color: #4A90E2; font-size: 16px; margin-top: 20px; display: inline-block;">
            Go back to the homepage
        </a>
    </div>

</body>


</html>
