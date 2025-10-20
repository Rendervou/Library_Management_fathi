<x-app-layout>

    

    <section class="bg-white dark:bg-gray-900 p-3 sm:p-5 lg:pl-64 mx-5">
        <h1 class="dark:text-white text-center font-extrabold text-3xl my-10">Riwayat Buku Dipinjam</h1>
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4 mb-10">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center" id="form-search-anggota-riwayat" method="GET">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <button onclick="clearInput()" type="button" class="absolute inset-y-0 right-1 flex items-center pl-3">
                                    <svg  class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                                    </svg>
                                </button>
                                <input value="{{ $search }}" type="text" id="search-anggota-riwayat" name="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Cari Nama" required="">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- @if($loans->isEmpty())
                    <p class="dark:text-gray-300 text-gray-700">Data Belum Tersedia</p>
                @endif --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">No</th>
                                <th scope="col" class="px-4 py-3">Judul Buku</th>
                                <th scope="col" class="px-4 py-3">Penulis</th>
                                <th scope="col" class="px-4 py-3">Nama Peminjam</th>
                                <th scope="col" class="px-4 py-3">Tanggal Pinjam</th>
                                <th scope="col" class="px-4 py-3">Tanggal Kembali</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                        @foreach ($loans as $loan)

                        @php
                            $time = date('Y-m-d');
                            $timeClass = $loan->tanggal_kembali === $time ? 'text-red-500' : '';
                            $timeClass2 = $loan->tanggal_kembali < $time ? 'bg-red-500 text-white' : '';
                        @endphp
                            <tr class="border-b dark:border-gray-700 {{ $timeClass. ' ' .$timeClass2 }}">
                                <td class="px-4 py-3">{{ $no++ }}</td>
                                <td class="px-4 py-3">{{ Str::words($loan->book->judul_buku, 5) }}</td>
                                <td class="px-4 py-3">{{ $loan->book->penulis }}</td>
                                <td class="px-4 py-3">{{ $loan->user->name }}</td>
                                <td class="px-4 py-3">{{ $loan->tanggal_pinjam }}</td>
                                <td class="px-4 py-3">{{ $loan->tanggal_kembali }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $loan->status === 'borrowed' ? 'bg-red-500 border-2 text-black p-1 rounded-md' : 'bg-yellow-500 border-2 text-black p-1 rounded-md' }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        @php
                        // dump(request('search'));

                        $no --  ;
                        $msg;
                        $search = $search ?? null;

                        if ($no == 0 && is_null($search)) {
                            $msg = "<span style='color:red; margin-left:1.25rem;'>Data Belum Tersedia</span>";
                        }
                        elseif ($no == 0 && isset($search)) {
                            $msg = "<p class='text-white ml-5'>Nama Yang Anda Cari \"<span style='color:red;'>$search</span>\" Tidak Ada</p>";
                        }
                        elseif ($no > 0 && isset($search)) {
                            $msg = "<p class='text-white ml-5'>Menampilkan <span style='color:rgb(1, 255, 1);'>$no</span> hasil pada pencarian Nama \"<span style='color:rgb(1, 255, 1);'>$search</span>\"</p>";
                        }
                        else {
                            $msg = "";
                        };

                        echo $msg;
                        // echo request('search');
                        // echo $search;
                        // echo $no;
                        @endphp

                        </tbody>
                    </table>
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
        </section>

</x-app-layout>

<script>
    function clearInput(){
        document.getElementById('search-anggota-riwayat').value = '';
        document.getElementById('form-search-anggota-riwayat').submit();
    };
</script>