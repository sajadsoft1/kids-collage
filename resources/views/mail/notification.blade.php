@php $primaryColor = '#3d85b8'; @endphp
<html lang="fa" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>{{ $title }}</title>

    <style type="text/css">
        div,
        p,
        a,
        li,
        td {
            -webkit-text-size-adjust: none;
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .ReadMsgBody {
            width: 100%;
            background-color: #ffffff;
        }

        .ExternalClass {
            width: 100%;
            background-color: #ffffff;
        }

        body {
            width: 100%;
            height: 100%;
            background-color: {{ $primaryColor }}d4;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        html {
            width: 100%;
            background-color: #ffffff;
        }

        @font-face {
            font-family: 'proxima_novalight';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-light-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'proxima_nova_rgregular';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-regular-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'proxima_novasemibold';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-semibold-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'proxima_nova_rgbold';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-bold-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'proxima_novathin';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-thin-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'proxima_novaextrabold';
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.eot');
            src: url('http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.eot?#iefix') format('embedded-opentype'), url('http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.woff2') format('woff2'), url('http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.woff') format('woff'), url('http://rocketway.net/themebuilder/products/font/proximanova-extrabold-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }


        p {
            padding: 0 !important;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            margin-left: 0 !important;
        }

        .hover:hover {
            opacity: 0.85;
            filter: alpha(opacity=85);
        }

        .image73 img {
            width: 73px;
            height: auto;
        }

        .image42 img {
            width: 42px;
            height: auto;
        }

        .image400 img {
            width: 400px;
            height: auto;
        }

        .icon49 img {
            width: 49px;
            height: auto;
        }

        .image113 img {
            width: 113px;
            height: auto;
        }

        .image70 img {
            width: 70px;
            height: auto;
        }

        .image67 img {
            width: 67px;
            height: auto;
        }

        .image80 img {
            width: 80px;
            height: auto;
        }

        .image35 img {
            width: 35px;
            height: auto;
        }

        .icon49 img {
            width: 49px;
            height: auto;
        }
    </style>


    <style type="text/css">
        @media only screen and (max-width: 479px) {
            body {
                width: auto !important;
            }

            table[class=full] {
                width: 100% !important;
                clear: both;
            }

            table[class=mobile] {
                width: 100% !important;
                padding-left: 30px;
                padding-right: 30px;
                clear: both;
            }

            table[class=fullCenter] {
                width: 100% !important;
                text-align: center !important;
                clear: both;
            }

            td[class=fullCenter] {
                width: 100% !important;
                text-align: center !important;
                clear: both;
            }

            *[class=erase] {
                display: none;
            }

            *[class=buttonScale] {
                float: none !important;
                text-align: center !important;
                display: inline-block !important;
                clear: both;
            }

            .image400 img {
                width: 100% !important;
                height: auto;
            }
        }

        }
    </style>


</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix">

    <div class="ui-sortable" id="sort_them">
        <!-- Notification 4  -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full">
            <tr>
                <td align="center"
                    style="-webkit-background-size: cover; background-size: cover; background-position: center center; background-repeat: no-repeat;"
                    id="not4ChangeBG">


                    <!-- Mobile Wrapper -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
                        <tr>
                            <td width="100%" align="center">

                                <!-- SORTABLE -->
                                <div class="sortable_inner ui-sortable">

                                    <!-- Space -->
                                    <table width="400" border="0" cellpadding="0" cellspacing="0" align="center"
                                        class="full" object="drag-module-small">
                                        <tr>
                                            <td width="100%" height="50"></td>
                                        </tr>
                                    </table><!-- End Space -->

                                    <!-- Space -->
                                    <table width="400" border="0" cellpadding="0" cellspacing="0" align="center"
                                        class="full" object="drag-module-small">
                                        <tr>
                                            <td width="100%" height="50"></td>
                                        </tr>
                                    </table><!-- End Space -->

                                    <!-- Main -->
                                    <table width="400" border="0" cellpadding="0" cellspacing="0" align="center"
                                        class="full"
                                        style="-webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px; box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);">
                                        <tr>
                                            <td width="100%"
                                                style="border-top-left-radius: 6px; border-top-right-radius: 6px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; background-color: rgb(255, 255, 255);"
                                                bgcolor="#ffffff">

                                                <!-- Start Top -->
                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="{{ $primaryColor }}"
                                                    object="drag-module-small"
                                                    style="border-top-right-radius: 6px; border-top-left-radius: 6px; background-color: {{ $primaryColor }};">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <!-- Header Text -->
                                                            <table width="280" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="35"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" width="100%"
                                                                        style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 18px; color: rgb(255, 255, 255); line-height: 26px; font-weight: 700;"
                                                                        class="fullCenter">
                                                                        {{ $title }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="100%" height="10"
                                                                        style="font-size: 1px; line-height: 1px;">&nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                                <!-- Image 113px -->
                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="{{ $primaryColor }}"
                                                    style="background-color: {{ $primaryColor }}; background-position: 50% 100%; background-repeat: repeat no-repeat; background-image: url({{ asset('http://kids-collage.test/assets/images/default/overlay1.png') }});"
                                                    object="drag-module-small">
                                                    <tr>
                                                        <td width="400" height="15"
                                                            style="font-size: 1px; line-height: 1px;">&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="400" valign="middle"
                                                            style="text-align: center; line-height: 1px;"
                                                            align="center">

                                                            <table width="100" border="0" cellpadding="0"
                                                                cellspacing="0" align="center" class="mobile">
                                                                <tr>
                                                                    <td width="100" height="35" align="center"
                                                                        class="image113">
                                                                        <a href="#">
                                                                        </a><a href="#"
                                                                            style="text-decoration: none;"><img
                                                                                src="{{ asset('http://kids-collage.test/assets/images/default/user-avatar.png') }}"
                                                                                width="113" alt=""
                                                                                border="0"></a>

                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table><!-- End Image 113px -->

                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="30"
                                                                        style="font-size: 1px; line-height: 1px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" width="100%"
                                                                        style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 13px; color: rgb(95, 106, 116); line-height: 24px; font-weight: 400;"
                                                                        class="fullCenter">
                                                                        {!! nl2br(e($subtitle)) !!}
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="30"
                                                                        style="font-size: 1px; line-height: 1px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" width="100%"
                                                                        style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 13px; color: rgb(95, 106, 116); line-height: 24px; font-weight: 400;"
                                                                        class="fullCenter">
                                                                        {!! $body !!}
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="35"
                                                                        style="font-size: 1px; line-height: 1px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" align="center">
                                                                        <table border="0" cellpadding="0"
                                                                            cellspacing="0" align="center"
                                                                            class="buttonScale">
                                                                            <tr>
                                                                                <td align="center" height="40"
                                                                                    bgcolor="{{ $primaryColor }}"
                                                                                    style="border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; padding-left: 25px; padding-right: 25px; font-family: 'Open Sans', Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); font-size: 15px; font-weight: 700; line-height: 1px; background-color: {{ $primaryColor }};">

                                                                                    @if ($cta && isset($cta['url']) && isset($cta['label']))
                                                                                        <a href="{{ $cta['url'] }}"
                                                                                            style="color: rgb(255, 255, 255); text-decoration: none; width: 100%;">
                                                                                            {{ $cta['label'] }}
                                                                                        </a>
                                                                                    @endif

                                                                                </td>
                                                                            </tr>
                                                                        </table>

                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                                {{-- <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="20"
                                                                        style="font-size: 1px; line-height: 1px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" width="100%"
                                                                        style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 13px; color: rgb(95, 106, 116); line-height: 24px; font-weight: 400;"
                                                                        class="fullCenter">
                                                                        or do something else <a href="#"
                                                                            style="color: rgb(61, 133, 184);">here</a>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table> --}}

                                                <table width="400" border="0" cellpadding="0" cellspacing="0"
                                                    align="center" class="mobile" bgcolor="#ffffff"
                                                    object="drag-module-small"
                                                    style="border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; background-color: rgb(255, 255, 255);">
                                                    <tr>
                                                        <td width="100%" valign="middle" align="center">

                                                            <table width="300" border="0" cellpadding="0"
                                                                cellspacing="0" align="center"
                                                                style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                                class="fullCenter">
                                                                <tr>
                                                                    <td width="100%" height="40"></td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>

                                            </td>
                                        </tr>
                                    </table><!-- End Main -->

                                    <!-- CopyRight -->
                                    <table width="352" border="0" cellpadding="0" cellspacing="0"
                                        align="center" class="full" object="drag-module-small">
                                        <tr>
                                            <td width="100%" height="25"></td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" width="100%"
                                                style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); font-size: 12px; font-weight: 400; line-height: 18px;"
                                                class="fullCenter">
                                                @if ($rtl)
                                                    اگر در کلیک کردن روی دکمه بالا مشکل دارید، می‌توانید لینک زیر را کپی
                                                    کرده و در مرورگر خود جای‌گذاری
                                                    کنید
                                                @else
                                                    If you're having trouble clicking the button, copy and paste the URL
                                                    below into your web browser
                                                @endif
                                                <br>

                                                {{ $cta['url'] }}
                                            </td>
                                        </tr>
                                    </table>

                                    <table width="352" border="0" cellpadding="0" cellspacing="0"
                                        align="center" class="full" object="drag-module-small">
                                        <tr>
                                            <td width="100%" height="20"
                                                style="font-size: 1px; line-height: 1px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle" width="100%"
                                                style="text-align: center; font-family: 'Open Sans', Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); font-size: 12px; font-weight: 400; line-height: 18px;"
                                                class="fullCenter">
                                                <!--subscribe--><a href="https://segmento.ir"
                                                    style="color: rgb(255, 255, 255);">{{ config('app.url') }}</a>
                                                <!--unsub-->
                                            </td>
                                        </tr>
                                    </table>

                                    <table width="352" border="0" cellpadding="0" cellspacing="0"
                                        align="center" class="full" object="drag-module-small">
                                        <tr>
                                            <td width="100%" height="60"
                                                style="font-size: 1px; line-height: 1px;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" height="1"
                                                style="font-size: 1px; line-height: 1px;">&nbsp;
                                            </td>
                                        </tr>
                                    </table><!-- End CopyRight -->
                                </div>

                            </td>
                        </tr>
                    </table>

    </div>
    </td>
    </tr>
    </table><!-- End Notification 4 -->
    </div>
</body>

</html>
