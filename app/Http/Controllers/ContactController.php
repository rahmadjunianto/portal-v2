<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact form page.
     */
    public function index(): View
    {
        $settings = Setting::getInstance();

        return view('contact', [
            'settings' => $settings,
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        // Rate limiting - max 3 submissions per minute per IP
        $rateLimitKey = 'contact-form:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ], 429);
        }

        // Validate request with honeypot check
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:20',
            'subject' => 'required|string|min:5|max:200',
            'message' => 'required|string|min:20|max:5000',
            // Honeypot field - should be empty
            'website' => 'nullable|string|max:0',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'subject.min' => 'Subjek minimal 5 karakter.',
            'message.required' => 'Isi pesan wajib diisi.',
            'message.min' => 'Pesan minimal 20 karakter.',
        ]);

        // Check honeypot - if filled, it's a bot
        if ($request->filled('website')) {
            // Silently fail - bot detected
            RateLimiter::hit($rateLimitKey, 60);
            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim.',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        // Additional spam check - detect excessive links
        $message = strtolower($request->input('message'));
        $linkCount = substr_count($message, 'http://') + substr_count($message, 'https://');
        if ($linkCount > 3) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan mengandung terlalu banyak tautan. Maksimal 3 tautan diperbolehkan.',
            ], 422);
        }

        try {
            // Send email notification
            $settings = Setting::getInstance();
            $recipientEmail = $settings->contact_email ?? config('mail.from.address');

            Mail::to($recipientEmail)->send(new ContactFormMail(
                $request->input('name'),
                $request->input('email'),
                $request->input('phone'),
                $request->input('subject'),
                $request->input('message')
            ));

            // Clear rate limit on success
            RateLimiter::clear($rateLimitKey);

            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.',
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}
