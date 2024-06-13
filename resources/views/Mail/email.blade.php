<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Mail Box</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
         /* Reset and Global Styles */
 body {
    margin: 0 auto;
    padding: 0;
    height: 100%;
    width: 100%;
    font-family: 'Lato', sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 1.8;
    color: rgba(0, 0, 0, .4);
}

* {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

div[style*="margin: 16px 0"] {
    margin: 0;
}

table,
td {
    mso-table-lspace: 0;
    mso-table-rspace: 0;
    border-spacing: 0;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 0 auto;
}

img {
    -ms-interpolation-mode: bicubic;
}

a {
    text-decoration: none;
}

.im {
    color: inherit;
}

img.g-img+div {
    display: none;
}

/* Media Queries for Responsive Design */
@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
    u~div .email-container {
        min-width: 320px;
    }
}

@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
    u~div .email-container {
        min-width: 375px;
    }
}

@media only screen and (min-device-width: 414px) {
    u~div .email-container {
        min-width: 414px;
    }
}


.bg_white {
    background: #ffffff;
}

.bg_light {
    background: #fafafa;
}

.bg_black {
    background: #000000;
}

.bg_dark {
    background: rgba(0, 0, 0, .8);
}

.email-section {
    padding: 2.5em;
}

/* Typography */
h1,
h2,
h3,
h4,
h5,
h6 {
    color: #000000;
    margin-top: 0;
    font-weight: 400;
}

.hero .text {
    color: rgba(0, 0, 0, .3);
}

.hero .text h2 {
    color: #000;
    font-size: 40px;
    margin-bottom: 10px;
    font-weight: 400;
    line-height: 1.4;
}

.hero .text h3 {
    color: #000;
    font-size: 24px;
    text-align: justify;
    font-weight: 400;
}

.hero .text h2 span {
    font-weight: 600;
}

/* Button Styles */
.custom-btn-danger {
    display: inline-block;
    font-weight: 400;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    background-color: #dc3545;
    border: 1px solid #dc3545;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-btn-danger:hover {
    color: #fff;
    background-color: #000000;
    /* Change to black first */
    border-color: #000000;
    /* Change to black first */
}

.custom-btn-danger:focus,
.custom-btn-danger.focus {
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
}

.custom-btn-danger:disabled,
.custom-btn-danger.disabled {
    opacity: 0.65;
}

.custom-btn-danger:not(:disabled):not(.disabled):active,
.custom-btn-danger:not(:disabled):not(.disabled).active,
.show>.custom-btn-danger.dropdown-toggle {
    color: #fff;
    background-color: #bd2130;
    border-color: #b21f2d;
}

.custom-btn-danger:not(:disabled):not(.disabled):active:focus,
.custom-btn-danger:not(:disabled):not(.disabled).active:focus,
.show>.custom-btn-danger.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
}

    </style>

</head>

<body width="100%" style="margin: 50px;  !important; mso-line-height-rule: exactly; background-color: #ffff;">


    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
        <!-- BEGIN BODY -->
        <table aria-hidden="true">
            <tr>
                <td class="hero bg_white" style="padding: 3em 0 2em 0;">
                    <img src="https://pngimg.com/d/gmail_logo_PNG4.png" alt=""
                        style="width: 200px; max-width: 300px; margin: auto; display: block;">
                </td>
            </tr><!-- end tr -->
            <tr>
                <td class="hero bg_white" style="padding: 2em 0 4em 0;">
                    <table>
                            <th>
                                <div class="text" style="padding: 0 2.5em; text-align: center; margin-top: -50px;">
                                    <h2>Please verify your email</h2>
                                    <h3 style="margin-top: 10px; margin-bottom: 20px;">Hi {{ $user->name }}, <br>You're almost set to start enjoying. Simply click the
                                        link below to verify your email address and get started. The link expires in 5 minutes.</h3>
                                    <a href="{{ $verificationUrl }}" class="custom-btn-danger"
                                        style="width: 200px; font-size:25px;">Confirm</a>
                                </div>
                            </th>
                    </table>
                </td>
            </tr>
    </div>

</body>

</html>
