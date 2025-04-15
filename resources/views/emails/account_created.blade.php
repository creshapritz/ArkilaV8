<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ARKILA</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="500px" style="background-color: #ffffff; border-radius: 10px; padding: 20px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <img src="https://yourwebsite.com/logo.png" alt="ARKILA Logo" style="max-width: 150px;">
                        </td>
                    </tr>

                    <!-- Welcome Message -->
                    <tr>
                        <td align="center">
                            <h2 style="color: #333;">Welcome to <span style="color: #ff7f00;">ARKILA!</span></h2>
                            <p style="color: #666; font-size: 16px;">Your account has been successfully created.</p>
                        </td>
                    </tr>

                    <!-- User Details -->
                    <tr>
                        <td style="padding: 15px 20px;">
                            <p style="font-size: 14px; color: #333;"><strong>Email:</strong> {{ $email }}</p>
                            <p style="font-size: 14px; color: #333;"><strong>Temporary Password:</strong> <span style="background-color: #eee; padding: 5px; border-radius: 5px;">{{ $password }}</span></p>
                        </td>
                    </tr>

                    <!-- Login Button -->
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <a href="{{ $loginUrl }}" 
                               style="background: #ff7f00; color: #fff; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                                Login Now
                            </a>
                        </td>
                    </tr>

                    <!-- Note -->
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <p style="font-size: 12px; color: #666;">Please change your password after logging in for security reasons.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px; font-size: 12px; color: #999;">
                            &copy; 2025 ARKILA. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
