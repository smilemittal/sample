<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    @stack('styles')
    <style>
        th {
            text-align: left !important;
        }

    </style>
</head>

<body style="font-style: normal;padding: 0px;font-family: 'Roboto'; margin: 0px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 20px 0px; background-color:#e2e2e2;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <table width="600" border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="margin: 30px 0; border-radius: 6px;">
                        <tbody>
                            <tr>
                                <td class="td container" style="width: 600px;min-width: 600px; box-shadow: #2a2a2a 0px 3px 6px, #686868 0px 3px 6px; border-radius: 6px; margin:40px 0;">
                                    <!-- Header -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding: 10px; background-color: #000; border-top-left-radius: 6px; border-top-right-radius:6px;">
                                                                    <table width="100%" border="0" cellspacing="0"
                                                                        cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="125" style="width: 180px;">
                                                                                    <table width="100%" border="0"
                                                                                        cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td
                                                                                                    style="text-align: center; color:#fff;">
                                                                                                    @if ($header_image)
                                                                                                        <a
                                                                                                            href="{{ url('/') }}">
                                                                                                            <img src="{{ $header_image }}"
                                                                                                                alt=""
                                                                                                                style="max-width: 100%; width: 50%; height: auto;">
                                                                                                        </a>
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="width:100%; height:5px; background-color:#1ae5ae;"></div>
                                    <!-- Email Body -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                                        bgcolor="#fff">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding: 50px 20px;background: #fff;">
                                                                    <table width="100%" border="0" cellspacing="0"
                                                                        cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="email-header"
                                                                                    style="font-weight:bold; font-size:{{ $header_text_size }}">
                                                                                    <div>
                                                                                        <div
                                                                                            style="display:inline-block">

                                                                                        </div>
                                                                                        <div
                                                                                            style="display:inline-block;vertical-align:middle; color:#0C0C0C;padding-bottom:20px">
                                                                                            {!! $header_text !!}
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>

                                                                            <!-- Email Body -->
                                                                            <tr
                                                                                style="font-size:{{ $body_text_size }}">
                                                                                <td class="email-body-content"
                                                                                    width="100%" cellpadding="0"
                                                                                    cellspacing="0">
                                                                                    <table class="email-inner-body"
                                                                                        align="center" width="570"
                                                                                        cellpadding="0" cellspacing="0">
                                                                                        <!-- Body content -->
                                                                                        <tr>
                                                                                            <td
                                                                                                class="email-content-cell">
                                                                                                @if (isset($content))
                                                                                                    {!! $content !!}
                                                                                                @endif

                                                                                                @yield('content')

                                                                                                <div style="font-weight: 500; font-size: 18px margin-top: 15px;">Regards</div>
                                                                                                <div style="font-weight: bold; color: #1ae5ae;">Xme Support</div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>

                                                                            <!--Regards Content-->
                                                                            {{-- <tr>
                                                                                <td
                                                                                    style="font-size: 20px;padding-top:20px">
                                                                                    <div>
                                                                                        <div
                                                                                            style="display:inline-block">

                                                                                        </div>
                                                                                        <div
                                                                                            style="display:inline-block;vertical-align:middle;">
                                                                                            {{ __('email.Regards') }}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div>
                                                                                        <div
                                                                                            style="display:inline-block">

                                                                                        </div>
                                                                                        <div
                                                                                            style="display:inline-block;vertical-align:middle; color:#0C0C0C;">
                                                                                            Test
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr> --}}

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="width:100%; height:5px; background-color:#1ae5ae;"></div>
                                    <!-- Footer -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom-left-radius: 6px; border-bottom-right-radius:6px;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding: 25px 20px;background-color: #000 ; border-bottom-left-radius: 6px; border-bottom-right-radius:6px;">
                                                                    <table width="100%" border="0" cellspacing="0"
                                                                        cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="125" style="width: 180px;">
                                                                                    <table width="100%" border="0"
                                                                                        cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td
                                                                                                    style="text-align: center;color: #fff; font-weight:bold; font-size: 12px;">
                                                                                                    {!! $footer_text !!}. All Right Reserved
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
