<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUsers;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function dashboard(Request $request)
    {
        if (Auth::user()->role->nama_role == "Administrator") {

            $events = Event::orderBy('nama_event')->get();

            // Kalau belum memilih event
            if (!$request->event_id) {
                return view('modules.dashboard', [
                    'events' => $events,
                    'selectedEvent' => null
                ]);
            }

            $eventId = $request->event_id;
        } else {

            // Admin Event
            $cek = EventUsers::where('user_id', Auth::id())->first();

            if (!$cek) {
                abort(403);
            }

            $eventId = $cek->event_id;

            $events = collect();
        }

        $selectedEvent = Event::find($eventId);

        $totalTamu = Guest::where("event_id", $eventId)->count();

        $tamuHadir = GuestCheckin::where("event_id", $eventId)->count();

        $belumHadir = $totalTamu - $tamuHadir;

        $totalHadir = Guest::where("event_id", $eventId)
            ->where("status_kehadiran", 1)
            ->count();

        $totalTamuLuarHadir = GuestPublic::where("event_id", $eventId)->count();

        $persen = $totalTamu > 0
            ? round(($tamuHadir / $totalTamu) * 100)
            : 0;

        $guest_invitation = GuestCheckin::with('guest.kategori')
            ->where("event_id", $eventId)
            ->latest()
            ->limit(10)
            ->get();

        $guest_public = GuestPublic::where("event_id", $eventId)
            ->latest()
            ->limit(10)
            ->get();

        $kedatangan = GuestCheckin::where("event_id", $eventId)
            ->selectRaw('HOUR(waktu_checkin) as jam, COUNT(*) as total')
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartJam = [];
        $chartTotal = [];

        foreach ($kedatangan as $row) {
            $chartJam[] = sprintf('%02d:00', $row->jam);
            $chartTotal[] = $row->total;
        }

        return view('modules.dashboard', compact(
            'events',
            'selectedEvent',
            'totalTamu',
            'tamuHadir',
            'belumHadir',
            'totalHadir',
            'persen',
            'guest_invitation',
            'guest_public',
            'chartJam',
            'chartTotal',
            'totalTamuLuarHadir'
        ));
    }

    public function error_page()
    {
        return view("modules.error-page");
    }

    public function landing_page()
    {
        $data = [
            'guestName' => request()->query('to', 'Tamu Undangan'),
            'couple' => [
                'bride' => 'Alya Putri',
                'groom' => 'Bima Pratama',
                'display_name' => 'Alya & Bima',
                'groom_parents' => 'Putra dari Bapak Rama Wijaya & Ibu Shinta Adelia',
                'bride_parents' => 'Putri dari Bapak Ahmad Santoso & Ibu Siti Aisyah',
                'groom_ig' => 'bima.pratama',
                'bride_ig' => 'alya.putri',
            ],
            'event' => [
                'date_label' => 'Sabtu, 12 Oktober 2026',
                'akad' => '10:00 WIB',
                'resepsi' => '19:00 WIB',
                'datetime_iso' => '2026-10-12T10:00:00+07:00',
                'address' => 'Aula Serbaguna, Jl. Melati Putih No. 12, Jakarta',
                'maps_url' => 'https://maps.google.com/?q=Aula+Serbaguna+Jl+Melati+Putih+No+12+Jakarta',
            ],
            'story' => [
                [
                    'title' => 'Awal Bertemu',
                    'year' => '2019',
                    'text' => 'Kami dipertemukan oleh percakapan sederhana yang kemudian tumbuh menjadi perjalanan penuh makna.',
                ],
                [
                    'title' => 'Menuju Hari Bahagia',
                    'year' => '2026',
                    'text' => 'Dengan restu keluarga dan doa orang terdekat, kami melangkah menuju ikatan suci pernikahan.',
                ],
            ],
            'quran' => [
                'translation' => 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda bagi kaum yang berpikir.',
                'source' => 'Q.S. AR-RUM: 21',
            ],
            'coupleImage' => 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?auto=format&fit=crop&w=1200&q=80',
            'groomPhoto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=1200&q=80',
            'bridePhoto' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=80',
            'gallery' => [
                [
                    'src' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=900&q=80',
                    'label' => 'Momen Hangat',
                ],
                [
                    'src' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80',
                    'label' => 'Hari Bahagia',
                ],
                [
                    'src' => 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=80',
                    'label' => 'Potret Romantis',
                ],
                [
                    'src' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=900&q=80',
                    'label' => 'Cerita Cinta',
                ],
            ],
            'angpau' => [
                'title' => 'Titip Kasih / Angpau',
                'desc' => 'Doa restu Anda merupakan kado terindah bagi kami. Namun bagi Bapak/Ibu/Saudara/i yang ingin mengirimkan tanda kasih berupa angpau digital, dapat mengirimkannya melalui rekening resmi kami di bawah ini:',
                'accounts' => [
                    [
                        'bank' => 'BCA',
                        'account_number' => '123-4567-890',
                        'account_name' => 'Rama Wijaya',
                    ],
                    [
                        'bank' => 'Mandiri',
                        'account_number' => '987-6543-210',
                        'account_name' => 'Shinta Adelia',
                    ],
                ],
            ],
            'thanks' => [
                'title' => 'Ucapan Terima Kasih',
                'text' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu. Atas kehadiran dan doa restunya kami ucapkan terima kasih.',
            ],
        ];

        return view("landing-page.index", $data);
    }
}
