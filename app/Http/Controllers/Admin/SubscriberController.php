<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(20);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function exportCsv()
    {
        $subscribers = Subscriber::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=subscribers.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Subscribed At', 'Unsubscribed At', 'Status']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->id,
                    $subscriber->email,
                    $subscriber->created_at ? $subscriber->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('Y-m-d H:i:s') : 'N/A',
                    $subscriber->unsubscribed_at ? 'Unsubscribed' : 'Active'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function exportPdf()
    {
        $subscribers = Subscriber::all();
        $pdf = Pdf::loadView('admin.subscribers.export_pdf', compact('subscribers'));
        return $pdf->download('subscribers.pdf');
    }

    public function exportHtml()
    {
        $subscribers = Subscriber::all();
        $html = view('admin.subscribers.export_html', compact('subscribers'))->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="subscribers.html"');
    }
}