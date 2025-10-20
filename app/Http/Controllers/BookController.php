<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use \App\Models\Book;
use Illuminate\View\View;
use App\Models\pinjamBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $books = Book::paginate(10);

        $search = $request->input('search'); 

        $books = Book::where('judul_buku', 'like', '%' . $search . '%')->latest()->paginate(10);

        return view('books.index', compact('books', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'status' => 'required|boolean',
            'tahun_terbit' => 'required|integer',
            'jumlah_stock' => 'required|integer',
            'deskripsi' => 'required',
        ]);

        Book::create($request->all());
        return redirect()->route('books.index')->with('success', 'Book created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $books = Book::findOrFail($id);
        return view('books.show', compact('books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $books = book::findOrFail($id);
        return view('books.edit', compact('books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul_buku' => 'required',
            'penulis' => 'required', 
            'kategori' => 'required',
            'status' => 'required|boolean',
            'tahun_terbit' => 'required|integer',
            'jumlah_stock' => 'required|integer',
            'deskripsi' => 'required',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());
        return redirect()->route('books.index')->with('success', 'Buku Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku Berhasil Dihapus');
    }
    

    // RIWAYAT ---------------------------------------------------------------------------------------------


    public function riwayat(Request $request): View{
        // $loans = pinjamBuku::paginate(10);
        $search = $request->input('search'); 

        // $loans = Book::where('judul_buku', 'like', '%' . $search . '%')
        $loans = PinjamBuku::whereHas('User', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
        })->latest()->paginate(10);


        return view('books.riwayat', compact('loans', 'search'));
    }

    public function updateRiwayat(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $loans = pinjamBuku::findOrFail($id);
        $user = User::find(Auth::id());

        
        $loans->update([
            'status' => $request->status,
        ]);

        if ($loans->status == 'diperiksa') {
            // Periksa apakah buku_diperiksa tidak negatif
            if ($user->buku_diperiksa >= 0) {
                $user->increment('buku_diperiksa');
            }
        } elseif ($loans->status == 'borrowed' && $user->buku_diperiksa > 0) {
            $user->decrement('buku_diperiksa');
        }

        return redirect()->route('books.riwayat');
    }

    public function deleteRiwayat(string $id)
{
    $loan = pinjamBuku::find($id);
    $user = User::find(Auth::id());

    // Jika peminjaman ditemukan, proses pengembalian
    if ($loan) {
        // Temukan buku yang terkait dengan peminjaman
        $book = Book::find($loan->book_id);

        // Jika buku ditemukan, tambah jumlah stoknya
        if ($book) {
            // Pastikan jumlah stok tidak melebihi batas maksimal atau menjadi negatif
            $book->increment('jumlah_stock');
        }

        // Pastikan bahwa jumlah buku yang diperiksa oleh pengguna tidak menjadi negatif
        if ($user && $user->buku_diperiksa > 0) {
            $user->decrement('buku_diperiksa');
        } elseif ($user) {
            // Jika buku_diperiksa sudah 0 atau negatif, set menjadi 0
            $user->update(['buku_diperiksa' => 0]);
        }

        $loan->delete();

        return redirect()->route('books.riwayat')->with('success', 'Buku Sudah Dikembalikan');
    }

    return redirect()->route('books.riwayat')->with('error', 'Peminjaman tidak ditemukan');
}


    public function destroyRiwayat(string $id)
    {
        $loan = pinjamBuku::find($id);
        $user = User::find(Auth::id());


        if ($loan) {
            $user = User::find(Auth::id());
    
            // dd($user);

            if ($user) {
                // Increment kolom 'buku_rusak' untuk pengguna yang sedang login
                $user->increment('buku_rusak');
            }

            if ($user && $user->buku_diperiksa > 0) {
                $user->decrement('buku_diperiksa');
            } elseif ($user) {
                // Jika buku_diperiksa sudah 0 atau negatif, set menjadi 0
                $user->update(['buku_diperiksa' => 0]);
            }
    
            $loan->delete();
        }

        return redirect()->route('books.riwayat')->with('success', 'Buku Sudah Dihapus Dari Stock');
    }

    public function perpanjang(Request $request, string $id)
    {
        $loan = pinjamBuku::findOrFail($id);

        $request->validate([
            'tanggal_kembali' => 'required'
        ]);

        $loan->update([
            'tanggal_kembali' => $request->tanggal_kembali
        ]);

        $loan->update($request->all());
        return redirect()->route('books.riwayat')->with('success', 'Masa Berhasil Diperpanjang');
    }
}