@extends('layouts.app-admin')

@section('title', 'Stok Keluar')

@section('admin')
    <div class="mt-3">
        <div class="text-xl font-medium mb-8">List Stok Keluar</div>

        {{-- Table --}}
        <div class="overflow-x-auto relative">
            <table class="w-full text-sm text-left text-gray-500" id="crudTable">
                <thead class="text-xs text-white uppercase bg-indigo-500">
                    <tr class="divide-x divide-y">
                        <th scope="col" class="py-3 px-6">
                            Tanggal Keluar
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Gambar Produk
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Nama Produk
                        </th>
                        <th scope="col" class="py-3 px-6">
                            Jumlah
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        var datatable = $('#crudTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: {
                url: 'https://' + '{!! request()->getHttpHost().request()->getRequestUri() !!}',
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at',
                    className: 'py-4 px-6 whitespace-nowrap',
                    width: '10%',
                    render: function(data, type, row) {
                        const date = new Date(data);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                        });
                        return formattedDate.replace('pukul', '-');
                    }
                },
                {
                    data: 'image',
                    name: 'image',
                    className: 'py-4 px-6',
                    width: '15%',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'product.name',
                    name: 'product.name',
                    className: 'py-4 px-6',
                    width: '20%'
                },
                {
                    data: 'outcoming_stock',
                    name: 'outcoming_stock',
                    className: 'py-4 px-6',
                    width: '10%',
                },
            ]
        })
    </script>
@endpush
