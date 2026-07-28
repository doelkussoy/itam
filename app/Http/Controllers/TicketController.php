<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Pic;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['employee', 'asset']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%")
                ->orWhereHas('employee', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        if ($request->has('status') && $request->status != '') {
            $statuses = explode(',', $request->status);
            if (count($statuses) > 1) {
                $query->whereIn('status', $statuses);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(10)->appends($request->all());

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        $assets = Asset::orderBy('asset_tag')->get();
        $pics = Pic::orderBy('name')->get();
        $categories = $this->getJobdeskCategories();
        $templates = $this->getTicketTitleTemplates();

        return view('tickets.create', compact('employees', 'assets', 'pics', 'categories', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'pic_id' => 'nullable|exists:pics,id',
            'employee_id' => 'required|exists:employees,id',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'created_at' => 'nullable|date',
        ]);

        Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'pic_id' => $request->pic_id,
            'employee_id' => $request->employee_id,
            'asset_id' => $request->asset_id,
            'priority' => $request->priority,
            'status' => 'Open',
            'created_at' => $request->created_at ?: now(),
        ]);

        return redirect()->route('tickets.index', request()->query())->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        $employees = Employee::orderBy('name')->get();
        $assets = Asset::orderBy('asset_tag')->get();
        $pics = Pic::orderBy('name')->get();
        $categories = $this->getJobdeskCategories();
        $templates = $this->getTicketTitleTemplates();

        return view('tickets.edit', compact('ticket', 'employees', 'assets', 'pics', 'categories', 'templates'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string',
            'pic_id' => 'nullable|exists:pics,id',
            'employee_id' => 'required|exists:employees,id',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'created_at' => 'nullable|date',
        ]);

        $completedAt = $ticket->completed_at;
        if (in_array($request->status, ['Closed', 'Resolved']) && ! in_array($ticket->status, ['Closed', 'Resolved'])) {
            $completedAt = now();
        } elseif (in_array($request->status, ['Open', 'In Progress'])) {
            $completedAt = null;
        }

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'pic_id' => $request->pic_id,
            'employee_id' => $request->employee_id,
            'asset_id' => $request->asset_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'completed_at' => $completedAt,
            'created_at' => $request->created_at ?: $ticket->created_at,
        ]);

        return redirect()->route('tickets.index', $request->query())->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket, Request $request)
    {
        $ticket->delete();

        return redirect()->route('tickets.index', $request->query())->with('success', 'Ticket deleted successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
        ]);

        $completedAt = $ticket->completed_at;
        if (in_array($request->status, ['Closed', 'Resolved']) && ! in_array($ticket->status, ['Closed', 'Resolved'])) {
            $completedAt = now();
        } elseif (in_array($request->status, ['Open', 'In Progress'])) {
            $completedAt = null;
        }

        $ticket->update([
            'status' => $request->status,
            'completed_at' => $completedAt,
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'status' => $ticket->status]);
    }

    public function exportExcel()
    {
        return Excel::download(new TicketExport, 'tickets.xlsx');
    }

    private function getTicketTitleTemplates()
    {
        return [
            'Troubleshooting Jaringan (Router/Switch/WiFi/Internet lambat)',
            'Troubleshooting Hardware (PC/Laptop/Printer/Scanner)',
            'Instalasi dan Setup Komputer/Laptop User Baru',
            'Instalasi Software, Antivirus, dan Aplikasi Kantor',
            'Preventive Maintenance Perangkat IT',
            'Permintaan Pengadaan/Penggantian Sparepart (Kabel/RAM/Toner)',
            'Pengecekan, Troubleshooting, atau Pemasangan CCTV',
            'Penarikan Data Rekaman CCTV (Investigasi/Kehilangan)',
            'Administrasi Moodle (Upload materi/Reset password user)',
            'Kendala Login / Error pada Aplikasi Internal',
            'Support Sistem Mesin Checkweigher',
            'Permintaan Desain Grafis (Poster K3L/Banner/Infografis)',
            'Dokumentasi Foto / Video Kegiatan Perusahaan',
            'Setup Perangkat Meeting/Training (Proyektor/Audio/Zoom)',
            'Instalasi Kabel Pendukung (Power/LAN/HDMI)',
        ];
    }

    private function getJobdeskCategories()
    {
        return [
            ['name' => 'Manajemen Dukungan IT', 'description' => 'Mengawasi dan mengkoordinasikan seluruh aktivitas dukungan IT serta memastikan layanan IT berjalan optimal.'],
            ['name' => 'Infrastruktur Jaringan', 'description' => 'Monitoring, konfigurasi, maintenance, dan troubleshooting router, switch, access point, firewall, serta perangkat jaringan lainnya.'],
            ['name' => 'Server, Backup & Security', 'description' => 'Mengelola server, monitoring performa, backup data, disaster recovery, keamanan sistem, antivirus, dan perlindungan malware.'],
            ['name' => 'Hardware & Software Support', 'description' => 'Melakukan troubleshooting, instalasi, konfigurasi, update software, sistem operasi, aplikasi kantor, dan standarisasi perangkat user.'],
            ['name' => 'Preventive Maintenance IT', 'description' => 'Melakukan pengecekan kondisi perangkat, optimasi sistem, cleaning hardware, update patch keamanan, dan preventive maintenance berkala.'],
            ['name' => 'IT Asset Management', 'description' => 'Mengelola aset IT meliputi pendataan, inventarisasi, labeling, mutasi, monitoring kondisi, pengadaan, peremajaan, dan disposal aset.'],
            ['name' => 'Sparepart & Inventaris Perangkat', 'description' => 'Menjaga akurasi data inventaris serta ketersediaan sparepart seperti kabel, RAM, toner, dan perangkat replacement.'],
            ['name' => 'CCTV & Security System', 'description' => 'Melakukan instalasi, konfigurasi, monitoring, maintenance CCTV, NVR/DVR, storage, cabling, backup rekaman, dan investigasi rekaman.'],
            ['name' => 'Monitoring Keamanan Area', 'description' => 'Monitoring area pabrik melalui CCTV untuk mendukung 5R, K3L, keamanan operasional, serta membuat laporan temuan.'],
            ['name' => 'Learning Management System', 'description' => 'Mengelola Moodle perusahaan meliputi user, materi, akses, monitoring pembelajaran, raport, dan konten training.'],
            ['name' => 'Helpdesk & User Support', 'description' => 'Menangani tiket gangguan IT, analisa masalah, troubleshooting, eskalasi, dan memberikan arahan teknis kepada operator IT Support.'],
            ['name' => 'Pengembangan Aplikasi Internal', 'description' => 'Melakukan analisa kebutuhan sistem, perancangan, coding, testing, implementasi, maintenance aplikasi, dan digitalisasi proses kerja.'],
            ['name' => 'Support Sistem Produksi', 'description' => 'Mendukung sistem mesin Checkweigher meliputi monitoring koneksi, backup database produksi, import/export data, dan troubleshooting.'],
            ['name' => 'Dokumentasi & Administrasi IT', 'description' => 'Membuat laporan pekerjaan IT, laporan infrastruktur, CCTV, aplikasi, maintenance, temuan teknis, proposal, PR, approval, berita acara, dan inventaris.'],
            ['name' => 'Planning & Improvement IT', 'description' => 'Menyusun planning pekerjaan IT, improvement teknologi, implementasi teknologi baru, serta peningkatan efektivitas sistem kerja IT.'],
            ['name' => 'Training Internal IT', 'description' => 'Memberikan training teknis kepada tim IT, membuat modul pelatihan, dan melakukan evaluasi kemampuan teknis.'],
            ['name' => 'Koordinasi Vendor & Departemen', 'description' => 'Berkoordinasi dengan seluruh departemen, vendor hardware/software, provider internet, vendor CCTV, dan pihak terkait lainnya.'],
            ['name' => 'Multimedia Meeting & Event Support', 'description' => 'Menyiapkan perangkat meeting, training, audit, dan event seperti proyektor, TV display, laptop presentasi, speaker, microphone, kabel, dan converter.'],
            ['name' => 'Conference System Support', 'description' => 'Melakukan support Zoom Meeting, Microsoft Teams, Google Meet, conference system, serta memastikan kompatibilitas perangkat.'],
            ['name' => 'Dokumentasi Foto & Video', 'description' => 'Melakukan dokumentasi kegiatan perusahaan, training, event, improvement, laporan, dan kebutuhan komunikasi internal.'],
            ['name' => 'Desain Grafis & Komunikasi Visual', 'description' => 'Membuat poster, banner, flyer, infografis, template presentasi, materi publikasi internal, dan kebutuhan desain departemen.'],
            ['name' => 'Konten Multimedia Edukasi', 'description' => 'Membuat video tutorial, video safety, video training operator, materi sosialisasi, dan konten edukasi perusahaan.'],
            ['name' => 'Pengelolaan Perangkat Multimedia', 'description' => 'Mengelola kamera, handycam, microphone, speaker, proyektor, TV display, serta melakukan maintenance sederhana.'],
            ['name' => 'Standarisasi & Improvement Digital', 'description' => 'Membuat SOP penggunaan perangkat digital, dokumentasi improvement IT, rekomendasi fasilitas multimedia, dan teknologi baru.'],
            ['name' => 'Diluar Jobdesc', 'description' => 'Pekerjaan atau tugas tambahan yang tidak tercakup dalam daftar jobdesk utama IT.'],
        ];
    }
}
