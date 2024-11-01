<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body>
    <div style="text-align: center; padding: 50px;">
        <!-- SVG Illustration for 405 Error -->
        <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#FBBF24" />
            <path d="M12 7v6M12 16h.01" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" />
        </svg>
        <!-- Error Message -->
        <h1 style="font-size: 36px; color: #333; margin-top: 20px;">405</h1>
        <p style="font-size: 18px; color: #555; margin-top: 10px;">Oops! Method Not Allowed</p>
        <p style="font-size: 16px; color: #777; margin-top: 10px;">
            The request method is not supported for this page.
        </p>
        <a href="{{ url('/') }}" style="color: #4A90E2; font-size: 16px; margin-top: 20px; display: inline-block;">
            Go back to the homepage
        </a>
    </div>

</body>


</html>
