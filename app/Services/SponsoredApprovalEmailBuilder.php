<?php

namespace App\Services;

class SponsoredApprovalEmailBuilder
{
  public static function subject(): string
  {
    return 'Application Approved – Global Conscious Food Systems Summit (Sponsored Participation)';
  }

  /**
   * HTML body for sponsored-ticket approval (from organizer template).
   *
   * @param  string  $safeName  HTML-escaped participant name
   */
  public static function html(string $safeName): string
  {
    $summitUrl = htmlspecialchars('https://www.consciousfoodsummit.com/', ENT_QUOTES, 'UTF-8');
    $yourStayUrl = htmlspecialchars(
      'https://www.consciousfoodsummit.com/your-stay-in-bhutan',
      ENT_QUOTES,
      'UTF-8'
    );
    $visaFormPdfUrl = htmlspecialchars(
      'https://www.doi.gov.bt/wp-content/uploads/2025/07/visa-application-form.pdf',
      ENT_QUOTES,
      'UTF-8'
    );
    $visaDetailsFormUrl = htmlspecialchars(
      'https://docs.google.com/forms/d/e/1FAIpQLSdC4lIGKesYCaJG9BMh43y0stsxpEx_e7QXZpL6VK8Jlqi6GA/viewform?usp=header',
      ENT_QUOTES,
      'UTF-8'
    );
    $neemaEmail = htmlspecialchars('neema.tamang@bhutanmindfulness.com', ENT_QUOTES, 'UTF-8');
    $pemaEmail = htmlspecialchars('pema.tshogyal@bhutanmindfulness.com', ENT_QUOTES, 'UTF-8');
    $linkStyle = 'color: #2563eb; text-decoration: underline;';

    return <<<HTML
<div style="font-family: Arial, Helvetica, sans-serif; color: #111827; background: #ffffff; padding: 24px;">
    <div style="max-width: 760px; margin: 0 auto;">
        <div style="padding: 18px; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 14px; margin-bottom: 18px;">
            <p style="margin: 0; font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; font-weight: 700;">
                <a href="{$summitUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle} color: #6b7280;">Global Conscious Food Systems Summit</a>
            </p>
            <p style="margin: 6px 0 0 0; font-size: 20px; font-weight: 800; color: #0d385a;">
                Sponsored Participation — Application Approved
            </p>
        </div>

        <p style="margin: 0 0 12px 0;">Dear {$safeName},</p>

        <p style="margin: 0 0 12px 0; line-height: 1.6;">
            Warm greetings from the Organizing Team of the
            <a href="{$summitUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}">Global Conscious Food Systems Summit</a>.
        </p>

        <div style="margin: 14px 0; padding: 16px; border: 2px solid #729430; background: #f0f7e8; border-radius: 12px;">
            <p style="margin: 0; line-height: 1.6; font-weight: 700;">
                We are delighted to inform you that your application has been <strong>successfully approved</strong> to join the
                <a href="{$summitUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}"><strong>Global Conscious Food Systems Summit</strong></a>, to be held from 31 August to 4 September in Paro, Bhutan.
            </p>
        </div>

        <p style="margin: 0 0 12px 0; line-height: 1.6;">
            This message outlines the steps required to onboard you to the Summit. Please review carefully and take the necessary actions.
        </p>

        <div style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
            <div style="background: #0d385a; color: #ffffff; padding: 12px 16px;">
                <p style="margin: 0; font-weight: 800;">Passport copy</p>
            </div>
            <div style="background: #ffffff; padding: 14px 16px;">
                <p style="margin: 0; line-height: 1.6;">
                    Please immediately send us a scanned copy of your valid passport in reply to this message, copying
                    Neema Tamang (<a href="mailto:{$neemaEmail}" style="{$linkStyle}">{$neemaEmail}</a>)
                    and Pema Tshogyal (<a href="mailto:{$pemaEmail}" style="{$linkStyle}">{$pemaEmail}</a>).
                </p>
            </div>
        </div>

        <div style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
            <div style="background: #111827; color: #ffffff; padding: 12px 16px;">
                <p style="margin: 0; font-weight: 800;">Visa application</p>
            </div>
            <div style="background: #ffffff; padding: 14px 16px;">
                <p style="margin: 0 0 10px 0; line-height: 1.6;">
                    You are invited to proceed with your visa application. Kindly ensure you have a valid email address for correspondence and prepare the following required documents:
                </p>
                <ul style="margin: 0; padding-left: 18px; line-height: 1.7;">
                    <li>Completed and signed <a href="{$visaFormPdfUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}"><strong>Form VISA2025</strong></a></li>
                    <li>Copy of your passport</li>
                    <li>Digital passport-sized photograph</li>
                    <li>Fill up the <a href="{$visaDetailsFormUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}"><strong>Applicant Visa Details Submission</strong></a> form and upload the above documents in the form</li>
                </ul>
                <p style="margin: 12px 0 0 0; line-height: 1.6;">
                    <strong>Please note</strong> that the Sustainable Development Fee (SDF) waiver for this Summit is valid for 10 days starting from 28 August.
                    Therefore, Summit visa dates should fall within the period of 28 August to 6 September. Participants who wish to extend their stay beyond this period
                    will be required to convert their visa from a Non-Tourist Visa to a Tourist Visa after their arrival in Bhutan, subject to applicable visa fees and daily SDF charges.
                    Please refer to <strong>Section 16: Extending Your Stay in Bhutan</strong> in the Information Note for further details, and see
                    <a href="{$yourStayUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}">Your Stay in Bhutan</a> on the
                    <a href="{$summitUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}">event website</a>.
                </p>
            </div>
        </div>

        <div style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
            <div style="background: #f9fafb; padding: 12px 16px; border-bottom: 1px solid #e5e7eb;">
                <p style="margin: 0; font-weight: 800;">General conditions for entry into Bhutan</p>
            </div>
            <div style="background: #ffffff; padding: 14px 16px;">
                <ul style="margin: 0; padding-left: 18px; line-height: 1.7;">
                    <li>The passport must be valid for six months from the intended date of departure from Bhutan.</li>
                    <li>Passport copy should be in JPEG or PNG format.</li>
                    <li><strong>Requirement for passport-sized photograph:</strong> should not be older than six months; have a white background (without border); should be a colour copy, portrait style with eyes open and forehead and ears clearly visible.</li>
                    <li>The visa application fee (already included in your ticket price along with the application assistance fee) is non-refundable and non-transferable.</li>
                    <li>The passport used for your e-visa application must be presented at the Port of Entry.</li>
                    <li>The issuance of an e-visa does not guarantee entry or stay in the Kingdom. Admission is subject to approval by immigration authorities in accordance with national regulations.</li>
                    <li>Visitors entering as non-immigrants are not eligible to apply for immigrant status or citizenship.</li>
                </ul>
            </div>
        </div>

        <p style="margin: 16px 0 0 0; line-height: 1.6;">
            Should you require any assistance during the visa form fill-up or if you have any other queries, please do not hesitate to reach out. We are happy to support you.
            You may also contact us at <strong>cofsa@undp.org</strong>.
        </p>

        <p style="margin: 16px 0 0 0; line-height: 1.6;">
            We look forward to welcoming you to Bhutan for an inspiring and meaningful Summit experience.
        </p>

        <p style="margin: 12px 0 0 0;">Warm regards,</p>
        <p style="margin: 4px 0 0 0; font-weight: 800;">The Organizing Team of the
            <a href="{$summitUrl}" target="_blank" rel="noopener noreferrer" style="{$linkStyle}">Global Conscious Food Systems Summit</a></p>
    </div>
</div>
HTML;
  }
}
