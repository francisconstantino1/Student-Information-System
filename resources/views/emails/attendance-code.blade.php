<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0046FF, #0033CC); color: #FFFFFF; padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
        <h1 style="margin: 0; font-size: 24px;">Attendance Code</h1>
    </div>
    
    <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-top: none; padding: 30px; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            An attendance code has been generated for your class session.
        </p>
        
        <div style="background: #F9FAFB; border: 2px solid #0046FF; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0;">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6B7280;">Your Attendance Code:</p>
            <div style="font-size: 32px; font-weight: bold; color: #0046FF; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                {{ $code }}
            </div>
        </div>
        
        <div style="background: #F9FAFB; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6B7280;"><strong>Session:</strong> {{ $sessionName }}</p>
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6B7280;"><strong>Time:</strong> {{ $sessionTime }}</p>
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6B7280;"><strong>Date:</strong> {{ $date }}</p>
            <p style="margin: 0; font-size: 14px; color: #6B7280;"><strong>Expires:</strong> {{ $expiresAt }}</p>
        </div>
        
        <p style="font-size: 16px; margin-top: 30px;">
            Please enter this code in your Student Information System to mark your attendance. The code will expire when the session ends.
        </p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #E5E7EB; text-align: center;">
            <p style="font-size: 12px; color: #6B7280; margin: 0;">
                This is an automated message from the Student Information System.
            </p>
        </div>
    </div>
</body>
</html>

