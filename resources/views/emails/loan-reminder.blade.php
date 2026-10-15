<x-mail::message>
# Konfirmai Pengembalian Equipment

Hi {{ $loan->user->name }},

Terima kasih telah mengembalikan **{{ $loan->equipment->name }}**.

**Detail :**
- Tanggal Peminjaman: {{ $loan->loan_date }}
- Tanggal Pengembalian: {{ $loan->return_date }}
- Status: {{ $loan->status }}

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>